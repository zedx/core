<?php

namespace ZEDx\Console\Commands\Theme;

use Illuminate\Console\Command;
use Themes;

class ThemeSetCommand extends Command
{
    /**
     * Theme name.
     */
    protected $name;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:set
                            {name : Theme name}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set frontend theme.';

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
        $this->name = studly_case($this->argument('name'));

        if (!Themes::exists($this->name)) {
            $this->error("Theme [{$this->name}] doesn't exist!]");

            return;
        }

        $this->info("[ + ] Switching to {$this->name} theme.");
        Themes::frontend()->setActive($this->name);
    }
}
