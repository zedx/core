<?php

namespace ZEDx\Console\Commands\Zedx;

use Illuminate\Console\Command;
use Updater;
use ZEDx\Core;

class ZedxUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zedx:update
                            {--force : force updating}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ZEDx core.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $force = $this->option('force');

        if (Updater::isLatest()) {
            $this->info('You already have the latest version of ZEDx [ <comment>v'.Core::VERSION.'</comment> ].');

            return;
        }

        if (!$force) {
            if (!$this->confirm('Are you sure to update ZEDx from <comment>v'.Core::VERSION.'</comment> to <comment>v'.Updater::getLatestVersion().'</comment> ?')) {
                return;
            }
        }

        $changedFiles = Updater::getChangedFiles();

        if (!empty($changedFiles)) {
            $data = [];

            foreach ($changedFiles as $file) {
                $data[] = [$file, '<error>FAIL</error>'];
            }

            $this->table(['File', 'Status'], $data);

            if (!$force) {
                return;
            }
        }

        $this->comment('Updating ZEDx from <comment>v'.Core::VERSION.'</comment> to <comment>v'.Updater::getLatestVersion().'</comment>');
        Updater::update($force, true);
    }
}
