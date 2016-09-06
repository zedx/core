<?php

namespace ZEDx\Utils;

use Carbon\Carbon;
use Exception;
use File;
use Modules;
use Themes;
use Widgets;
use ZEDx\Core;
use ZEDx\Models\Package;
use Zipper;

class Updater
{
    protected $is_console;
    protected $latestJson = [];
    protected $updateJson = [];
    protected $packageType;
    protected $packageNamespace;
    protected $packageVersion;
    protected $eventId;
    protected $eventProgress;
    protected $logData = [];
    protected $storageUpdateFolder;
    protected $initEventMessage = false;

    public function __construct()
    {
        $this->logData = [];
    }

    public function setPackageType($type)
    {
        $this->packageType = $type;

        return $this;
    }

    public function setPackageNamespace($namespace)
    {
        $this->packageNamespace = $namespace;
    }

    public function setPackageVersion($version)
    {
        $this->packageVersion = $version;
    }

    public function getPackageVersion()
    {
        return $this->packageVersion;
    }

    public function isLatest($force = false)
    {
        $list = $this->getUpdatesList($force);

        return empty($list['core'])
            && empty($list['theme'])
            && empty($list['module'])
            && empty($list['widget']);
    }

    public function isLatestPackage($type, $namespace)
    {
        $list = $this->getUpdatesList();

        return (bool) !array_get($list, $type.'.'.$namespace, false);
    }

    /**
     * Get all updates that should be updated.
     *
     * @return mixed
     */
    public function getUpdatesList($force = false)
    {
        static $result;

        if ($result) {
            return $result;
        }

        $result = [
            'core'    => [],
            'theme'   => [],
            'widget'  => [],
            'module'  => [],
        ];

        $setting = setting();

        if ($force || ($setting->api_checked_at && $setting->api_checked_at->diffInHours() >= 12)) {
            $this->updateVersionsFromApi();
        }

        $packages = Package::all();
        $componentsVersions = $this->getPackagesVersions();

        foreach ($packages as $package) {
            if (array_get($componentsVersions, $package->type.'.'.$package->namespace) == $package->api_version) {
                continue;
            }

            $result[$package->type][$package->namespace] = [
                'namespace' => $package->namespace,
                'date'      => $package->date,
                'version'   => $package->api_version,
            ];
        }

        return $result;
    }

    protected function updateVersionsFromApi()
    {
        $list = $this->getPackagesVersions();

        foreach ($list as $type => $packages) {
            foreach ($packages as $package => $version) {
                $this->setPackageType($type);
                $this->setPackageNamespace($package);

                $latest = $this->getLatest();

                $model = Package::firstOrNew([
                    'type'      => $type,
                    'namespace' => $package,
                ]);

                if ($latest == null) {
                    $model->delete();
                    continue;
                }


                $model->api_version = $latest->version;
                $model->date = Carbon::createFromFormat('F d Y H:i:s', $latest->date);

                $model->save();
            }
        }

        $setting = setting();

        $setting->api_checked_at = Carbon::now();
        $setting->save();
    }

    /**
     * Get all packages versions.
     *
     * @return mixed
     */
    public function getPackagesVersions()
    {
        $components = [
            'core'    => [],
            'theme'   => [],
            'widget'  => [],
            'module'  => [],
        ];

        $components['core']['ZEDx'] = Core::VERSION;

        foreach (Themes::all() as $theme) {
            $components['theme'][$theme['manifest']['name']] = $theme['manifest']['version'];
        }

        foreach (Widgets::noType()->noFilter()->all(null, true) as $namespace => $widget) {
            $components['widget'][$namespace] = $widget->get('version');
        }

        foreach (Modules::all() as $module) {
            $components['module'][$module->name] = $module->version;
        }

        return $components;
    }

    public function getLatestVersion()
    {
        $latest = $this->getLatest();

        return $latest->version;
    }

    public function newestVersion()
    {
        return version_compare($this->getLatestVersion(), $this->packageVersion) > 0;
    }

