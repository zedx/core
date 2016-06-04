<?php

namespace ZEDx\Utils;

use Exception;
use File;
use ZEDx\Core;
use Zipper;

class Updater
{
    protected $is_console;
    protected $latestJson;
    protected $updateJson;
    protected $eventId;
    protected $eventProgress;
    protected $logData = [];
    protected $storageUpdateFolder;

    public function __construct()
    {
        $this->logData = [];
    }

    public function isLatest()
    {
        return Core::VERSION == $this->getLatestVersion();
    }

    public function getLatestVersion()
    {
        $latest = $this->getLatest();

        return $latest->version;
    }

    public function newestVersion()
    {
        return (version_compare($this->getLatestVersion(), Core::VERSION) > 0);
    }

    public function getChangedFiles()
    {
        $list = [];
        //check that checksum are ok
        $json = $this->getJsonUpdate();

        foreach ($json->files as $type => $files) {
            if ($type == 'U') {
                foreach ($files as $file) {
                    if (! base_path($file->path)) {
                        $list[] = $file->path;
                    } elseif (@md5_file(base_path($file->path)) != $file->checksum->before) {
                        $list[] = $file->path;
                    }
                }
            }
        }

        return $list;
    }

    /**
     * Start Updating.
     *
     * @param  bool $force           Force updating
     * @param  bool $is_console      running from console or not
     *
     * @return void
     */
    public function update($force = false, $is_console = false)
    {
        if (! $this->newestVersion()) {
            $this->error('You are trying to install an old version v.'.$this->getLatestVersion().' <= (installed) v.'.Core::VERSION);

            return false;
        }

        $this->is_console = $is_console;

        try {
            if ($this->isLatest()) {
                return;
            }

            $this->progress(1, trans('backend.update.events.checking_conformity'), 1);

            $listEditedFiles = $this->getChangedFiles();
            if (! empty($listEditedFiles) && ! $force) {
                return $listEditedFiles;
            }

            $json = $this->getJsonUpdate();
            $updateName = 'update_v'.$json->upgrade->to;
            $this->storageUpdateFolder = storage_path('app/'.$updateName);
            $zipFile = storage_path('app/'.$updateName.'.zip');

            $this->progress(10, trans('backend.update.events.clearing_old_files'), 10);
            $this->clearAll($zipFile);

            $this->progress(15, trans('backend.update.events.creating_storage_update_folder'), 15);

            //create folder storage/app/update_v{version}
            File::makeDirectory($this->storageUpdateFolder);

            $this->progress(20, trans('backend.update.events.downlading_update_file'), 20);

            //download archive file
            File::put($zipFile, file_get_contents($json->archive));

            $this->progress(40, trans('backend.update.events.extracting_update_files'), 40);

            //unzip archive file into storage/app/update_v{version}
            Zipper::make($zipFile)->extractTo($this->storageUpdateFolder);

            $bootstrap = $this->getBootstrap();

            $this->progress(45, trans('backend.update.events.starting_before_trigger'), 45);

            //execute __bootstrap.before
            if ($bootstrap && isset($bootstrap['before'])) {
                $bootstrap['before']($this->is_console);
            }

            $this->progress(50, trans('backend.update.events.updating_component'), 50);

            $count = count($json->files->A) + count($json->files->U) + count($json->files->D);

            $completedFiles = 0;
            //copy files, and delete others
            foreach ($json->files as $type => $files) {
                foreach ($files as $file) {
                    $completedFiles++;
                    $progressNbr = round(50 + (($completedFiles * 40) / $count));

                    if ($type == 'A') {
                        $this->progress($progressNbr, trans('backend.update.events.creating_file', ['path' => $file->path]), $progressNbr);
                        $this->updateFile($file);
                    } elseif ($type == 'U') {
                        $this->progress($progressNbr, trans('backend.update.events.updating_file', ['path' => $file->path]), $progressNbr);
                        $this->updateFile($file);
                    } else {
                        $this->progress($progressNbr, trans('backend.update.events.deleting_file', ['path' => $file->path]), $progressNbr);
                        @unlink(base_path($file->path));
                    }
                }
            }

            $this->progress(90, trans('backend.update.events.starting_after_trigger'), 90);
            //execute __bootstrap.after
            if ($bootstrap && isset($bootstrap['after'])) {
                $bootstrap['after']($this->is_console);
            }

            $this->progress(99, trans('backend.update.events.clearing_update_files'), 99);

            $this->clearAll($zipFile);
            $this->progress('COMPLETE', trans('backend.update.events.updated'), 100);
        } catch (Exception $e) {
            $this->progress('ERROR', $e->getMessage());
        }
    }

    public function getLatest()
    {
        if ($this->latestJson) {
            return $this->latestJson;
        }

        $this->latestJson = json_decode(file_get_contents(Core::API));

        return $this->latestJson;
    }

    public function getJsonUpdate()
    {
        if ($this->updateJson) {
            return $this->updateJson;
        }

        $this->updateJson = json_decode(file_get_contents(Core::API.'/update/'.Core::VERSION));

        if ($this->updateJson === null) {
            throw new Exception('Whoops, looks like something went wrong with the API.');
        }

        return $this->updateJson;
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
        if (dirname($file->path) != '.' && ! File::exists(base_path(dirname($file->path)))) {
            File::makeDirectory(base_path(dirname($file->path)), 0775, true);
        }
        //copy file content
        $src = $this->storageUpdateFolder.'/'.$file->path;
        $dest = base_path($file->path);
        File::copy($src, $dest);
    }

    protected function sendEventMessage($message, $time)
    {
        $data = ['message' => $message, 'progress' => $this->eventProgress, 'time' => $time];

        echo 'id: '.$this->eventId.PHP_EOL;
        echo 'data: '.json_encode($data).PHP_EOL;
        echo PHP_EOL;

        ob_flush();
        flush();
    }

    protected function progress($id, $message, $progress = null)
    {
        $this->eventId = $id;
        $this->eventProgress = $progress ?: $this->eventProgress;
        $this->log($message);
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
        if (! $this->is_console) {
            $this->sendEventMessage($message, $time);
        }
    }
}
