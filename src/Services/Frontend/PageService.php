<?php

namespace ZEDx\Services\Frontend;

use ZEDx\Models\Page;

class PageService
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($shortcut = '/', $integrateCorePages = false)
    {
        if ($shortcut != '/') {
            $page = Page::shortcut($shortcut, $integrateCorePages)->firstOrFail();
        } else {
            $page = Page::home()->firstOrFail();
        }

        $__zedx_template_blocks = $this->blocks($page);
        $__themePartials = $page->themepartials->lists('name')->toArray();

        return [
            'templateFile' => $page->template->file,
            'data'         => compact('page', '__themePartials', '__zedx_template_blocks'),
        ];
    }

    private function blocks($page)
    {
        $blocks = [];
        foreach ($page->template->blocks as $block) {
            $blockIdentifier = $block->identifier;

            $blocks[$blockIdentifier] = [];

            $nodes = $page->nodes()->whereTemplateblockId($block->id)
                ->whereIsEnabled(true)
                ->sorted()->get();

            foreach ($nodes as $node) {
                if (($data = $node->config) === null) {
                    $data = [];
                }

                $conf = [
                    '_namespace' => $node->namespace,
                    '_config'    => $data,
                    '_node'      => $node->id,
                ];

                $blocks[$blockIdentifier][$node->id] = $conf;
            }
        }

        return $blocks;
    }
}
