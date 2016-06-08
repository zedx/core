<?php

namespace ZEDx\Utils;

use Artisan;
use Exception;
use File;
use PDO;
use SQLite3;
use Themes;

class Installer
{
    private $logData = [];
    private $baseDirectory;
    private $databaseDirectory;

    public function __construct()
    {
        $this->baseDirectory = base_path();
        $this->databaseDirectory = $this->baseDirectory.DIRECTORY_SEPARATOR.'database';
        $this->logData = [];
    }

    public function checkDatabase()
    {
        if ($this->post('db_type') != 'sqlite') {
            if (!strlen($this->post('db_host'))) {
                throw new Exception('Please specify a database host');
            }

            if (!is_numeric($this->post('db_port'))) {
                throw new Exception('Please specify a database port');
            }

            if (!strlen($this->post('db_username'))) {
                throw new Exception('Please specify a database username');
            }

            if (!strlen($this->post('db_password'))) {
                throw new Exception('Please specify a database password');
            }
        }

        if (!strlen($this->post('db_name'))) {
            throw new Exception('Please specify the database name');
        }

        $config = [
            'type' => $this->post('db_type'),
            'host' => $this->post('db_host'),
            'port' => $this->post('db_port'),
            'name' => $this->post('db_name'),
            'user' => $this->post('db_username'),
            'pass' => $this->post('db_password'),
        ];

        extract($config);

        switch ($type) {
            case 'mysql':
                $dsn = 'mysql:host='.$host.';dbname='.$name;
                if ($port) {
                    $dsn .= ';port='.$port;
                }

                break;

            case 'postgresql':
                $_host = ($host) ? 'host='.$host.';' : '';
                $dsn = 'pgsql:'.$_host.'dbname='.$name;
                if ($port) {
                    $dsn .= ';port='.$port;
                }

                break;

            case 'sqlite':
                $dsn = 'sqlite:'.$this->databaseDirectory.DIRECTORY_SEPARATOR.$name;
                $this->validateSqliteFile($this->databaseDirectory.DIRECTORY_SEPARATOR.$name);
                break;

            case 'sqlserver':
                $availableDrivers = PDO::getAvailableDrivers();
                $_port = $port ? ','.$port : '';
                if (in_array('dblib', $availableDrivers)) {
                    $dsn = 'dblib:host='.$host.$_port.';dbname='.$name;
                } else {
                    $dsn = 'sqlsrv:Server='.$host.(empty($port) ? '' : ','.$_port).';Database='.$name;
                }
                break;
        }
        try {
            $db = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (Exception $ex) {
            throw new Exception('Connection failed: '.$ex->getMessage());
        }
    }

    public function checkAdmin()
    {
        if (!strlen($this->post('admin_name'))) {
            throw new Exception('Please specify administrator name');
        }

        if (!strlen($this->post('admin_email'))) {
            throw new Exception('Please specify administrator email address');
        }

        if (!filter_var($this->post('admin_email'), FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please specify valid email address');
        }

        if (!strlen($this->post('admin_password'))) {
            throw new Exception('Please specify password');
        }

        if (strlen($this->post('admin_password')) < 4) {
            throw new Exception('Please specify password length more than 4 characters');
        }

        if (strlen($this->post('admin_password')) > 255) {
            throw new Exception('Please specify password length less than 64 characters');
        }
    }

    public function checkSettings()
    {
        if (!strlen($this->post('website_name'))) {
            throw new Exception('Please specify your website name');
        }

        if (!strlen($this->post('website_description'))) {
            throw new Exception('Please specify your website description');
        }
    }

    /*
    Install
     */

    public function changePermissions()
    {
        $this->log('<comment>~ Changing permissions</comment>');

        rchmod(storage_path(), 0777, 0777);
        rchmod(base_path('bootstrap/cache'), 0777, 0777);

        if ($this->post('db_type') == 'sqlite') {
            $db_name = $this->post('db_name', '');
            rchmod($this->baseDirectory.DIRECTORY_SEPARATOR.$db_name, 0777, 0777);
        }

        $this->log('<info>[ + ] Changing permissions [OK]</info>');
    }

    public function buildConfigs()
    {
        $this->log('<comment>~ Building Configs</comment>');

        if (!File::exists(base_path('.env'))) {
            @File::copy(base_path('.env.example'), base_path('.env'));
        }

        $this->rewriteEnv([
            'MAIL_FROM_ADDRESS'  => $this->post('admin_email', 'mailer@example.com'),
            'MAIL_FROM_NAME'     => $this->post('website_name', 'ZEDx'),
            'APP_URL'            => $this->getBaseUrl(),
            'APP_LOCALE'         => 'fr',
            'APP_KEY'            => str_random(32),
            'APP_FRONTEND_THEME' => 'Default',
        ]);

        $this->rewriteEnv($this->getDatabaseConfigValues());

        $this->log('<info>[ + ] Building Configs [ OK ]</info>');

        $this->rewriteEnv([
            'APP_ENV' => 'local',
        ]);
    }

    public function migrateDatabase()
    {
        $this->log('<comment>~ Starting Database Migration</comment>');
        $this->artisan('migrate', ['--seed' => true, '--force' => true]);
    }

    private function artisan($command, $params = [])
    {
        $this->log('<comment>~ Starting Artisan command %s</comment>', $command);
        Artisan::call($command, $params);
        $this->log('<question>~Artisan response</question>');
        $this->log('<info>'.Artisan::output().'</info>');
    }

    public function createAdminAccount()
    {
        $this->log('<comment>~ Creating Admin Account</comment>');

        $admin = \ZEDx\Models\Admin::firstOrFail();
        $admin->name = $this->post('admin_name', 'Administrator');
        $admin->email = $this->post('admin_email', 'admin@example.com');
        $admin->password = $this->post('admin_password', 'password');

        $this->log('<info>[ + ] Creating Admin Account [ OK ]</info>');

        $admin->save();
    }

    public function createSetting()
    {
        $this->log('<comment>~ Creating Setting</comment>');

        $setting = \ZEDx\Models\Setting::firstOrFail();

        $setting->website_name = $this->post('website_name', 'ZEDx');
        $setting->website_url = $this->getBaseUrl();
        $setting->website_title = $this->post('website_title', 'ZEDx');
        $setting->website_description = $this->post('website_description', 'Classifieds CMS');
        $setting->save();

        $this->log('<info>[ + ] Creating Setting [ OK ]</info>');
    }

    public function setDefaultTheme()
    {
        $this->log('<comment>~ Setting Default Theme</comment>');
        Themes::frontend()->setActive('Default');
        $this->log('<info>[ + ] Setting Default Theme [ OK ]</info>');
    }

    public function createSymLinks()
    {
        $this->log('<comment>~ Creating symbolic links</comment>');

        symlink(storage_path('app/uploads'), public_path('uploads'));

        $this->log('<info>[ + ] Creating symbolic links [ OK ]</info>');
    }

    public function enableWebsite()
    {
        $this->enableHtaccess();
        $this->rewriteEnv([
            'APP_ENV' => 'production',
        ]);
    }

    private function getDatabaseConfigValues()
    {
        $config = array_merge([
            'type'   => null,
            'host'   => null,
            'name'   => null,
            'port'   => null,
            'user'   => null,
            'pass'   => null,
            'prefix' => null,
        ], [
            'type'   => $this->post('db_type'),
            'host'   => $this->post('db_host', ''),
            'name'   => $this->post('db_name', ''),
            'port'   => $this->post('db_port', ''),
            'user'   => $this->post('db_username', ''),
            'pass'   => $this->post('db_password', ''),
            'prefix' => $this->post('db_prefix', 'zedx').'_',
        ]);

        extract($config);

        switch ($type) {
            default:
            case 'mysql':
                $result = [
                    'DB_HOST'     => $host,
                    'DB_PORT'     => empty($port) ? 3306 : $port,
                    'DB_DATABASE' => $name,
                    'DB_USERNAME' => $user,
                    'DB_PASSWORD' => $pass,
                    'DB_PREFIX'   => $prefix,
                ];
                break;

            case 'sqlite':
                $result = [
                    'DB_DATABASE' => $name,
                    'DB_PREFIX'   => $prefix,
                ];
                break;

            case 'pgsql':
                $result = [
                    'DB_HOST'     => $host,
                    'DB_PORT'     => empty($port) ? 5432 : $port,
                    'DB_DATABASE' => $name,
                    'DB_USERNAME' => $user,
                    'DB_PASSWORD' => $pass,
                    'DB_PREFIX'   => $prefix,
                ];
                break;

            case 'sqlsrv':
                $result = [
                    'DB_HOST'     => $host,
                    'DB_PORT'     => empty($port) ? 1433 : $port,
                    'DB_DATABASE' => $name,
                    'DB_USERNAME' => $user,
                    'DB_PASSWORD' => $pass,
                    'DB_PREFIX'   => $prefix,
                ];
                break;
        }

        if (in_array($type, ['mysql', 'sqlite', 'pgsql', 'sqlsrv'])) {
            $result['DB_CONNECTION'] = $type;
        }

        return $result;
    }

    public function getBaseUrl()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $baseUrl = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $baseUrl .= '://'.$_SERVER['HTTP_HOST'];
            $baseUrl .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        } else {
            $baseUrl = 'http://localhost/';
        }

        return $baseUrl;
    }

