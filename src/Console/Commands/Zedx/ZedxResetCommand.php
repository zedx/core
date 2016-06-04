<?php

namespace ZEDx\Console\Commands\Zedx;

use Illuminate\Console\Command;

class ZedxResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zedx:reset
                            {--force : Force the operation to run}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset ZEDx installation.';

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
        if (!$this->option('force')) {
            $warning = 'Reset ZEDx database';
            $this->comment(str_repeat('*', strlen($warning) + 12));
            $this->comment('*     '.$warning.'     *');
            $this->comment(str_repeat('*', strlen($warning) + 12));
            $this->output->writeln('');
            if (!$this->confirm('Are you sure ?')) {
                return;
            }
        }

        $this->table([], [['Refreshing database']]);
        $this->output->writeln('');
        $this->call('migrate:refresh', ['--force' => true, '--seed' => true]);
        $this->output->writeln('');

        $this->table([], [['Publishing Frontend Assets']]);
        $this->output->writeln('');
        $this->call('theme:publish', ['--force' => true]);
        $this->output->writeln('');

        $this->table([], [['Publishing Backend Assets']]);
        $this->output->writeln('');
        $this->call('backend:publish', ['--force' => true]);

        $this->table([], [['Publishing Widgets Assets']]);
        $this->output->writeln('');
        $this->call('widget:publish', ['--force' => true]);

        $this->sayThankYou();
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
            ['Administrator Email', '<info>admin@example.com</info>'],
            ['Administrator Password', '<info>password</info>'],
        ];

        $this->table([], $data);
    }
}
