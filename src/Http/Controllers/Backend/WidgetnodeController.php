<?php

namespace ZEDx\Http\Controllers\Backend;

use Illuminate\Http\Request;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\WidgetnodeRequest;
use ZEDx\Models\Page;
use ZEDx\Models\Widgetnode;

class WidgetnodeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Page $page, $templateblock, WidgetnodeRequest $request)
    {
        $data = $request->all();
        $data['templateblock_id'] = $templateblock->id;
        $page->nodes()->create($data);

        return redirect()->route('zxadmin.page.edit', [$page->id, $templateblock->identifier]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Page          $page
     * @param Templateblock $templateblock
     * @param Widgetnode    $widgetnode
     *
     * @return Response
     */
    public function edit(Page $page, $templateblock, Widgetnode $widgetnode)
    {
        $selectedThemePartials = $page->themepartials->lists('id')->toArray();

        return view_backend('page.edit', compact('page', 'templateblock', 'widgetnode', 'selectedThemePartials'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Page $page, $templateblock, Widgetnode $widgetnode, Request $request)
    {
        if ($request->has('config')) {
            $widgetnode->config = json_encode($request->get('config'));
            $widgetnode->save();
        }

        if ($request->ajax() && $request->has('title')) {
            $widgetnode->title = $request->get('title');
            $widgetnode->save();
        }

        if (!$request->ajax()) {
            return back();
        }
    }

    /**
     * Enable/Disable a widget.
     *
     * @param Page          $page
     * @param Templateblock $templateblock
     * @param Widgetnode    $widgetnode
     * @param Request       $request
     *
     * @return mixed
     */
    public function swap(Page $page, $templateblock, Widgetnode $widgetnode, Request $request)
    {
        $widgetnode->is_enabled = !$widgetnode->is_enabled;
        $widgetnode->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Page $page, $templateblock, Widgetnode $widgetnode)
    {
        $widgetnode->delete();

        return redirect()->route('zxadmin.page.edit', [$page->id, $templateblock->identifier]);
    }
}
