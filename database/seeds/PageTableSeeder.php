<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Page;
use ZEDx\Models\Template;
use ZEDx\Models\Themepartial;
use ZEDx\Models\Widgetnode;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $homepage = $this->create('Accueil', 'home', "Description de la page d'accueil", 1);
        $homepage->themepartials()->attach(Themepartial::all());
        $this->attachMapWidget($homepage);
    }

    protected function attachMapWidget($page)
    {
        $contentBlock = $page->template->blocks()->first();
        $node = new Widgetnode();
        $node->page_id = $page->id;
        $node->namespace = 'Frontend\ZEDx\Maps';
        $node->title = 'Maps';
        $node->config = '[]';
        $node->is_enabled = true;
        $contentBlock->nodes()->save($node);
    }

    protected function create($name, $shortcut, $description, $is_home)
    {
        return Page::create([
        'name'        => $name,
        'shortcut'    => $shortcut,
        'description' => $description,
        'is_home'     => $is_home,
        'template_id' => Template::first()->id,
      ]);
    }
}
