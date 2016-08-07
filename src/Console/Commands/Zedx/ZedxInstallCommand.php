<?php

namespace ZEDx\Console\Commands\Zedx;

use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\TableSeparator;
use ZEDx\Core;
use ZEDx\Utils\Installer;

class ZedxInstallCommand extends Command
{
    /**
     * Installer.
     *
     * @var ZEDx\Utils\Installer
     */
    protected $installer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zedx:install
                            {--force : Force the operation to run}
                            {--quick : Quick install}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install ZEDx Core.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Installer $installer)
    {
        parent::__construct();
        $this->installer = $installer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $warning = 'Force the operation to run';

        $force = $this->option('force');

        if ($force) {
            $this->comment(str_repeat('*', strlen($warning) + 12));
            $this->comment('*     '.$warning.'     *');
            $this->comment(str_repeat('*', strlen($warning) + 12));
            $this->output->writeln('');
        }

        if (!$force && file_exists(base_path('.env'))) {
            $this->error('ZEDx is already installed on your server.');

            return;
        }

        // check system
        $this->checkSystem();

        $this->display($this->installer->pullLog());

        $database = $this->option('quick') ? 'Sqlite' : $this->choice('Which database would you use ?', [
            'Sqlite', 'MySQL',
            'PostgreSQL', 'SQL Server',
        ], 0);

        // check database
        $this->setDatabaseConfig($database);
        $this->installer->checkDatabase();

        $this->display($this->installer->pullLog());

        // Admin
        $this->setAdminConfig();
        $this->installer->checkAdmin();

        $this->display($this->installer->pullLog());

        // Setting
        $this->setSettingConfig();
        $this->installer->checkSettings();
        $this->display($this->installer->pullLog());

        // Permissions
        $this->installer->changePermissions();
        $this->display($this->installer->pullLog());

        // Build config
        $this->installer->buildConfigs();
        $this->display($this->installer->pullLog());

        // Database migration
        $this->comment('~ Starting Database Migration');
        $this->call('migrate:refresh', ['--seed' => true, '--force' => true]);

        // Create Admin Account
        $this->installer->createAdminAccount();
        $this->display($this->installer->pullLog());

        // Create settings
        $this->installer->createSetting();
        $this->display($this->installer->pullLog());

        // Set default Theme
        $this->installer->setDefaultTheme();
        $this->display($this->installer->pullLog());

        // Create symlinks
        $this->installer->createSymLinks();
        $this->display($this->installer->pullLog());

        // Enable website
        $this->installer->enableWebsite();
        $this->display($this->installer->pullLog());

        // Publish Backend Assets
        $this->table([], [['Publishing Backend Assets']]);
        $this->output->writeln('');
        $this->call('backend:publish', ['--force' => true]);

        // Publish Widgets Assets
        $this->table([], [['Publishing Widgets Assets']]);
        $this->output->writeln('');
        $this->call('widget:publish', ['--force' => true]);

        $this->sayThankYou();
    }

    protected function checkSystem()
    {
        $codes = [
            'writePermission', 'phpVersion', 'pdoLibrary',
            'mcryptLibrary', 'mbstringLibrary', 'sslLibrary',
            'gdLibrary', 'curlLibrary', 'zipLibrary', 'procOpen',
        ];

        foreach ($codes as $code) {
            if (!$this->installer->checkCode($code)) {
                throw new Exception(last($this->installer->getLog()));
            }
        }

        return true;
    }

    protected function display($data)
    {
        foreach ($data as $message) {
            $this->info($message);
        }
    }

    protected function setAdminConfig()
    {
        $_REQUEST['admin_name'] = $this->option('quick') ? 'Administrator' : $this->ask('Admin name', 'Administrator');
        $_REQUEST['admin_email'] = $this->option('quick') ? 'admin@example.com' : $this->ask('Admin email', 'admin@example.com');
        $_REQUEST['admin_password'] = $this->option('quick') ? 'password' : $this->ask('Admin password', 'password');
    }

    protected function setSettingConfig()
    {
        $_REQUEST['website_name'] = $this->option('quick') ? 'My Classifieds Website' : $this->ask('Website name', 'My Classifieds Website');
        $_REQUEST['website_description'] = $this->option('quick') ? 'Classifieds website based on ZEDx.io' : $this->ask('Website description', 'Classifieds website based on ZEDx.io');
    }

    protected function setDatabaseConfig($database)
    {
        // Sqlite
        if ($database == 'Sqlite') {
            $_REQUEST['db_type'] = 'sqlite';
            $_REQUEST['db_name'] = $this->option('quick') ? 'database.sqlite' : $this->ask('Database filename ?', 'database.sqlite');

            return;
        }

        // MySql
        if ($database == 'MySQL') {
            $_REQUEST['db_type'] = 'mysql';
        }

        // PostgreSQL
        if ($database == 'PostgreSQL') {
            $_REQUEST['db_type'] = 'pgsql';
        }
        // Sql server
        if ($database == 'SQL Server') {
            $_REQUEST['db_type'] = 'sqlsrv';
        }

        $_REQUEST['db_host'] = $this->option('quick') ? 'localhost' : $this->ask('Database Host', 'localhost');
        $_REQUEST['db_port'] = $this->option('quick') ? $this->getDefaultPort($database) : $this->ask('Database Port', $this->getDefaultPort($database));
        $_REQUEST['db_username'] = $this->ask('Database Username');
        $_REQUEST['db_password'] = $this->ask('Database Password');
        $_REQUEST['db_name'] = $this->ask('Database Name');
    }

    protected function getDefaultPort($database)
    {
        switch ($database) {
            case 'MySQL':
                return 3306;
                break;
            case 'PostgreSQL':
                return 5432;
                break;
            case 'SQL Server':
                return 1433;
                break;
        }
    }

    protected function sayThankYou()
    {
        $this->output->writeln('');
        $message = 'Thank you for installing ZEDx <comment>v.'.Core::VERSION.'</comment>';
        $this->info(str_repeat('*', strlen($message) - 7));
        $this->info('*'.str_repeat(' ', strlen($message) - 9).'*');
        $this->info('*     '.$message.'     *');
        $this->info('*'.str_repeat(' ', strlen($message) - 9).'*');
        $this->info(str_repeat('*', strlen($message) - 7));
        $this->output->writeln('');

        $this->comment('~[ Installation summary ]~');
        $this->output->writeln('');

        $data = [
            ['Database Driver', '<info>'.env('DB_CONNECTION').'</info>'],
            ['Database Name', '<info>'.env('DB_DATABASE').'</info>'],
            new TableSeparator(),
            ['Administration area', '<info>http://yourwebsite/zxadmin</info>'],
            ['Administrator Email', '<info>'.$this->installer->post('admin_email').'</info>'],
            ['Administrator Password', '<info>'.$this->installer->post('admin_password').'</info>'],
        ];

        $this->table([], $data);
    }
}
