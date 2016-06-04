<?php

namespace ZEDx\Http\Controllers\Backend;

use Request;
use File;
use Zipper;
use Modules;
use Exception;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\ModuleUploadRequest;
use ZEDx\Core;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $modules = Modules::all();

        return view_backend('module.index', compact('modules'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function filterByStatus($status)
    {
        switch ($status) {
            case 'enabled' : $modules = Modules::enabled(); break;
            case 'disabled' : $modules = Modules::disabled(); break;
            default :
                return redirect()->route('zxadmin.module.index');
            break;
        }

        return view_backend('module.index', compact('modules', 'status'));
    }

    public function switchStatus($module)
    {
        if (Modules::active($module)) {
            Modules::disable($module);
        } else {
            Modules::enable($module);
        }
    }

    /**
     * Show the form for adding a new resource.
     *
     * @return Response
     */
    public function add()
    {
        return redirect()->route('zxadmin.module.addWithTab', 'search');
    }

    /**
     * Show the form for adding a new resource.
     *
     * @return Response
     */
    public function addWithTab($tab)
    {
        $modules = [];
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
                $modules = $this->getPaginatorFromApi();
                break;

            default:
                return redirect()->route('zxadmin.module.addWithTab', 'search');
                break;
        }

        return view_backend('module.add', compact('modules', 'tab'));
    }

    protected function tabSearch()
    {
    }

    /**
     * Download and install a new module.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function download($module, Request $request)
    {
        $url = Core::API.'/module/'.$module;
        $json = json_decode(file_get_contents($url));
        $archive = file_get_contents(Core::API.'/'.$json->archive);
        $zipPath = storage_path().'/app/'.$module.'_'.time().'.zip';
        $package = File::put($zipPath,  $archive);
        $moduleName = $this->getModuleNameFromZip($zipPath);

        $this->extract($zipPath, $moduleName);

        $this->install($moduleName);

        return response()->json(['success'], 200);
    }

    /**
     * upload a new module.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function upload(ModuleUploadRequest $request)
    {
        $error = trans('Fichier Zip invalid');

        $uploadedModule = $request->file('file');

        if (! $uploadedModule->isValid()) {
            return response()->json(['error' => trans('Fichier Zip invalid')], 400);
        }

        $zipPath = $uploadedModule->getPathname();
        $fileName = $uploadedModule->getClientOriginalName();
        $moduleName = $this->getModuleNameFromZip($zipPath);

        $this->extract($zipPath, $moduleName);

        $this->install($moduleName);

        return response()->json(['success'], 200);
    }

    protected function extract($zipPath, $moduleName, $delete = true)
    {
        $error = false;
        $modulePath = config('modules.paths.modules').DIRECTORY_SEPARATOR.$moduleName;

        umask(0);

        if (Modules::has($moduleName)) {
            throw new Exception(trans('Module déjà existant'));
        }

        if (! File::makeDirectory($modulePath, 0775, true)) {
            throw new Exception(trans('Impossible de créer le module'));
        }

        Zipper::make($zipPath)->extractTo($modulePath);

        if ($delete) {
            File::delete($zipPath);
        }
    }

    protected function getModuleNameFromZip($zipPath)
    {
        $manifest = Zipper::make($zipPath)->getFileContent('zedx.json');
        if (! $manifest) {
            throw new Exception(trans('zedx.json introuvable'));
        }

        $manifest = json_decode($manifest);

        return $manifest->name;
    }

    protected function install($moduleName)
    {
        $className = "\ZEDx\Modules\\".$moduleName."\Module";
        $module = new $className();
        if (! $module->install()) {
            throw new Exception(trans("Impossible d'installer le module"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($moduleName)
    {
        if (! Modules::has($moduleName)) {
            throw new Exception(trans("Module n'existe pas"));
        }

        $className = "\ZEDx\Modules\\".$moduleName."\Module";
        $module = new $className();
        if (! $module->uninstall()) {
            throw new Exception(trans('Impossible de désinstaller le module'));
        }

        $modulePath = config('modules.paths.modules').DIRECTORY_SEPARATOR.$moduleName;

        File::deleteDirectory($modulePath);

        return redirect()->route('zxadmin.module.index');
    }

    protected function getPaginatorFromApi()
    {
        $query = Request::query();
        $queryBuild = http_build_query($query);
        $url = Core::API.'/module?'.$queryBuild;
        $modules = json_decode(file_get_contents($url));
        $paginator = new Paginator($modules->data, $modules->total, 10, $modules->current_page, [
            'path'  => Request::url(),
            'query' => $query,
        ]);

        return $paginator;
    }
}
