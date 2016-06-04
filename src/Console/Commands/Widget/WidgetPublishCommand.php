<?php

namespace ZEDx\Console\Commands\Widget;

use File;
use Illuminate\Console\Command;
use Widgets;

class WidgetPublishCommand extends Command
{
    /**
     * Widget Author.
     */
    protected $author;

    /**
     * Widget name.
     */
    protected $name;

    /**
     * Widget type.
     */
    protected $type;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'widget:publish
                            {type? : Widget type}
                            {author? : Widget author}
                            {name? : Widget name}
                            {--force : force publishing widget assets}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish widget assets.';

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
        $this->author = $this->argument('author');
        $this->name = $this->argument('name');
        $this->type = $this->argument('type');

        if (!$this->author && !$this->name && !$this->type) {
            $this->publishAll();

            return;
        }

        if (!$this->validWidgetType()) {
            $this->error('Invalid Widget Type, please choose between [Frontend, Backend]');

            return;
        }

        $this->publish();
    }

    protected function publishAll()
    {
        foreach (Widgets::noType()->noFilter()->all() as $widget) {
            $this->call('widget:publish', [
                'author'  => $widget->author,
                'name'    => $widget->name,
                'type'    => $widget->type,
                '--force' => $this->option('force'),
            ]);
        }
    }

    protected function validWidgetType()
    {
        return in_array($this->type, ['Frontend', 'Backend']);
    }

    protected function publish()
    {
        $this->output->writeln('');
        $this->table([], [["Publishing Assets of Widget <info>{$this->type}/{$this->author}/{$this->name}</info>"]]);
        $this->output->writeln('');

        $widgetPath = $this->getWidgetPath();
        $widgetPublicPath = $this->getWidgetPublicPath();

        if (!File::isDirectory($widgetPath)) {
            $this->error("Widget [{$this->type}/{$this->author}/{$this->name}] doesn't exist!");

            return;
        }

        if (!File::isDirectory($widgetPath.'/assets')) {
            $this->info("[ ~ ] Nothing to publish for Widget [{$this->type}/{$this->author}/{$this->name}]");

            return;
        }

        if (File::isDirectory($widgetPublicPath)) {
            if ($this->option('force')) {
                File::deleteDirectory($widgetPublicPath);
            } else {
                $this->error("Assets of Widget [{$this->type}/{$this->author}/{$this->name}] already exist!");

                return;
            }
        }

        $this->info("[ + ] Creating $widgetPublicPath");
        File::makeDirectory($widgetPublicPath, 0755, true);

        $this->info("[ + ] Publishing assets from $widgetPath/assets");
        File::copyDirectory($widgetPath.'/assets', $widgetPublicPath);
        $this->info('Assets published');
    }

    /**
     * Get widget path.
     */
    protected function getWidgetPath()
    {
        return base_path()
        .'/widgets'
        .'/'.$this->type
        .'/'.studly_case($this->author)
        .'/'.studly_case($this->name);
    }

    /**
     * Get widget public assets path.
     */
    protected function getWidgetPublicPath()
    {
        return public_path()
        .'/widgets'
        .'/'.strtolower($this->type)
        .'/'.strtolower($this->author)
        .'/'.strtolower($this->name);
    }
}
