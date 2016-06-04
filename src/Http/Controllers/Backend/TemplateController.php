<?php

namespace ZEDx\Http\Controllers\Backend;

use Illuminate\Support\Collection;
use SoapBox\Formatter\Formatter;
use TemplateSkeleton;
use Themes;
use ZEDx\Events\Template\TemplateWasUpdated;
use ZEDx\Events\Template\TemplateWillBeUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\TemplateRequest;
use ZEDx\Models\Template;
use ZEDx\Utils\TemplateHelper;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = Template::paginate(10);

        return view_backend('template.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view_backend('template.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TemplateRequest $request)
    {
        $formatter = Formatter::make($request->get('skeleton'), Formatter::JSON);
        $templateSkeleton = json_decode($formatter->toJson(), true);
        $themeName = Themes::frontend()->getSlug();
        $template = TemplateHelper::saveNewTemplate($themeName, $templateSkeleton);

        return redirect()->route('zxadmin.template.edit', $template->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Template $template
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        return view_backend('template.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TemplateRequest $request
     * @param Template        $template
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TemplateRequest $request, Template $template)
    {
        $template->skeleton = $request->get('skeleton');
        $template->title = $request->get('title');

        event(
            new TemplateWillBeUpdated($template)
        );

        $template->save();
        TemplateHelper::saveTemplateBlocks($template);
        TemplateSkeleton::generateTemplateFile($template);

        event(
            new TemplateWasUpdated($template)
        );

        return redirect()->route('zxadmin.template.edit', $template->id);
    }

    /**
     * Remove a Collection of Templates.
     *
     * @param Collection $templates
     *
     * @return Response
     */
    public function destroyTemplatesCollection(Collection $templates)
    {
        foreach ($templates as $template) {
            $this->destroy($template);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Template $template
     *
     * @return Response
     */
    protected function destroy(Template $template)
    {
        $template->delete();
    }
}
