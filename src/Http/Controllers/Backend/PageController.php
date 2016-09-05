<?php

namespace ZEDx\Http\Controllers\Backend;

use Illuminate\Http\Request;
use SoapBox\Formatter\Formatter;
use ZEDx\Events\Page\PageTemplateWasSwitched;
use ZEDx\Events\Page\PageThemepartialWasAttached;
use ZEDx\Events\Page\PageThemepartialWasDetached;
use ZEDx\Events\Page\PageWasCreated;
use ZEDx\Events\Page\PageWasUpdated;
use ZEDx\Events\Page\PageWillBeCreated;
use ZEDx\Events\Page\PageWillBeUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\CreatePageRequest;
use ZEDx\Http\Requests\SwitchTemplateRequest;
use ZEDx\Http\Requests\UpdatePageRequest;
use ZEDx\Models\Page;
use ZEDx\Models\Tag;
use ZEDx\Models\Template;
use ZEDx\Models\Themepartial;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pages = Page::whereType('page')->paginate(15);
        $type = 'page';
        
        $pages = Page::search(\Request::get('q'))->paginate(15);
        
        return view_backend('page.index', compact('pages', 'type'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function core()
    {
        $pages = Page::where('type', '<>', 'page')->paginate(15);
        $type = 'core';

        return view_backend('page.index', compact('pages', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view_backend('page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreatePageRequest $request)
    {
        $template = Template::findOrFail($request->get('template_id'));

        $page = new Page();
        $page->fill($request->all());
        $page->template_id = $template->id;

        event(
            new PageWillBeCreated($page)
        );

        $page->save();

        $this->attachDefaultPartials($page, $template);
        $this->attachTags($page, $request);
        $blockIdentifier = $template->blocks()->firstOrFail()->identifier;

        event(
            new PageWasCreated($page)
        );

        return redirect()->route('zxadmin.page.edit', [$page->id, $blockIdentifier]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Page $page, $templateblock)
    {
        $selectedThemePartials = $page->themepartials->lists('id')->toArray();

        return view_backend('page.edit', compact('page', 'templateblock', 'selectedThemePartials'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Page $page, UpdatePageRequest $request)
    {
        $page->fill($request->all());

        event(
            new PageWillBeUpdated($page)
        );

        $page->save();
        $this->attachTags($page, $request);

        event(
            new PageWasUpdated($page)
        );

        return redirect()->back()->with('pageSettingEdited', true);
    }

    /**
     * Define a page as a homepage.
     *
     * @param Page $page
     *
     * @return Reponse
     */
    public function beHomepage(Page $page)
    {
        if (\Request::ajax()) {
            $page->beHomepage();
        } else {
            abort(404);
        }
    }

    /**
     * Attach a theme partial to a page.
     *
     * @param Page         $page
     * @param Themepartial $themepartial
     *
     * @return void
     */
    public function attachThemePartial(Page $page, Themepartial $themepartial)
    {
        $page->themepartials()->attach($themepartial);

        event(
            new PageThemepartialWasAttached($page, $themepartial)
        );
    }

    /**
     * Attach a theme partial from a page.
     *
     * @param Page         $page
     * @param Themepartial $themepartial
     *
     * @return void
     */
    public function detachThemePartial(Page $page, Themepartial $themepartial)
    {
        $page->themepartials()->detach($themepartial);

        event(
            new PageThemepartialWasDetached($page, $themepartial)
        );
    }

    /**
     * Set new template to a page.
     *
     * @param Page                  $page
     * @param SwitchTemplateRequest $request
     *
     * @return array
     */
    public function switchTemplate(Page $page, SwitchTemplateRequest $request)
    {
        $connectedBlocks = $this->blocksAreConnected($page, $request->get('connected_blocks'));
        $template = Template::findOrFail($request->get('template_id'));

        $page->template()->associate($template)->save();
        $this->updateTemplateBlocks($page, $request->get('connected_blocks'));

        event(
            new PageTemplateWasSwitched($page)
        );

        $url = route('zxadmin.page.edit', [$page->id, $page->template->blocks()->firstOrFail()->identifier]);

        return ['connectedBlocks' => $connectedBlocks, 'template' => $template, 'url' => $url];
    }

    /**
     * Update template blocks.
     *
     * @param Page  $page
     * @param array $connectedBlocks
     *
     * @return void
     */
    protected function updateTemplateBlocks(Page $page, $connectedBlocks)
    {
        $connectedBlocks = $this->formatConnectedBlock($connectedBlocks);
        foreach ($page->nodes as $node) {
            if (false !== $key = array_search($node->block->identifier, $connectedBlocks['from'])) {
                $newTemplateBlockIdentifier = $connectedBlocks['to'][$key];
                $newTemplateBlock = $page->template->blocks()->whereIdentifier($newTemplateBlockIdentifier)->first();
                if ($newTemplateBlock) {
                    $node->block()->associate($newTemplateBlock)->save();
                }
            }
        }
    }

    /**
     * Define if blocks are connected to a page.
     *
     * @param Page  $page
     * @param array $connectedBlocks
     *
     * @return bool
     */
    protected function blocksAreConnected(Page $page, $connectedBlocks)
    {
        $connectedBlocks = $this->formatConnectedBlock($connectedBlocks);

        foreach ($page->nodes as $node) {
            if (!in_array($node->block->identifier, $connectedBlocks['from'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Format connected block.
     *
     * @param array $connectedBlocks
     *
     * @return array
     */
    protected function formatConnectedBlock($connectedBlocks)
    {
        $connectedBlocks = json_decode($connectedBlocks);
        $blocks = ['from' => [], 'to' => []];
        foreach ($connectedBlocks as $connectedBlock) {
            $blocks['from'][] = $connectedBlock->from;
            $blocks['to'][] = $connectedBlock->to;
        }

        return $blocks;
    }

    /**
     * Attach default theme partials.
     *
     * @param Page     $page
     * @param Template $template
     *
     * @return void
     */
    protected function attachDefaultPartials(Page $page, Template $template)
    {
        $skeleton = Formatter::make($template->skeleton, Formatter::JSON);
        $xmlTemplate = @simplexml_load_string($skeleton->toXml(), 'SimpleXMLElement', LIBXML_NOWARNING);
        if ($xmlTemplate) {
            $partialsList = (string) $xmlTemplate->attributes->partials;
            if ($partialsList) {
                $partials = explode(',', $partialsList);
                foreach ($partials as $partial) {
                    $partialModel = Themepartial::whereName($partial)->first();
                    if ($partialModel) {
                        $page->themepartials()->attach($partialModel);
                    }
                }
            }
        }
    }

    /**
     * Attach tags to a page.
     *
     * @param Page    $page
     * @param Request $request
     *
     * @return void
     */
    protected function attachTags(Page $page, Request $request)
    {
        $tags = [];
        $tagNames = explode(',', $request->get('tags'));
        array_walk($tagNames, function (&$value) {
            $value = trim($value);
        });
        $tagNames = array_filter($tagNames);
        foreach ($tagNames as $tag) {
            $tags[] = Tag::firstOrCreate(['name' => $tag])->id;
        }
        $page->tags()->sync($tags);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Page $page
     *
     * @return Response
     */
    public function destroy(Page $page)
    {
        if ($page->type != 'page') {
            return abort();
        }

        $page->delete();
    }
}