    public function getChangedFiles()
    {
        $list = [];
        $json = $this->getJsonUpdate();

        if (!isset($json->files)) {
            return $list;
        }

        foreach ($json->files as $type => $files) {
            if ($type == 'U') {
                foreach ($files as $file) {
                    if (!$this->getBaseName($file->path)) {
                        $list[] = $file->path;
                    } elseif (@md5_file($this->getBaseName($file->path)) != $file->checksum->before) {
                        $list[] = $file->path;
                    }
                }
            }
        }

        return $list;
    }

    protected function getBaseName($path)
    {
        $namespace = str_replace('\\', '/', $this->packageNamespace);
        $path = $namespace.'/'.$path;

        switch ($this->packageType) {
            case 'module':
                return base_path('modules/'.$path);
                break;

            case 'widget':
                return base_path('widgets/'.$path);
                break;

            case 'theme':
                return base_path('themes/'.$path);
                break;

            default:
                return base_path($path);
                break;
        }
    }

    /**
     * Start Updating.
     *
     * @param bool $force      Force updating
     * @param bool $is_console running from console or not
     *
     * @return void
     */
    public function update($force = false, $is_console = false)
    {
        if (!$this->newestVersion()) {
            $this->error('You are trying to install an old version v.'.$this->getLatestVersion().' <= (installed) v.'.$this->packageVersion);

            return false;
        }

        $this->is_console = $is_console;

        try {
            if ($this->isLatest($force)) {
                return;
            }

            $this->progress('progress', trans('backend.update.events.checking_conformity'), 1);

            $listEditedFiles = $this->getChangedFiles();
            if (!empty($listEditedFiles) && !$force) {
                return $listEditedFiles;
            }

            $json = $this->getJsonUpdate();
            $updateName = 'update_v'.$json->upgrade->to;
            $this->storageUpdateFolder = storage_path('app/'.$updateName);
            $zipFile = storage_path('app/'.$updateName.'.zip');

            $this->progress('progress', trans('backend.update.events.clearing_old_files'), 10);
            $this->clearAll($zipFile);

            $this->progress('progress', trans('backend.update.events.creating_storage_update_folder'), 15);

            //create folder storage/app/update_v{version}
            File::makeDirectory($this->storageUpdateFolder);

            $this->progress('progress', trans('backend.update.events.downlading_update_file'), 20);

            //download archive file
            File::put($zipFile, file_get_contents($json->archive.$this->getApiQueries()));

            $this->progress('progress', trans('backend.update.events.extracting_update_files'), 40);

            //unzip archive file into storage/app/update_v{version}
            Zipper::make($zipFile)->extractTo($this->storageUpdateFolder);

            $bootstrap = $this->getBootstrap();

            $this->progress('progress', trans('backend.update.events.starting_before_trigger'), 45);

            //execute __bootstrap.before
            if ($bootstrap && isset($bootstrap['before'])) {
                $bootstrap['before']($this->is_console);
            }

            $this->progress('progress', trans('backend.update.events.updating_component'), 50);

            $count = count($json->files->A) + count($json->files->U) + count($json->files->D);

            $completedFiles = 0;
            //copy files, and delete others
            foreach ($json->files as $type => $files) {
                foreach ($files as $file) {
                    $completedFiles++;
                    $progressNbr = round(50 + (($completedFiles * 40) / $count));

                    if ($type == 'A') {
                        $this->progress('progress', trans('backend.update.events.creating_file', ['path' => $file->path]), $progressNbr);
                        $this->updateFile($file);
                    } elseif ($type == 'U') {
                        $this->progress('progress', trans('backend.update.events.updating_file', ['path' => $file->path]), $progressNbr);
                        $this->updateFile($file);
                    } else {
                        $this->progress('progress', trans('backend.update.events.deleting_file', ['path' => $file->path]), $progressNbr);
                        @unlink($this->getBaseName($file->path));
                    }
                }
            }

            $this->progress('progress', trans('backend.update.events.starting_after_trigger'), 90);
            //execute __bootstrap.after
            if ($bootstrap && isset($bootstrap['after'])) {
                $bootstrap['after']($this->is_console);
            }

            $this->progress('progress', trans('backend.update.events.clearing_update_files'), 99);

            $this->clearAll($zipFile);
            $this->progress('complete', trans('backend.update.events.updated'), 100);
        } catch (Exception $e) {
            $this->progress('error', $e->getMessage());
        }
    }