    private function validateSqliteFile($filename)
    {
        if (file_exists($filename)) {
            return;
        }

        $directory = dirname($filename);

        if (!is_dir($directory) && !mkdir($directory, 0777, true)) {
            throw new Exception("Can't create SQLite storage file");
        }

        new SQLite3($filename);
    }

    private function disableHtaccess()
    {
        @File::copy($this->baseDirectory.'/.htaccess', $this->baseDirectory.'/default.htaccess');
    }

    private function enableHtaccess()
    {
        @File::copy($this->baseDirectory.'/default.htaccess', $this->baseDirectory.'/.htaccess');
    }

    public function checkCode($code)
    {
        $result = false;
        switch ($code) {
            case 'writePermission':
                $result = is_writable($this->baseDirectory);
                break;
            case 'phpVersion':
                $result = version_compare(PHP_VERSION, '5.5.9', '>=');
                break;
            case 'pdoLibrary':
                $result = defined('PDO::ATTR_DRIVER_NAME');
                break;
            case 'mcryptLibrary':
                $result = extension_loaded('mcrypt');
                break;
            case 'mbstringLibrary':
                $result = extension_loaded('mbstring');
                break;
            case 'sslLibrary':
                $result = extension_loaded('openssl');
                break;
            case 'gdLibrary':
                $result = extension_loaded('gd');
                break;
            case 'curlLibrary':
                $result = function_exists('curl_init') && defined('CURLOPT_FOLLOWLOCATION');
                break;
            case 'zipLibrary':
                $result = class_exists('ZipArchive');
                break;
        }

        $this->log('Requirement %s %s', $code, ($result ? '[ OK ]' : '[ FAIL ]'));

        return $result;
    }

