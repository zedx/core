<?php

namespace ZEDx\Http\Controllers\Backend;

use Illuminate\Http\Request;
use File;
use Zipper;
use Widgets;
use Artisan;
use ZEDx\Core;
use Exception;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\WidgetUploadRequest;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class WidgetController extends Controller
{
    protected $uploadedWidget;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $widgetsFrontend = Widgets::frontend()->all();
        $widgetsBackend = Widgets::backend()->all();

        return view_backend('widget.index', compact('widgetsFrontend', 'widgetsBackend'));
    }

    /**
     * Show the form for adding a new resource.
     *
     * @return Response
     */
    public function addWithTab($widgetType, $tab)
    {
        $widgets = [];
        switch ($tab) {
            case 'search':
                # code...
                break;
                /*
            case 'upload':
                # code...
                break;
                */
            case 'api':
                $widgets = $this->getPaginatorFromApi($widgetType);
                break;

            default:
                return redirect()->route('zxadmin.widget.addWithTab', 'search');
                break;
        }

        return view_backend('widget.add', compact('widgets', 'tab', 'widgetType'));
    }

    /**
     * Download and install a new widget.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function download($widgetType, $widgetNamespace, $widget, Request $request)
    {
        $url = Core::API.'/widget/'.$widgetType.'/'.$widgetNamespace.'/'.$widget;
        $json = json_decode(file_get_contents($url));
        $archive = file_get_contents(Core::API.'/'.$json->archive);
        $zipPath = storage_path().'/app/'.$widget.'_'.time().'.zip';
        $package = File::put($zipPath,  $archive);

        $widgetManifest = $this->getWidgetManifestFromZip($zipPath);

        $widgetName = $widgetManifest->name;
        $widgetAuthor = $widgetManifest->author;

        $this->extract($zipPath, $widgetType, $widgetAuthor, $widgetName);

        $this->install($widgetType, $widgetAuthor, $widgetName);

        return response()->json(['success'], 200);
    }

    protected function getWidgetManifestFromZip($zipPath)
    {
        $manifest = Zipper::make($zipPath)->getFileContent('zedx.json');
        if (! $manifest) {
            throw new Exception(trans('zedx.json introuvable'));
        }

        return json_decode($manifest);
    }

    /**
     * upload a new widget.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function upload($widgetType, WidgetUploadRequest $request)
    {
        $error = trans('Fichier Zip invalid');

        $uploadedWidget = $request->file('file');

        if (! $uploadedWidget->isValid()) {
            return response()->json(['error' => trans('Fichier Zip invalid')], 400);
        }

        $zipPath = $uploadedWidget->getPathname();
        $fileName = $uploadedWidget->getClientOriginalName();

        $widgetManifest = $this->getWidgetManifestFromZip($zipPath);

        $widgetName = $widgetManifest->name;
        $widgetAuthor = $widgetManifest->author;

        $this->extract($zipPath, $widgetType, $widgetAuthor, $widgetName);

        $this->install($widgetType, $widgetAuthor, $widgetName);

        return response()->json(['success'], 200);
    }

    protected function extract($zipPath, $widgetType, $widgetAuthor, $widgetName, $delete = true)
    {
        $error = false;
        $widgetPath = base_path('widgets').'/'.$widgetType.'/'.$widgetAuthor.'/'.$widgetName;

        umask(0);

        if (File::exists($widgetPath)) {
            throw new Exception(trans('Widget déjà existant'));
        }

        if (! File::makeDirectory($widgetPath, 0775, true)) {
            throw new Exception(trans('Impossible de créer le widget'));
        }

        Zipper::make($zipPath)->extractTo($widgetPath);

        if ($delete) {
            File::delete($zipPath);
        }
    }

    protected function install($widgetType, $widgetAuthor, $widgetName)
    {
        Artisan::call('widget:publish', [
            'type'    => $widgetType,
            'author'  => $widgetAuthor,
            'name'    => $widgetName,
            '--force' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    protected function getPaginatorFromApi($widgetType)
    {
        $query = \Request::query();
        $queryBuild = http_build_query($query);
        $url = Core::API.'/widget?namespace='.$widgetType.'&'.$queryBuild;
        $widgets = json_decode(file_get_contents($url));
        $paginator = new Paginator($widgets->data, $widgets->total, 10, $widgets->current_page, [
            'path'  => \Request::url(),
            'query' => $query,
        ]);

        return $paginator;
    }
}