    public function getLatest()
    {
        $namespace = $this->getPackageNamespaceForApi();

        if (isset($this->latestJson[$namespace])) {
            return $this->latestJson[$namespace];
        }

        return $this->latestJson[$namespace] = json_decode(file_get_contents($this->getPackageDownloadUrl()));
    }

    protected function getPackageDownloadUrl()
    {
        return Core::API.'/'.$this->getPackageNamespaceForApi().$this->getApiQueries();
    }

    protected function getPackageUpdateUrl()
    {
        return Core::API.'/update/'.$this->getPackageNamespaceForApi().$this->packageVersion.$this->getApiQueries();
    }

    protected function getPackageNamespaceForApi()
    {
        return $this->packageType != 'core' ? strtolower($this->packageType.'/'.str_replace('\\', '/', $this->packageNamespace)).'/' : '';
    }

    public function getJsonUpdate()
    {
        $namespace = $this->getPackageNamespaceForApi();

        if (isset($this->updateJson[$namespace])) {
            return $this->updateJson[$namespace];
        }

        $this->updateJson[$namespace] = json_decode(file_get_contents($this->getPackageUpdateUrl()));

        if ($this->updateJson[$namespace] === null) {
            throw new Exception('Whoops, looks like something went wrong with the API.');
        }

        return $this->updateJson[$namespace];
    }

    protected function clearAll($zipFile)
    {
        // delete storage/app/update_v{version}
        File::deleteDirectory($this->storageUpdateFolder);
        // delete storage/app/update_v{version}.zip
        File::delete($zipFile);
    }

    protected function getBootstrap()
    {
        $bootstrapFile = $this->storageUpdateFolder.'/__bootstrap.php';
        $bootstrap = false;
        if (File::exists($bootstrapFile)) {
            $bootstrap = require_once $bootstrapFile;
        }

        return $bootstrap;
    }

    protected function updateFile($file)
    {
        // create directory
        if (dirname($file->path) != '.' && !File::exists($this->getBaseName(dirname($file->path)))) {
            File::makeDirectory($this->getBaseName(dirname($file->path)), 0775, true);
        }
        //copy file content
        $src = $this->storageUpdateFolder.'/'.$file->path;
        $dest = $this->getBaseName($file->path);
        File::copy($src, $dest);
    }

    protected function sendEventMessage($message, $time)
    {
        if (!$this->initEventMessage) {
            @ob_start();
            echo str_repeat(' ', 2048).PHP_EOL;
            $this->initEventMessage = true;
        }

        $data = ['message' => $message, 'progress' => $this->eventProgress, 'time' => $time];

        echo 'event: '.$this->eventId.PHP_EOL;
        echo 'data: '.json_encode($data).PHP_EOL;
        echo PHP_EOL;

        @ob_flush();
        @flush();
    }

    protected function progress($id, $message, $progress = null)
    {
        $this->eventId = $id;
        $this->eventProgress = $progress ?: $this->eventProgress;
        $this->log($message);
    }

    /**
     * Get Api Queries.
     *
     * @return string
     */
    protected function getApiQueries()
    {
        return '?url='.base64_encode(url('/'));
    }

    /*
     * Loggin
     */

    public function log()
    {
        $args = func_get_args();
        $message = array_shift($args);

        if (is_array($message)) {
            $message = implode(PHP_EOL, $message);
        }

        $message = vsprintf($message, $args);
        $time = date('Y/m/d h:i:s', time());
        if (!$this->is_console) {
            $this->sendEventMessage($message, $time);
        }
    }
}