    public function post($var, $default = null)
    {
        if (array_key_exists($var, $_REQUEST)) {
            $result = $_REQUEST[$var];
            if (is_string($result)) {
                $result = trim($result);
            }

            return $result;
        }

        return $default;
    }

    private function rewriteEnv($newValues)
    {
        foreach ($newValues as $key => $value) {
            env_replace($key, $value);
        }

        $this->reloadConfig($newValues);
    }

    private function reloadConfig($configs)
    {
        foreach ($configs as $key => $value) {
            $code = null;

            if ($key == 'MAIL_FROM_ADDRESS') {
                $code = 'mail.from.address';
            }
            if ($key == 'MAIL_FROM_NAME') {
                $code = 'mail.from.name';
            }
            if ($key == 'APP_URL') {
                $code = 'app.url';
            }
            if ($key == 'APP_LOCALE') {
                $code = 'app.locale';
            }
            if ($key == 'APP_KEY') {
                $code = 'app.key';
            }
            if ($key == 'APP_ENV') {
                $code = 'app.env';
            }
            if ($key == 'DB_HOST') {
                $code = 'database.connections.'.$configs['DB_CONNECTION'].'.host';
            }
            if ($key == 'DB_PORT') {
                $code = 'database.connections.'.$configs['DB_CONNECTION'].'.port';
            }
            if ($key == 'DB_DATABASE') {
                $code = 'database.connections.'.$configs['DB_CONNECTION'].'.database';
            }
            if ($key == 'DB_USERNAME') {
                $code = 'database.connections.'.$configs['DB_CONNECTION'].'.username';
            }
            if ($key == 'DB_PASSWORD') {
                $code = 'database.connections.'.$configs['DB_CONNECTION'].'.password';
            }
            if ($key == 'DB_PREFIX') {
                $code = 'database.connections.'.$configs['DB_CONNECTION'].'.prefix';
            }
            if ($key == 'DB_CONNECTION') {
                $code = 'database.default';
            }

            if ($code !== null) {
                if ($code == 'database.connections.sqlite.database') {
                    $value = $this->databaseDirectory.DIRECTORY_SEPARATOR.$value;
                }

                config([$code => $value]);
            }
        }
    }

    //
    // Logging
    //

    public function clearLog()
    {
        $this->logData = [];
    }

    public function log()
    {
        $args = func_get_args();
        $message = array_shift($args);

        if (is_array($message)) {
            $message = implode(PHP_EOL, $message);
        }

        $this->logData[] = vsprintf($message, $args);
    }

    public function getLog()
    {
        return $this->logData;
    }

    public function pullLog()
    {
        $log = $this->logData;
        $this->logData = [];

        return $log;
    }
}
