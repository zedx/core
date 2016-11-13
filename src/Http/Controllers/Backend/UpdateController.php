<?php

namespace ZEDx\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Updater;
use ZEDx\Core;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\UpdaterRequest;

class UpdateController extends Controller
{
    /**
     * Show list of components to update.
     *
     * @param Request $request
     * @param string  $type
     *
     * @return Reponse
     */
    public function index(Request $request, $type = 'core')
    {
        $updatesList = Updater::getUpdatesList();

        $data = compact('updatesList', 'type');

        if ($type == 'core') {
            return view_backend('update.core.index', $data);
        }

        if (in_array($type, ['module', 'widget', 'theme'])) {
            return view_backend('update.component.index', $data);
        }

        return redirect()->route('zxadmin.dashboard.index');
    }

    /**
     * Show update files for a specific package.
     *
     * @param UpdaterRequest $request
     * @param string         $type
     *
     * @return Response
     */
    public function show(UpdaterRequest $request, $type = 'core')
    {
        $force = $request->has('force') && $request->force == 'true';

        $this->setPackage($request, $type);

        $changedFiles = Updater::getChangedFiles();
        $version = Updater::getPackageVersion();

        return view_backend('update.component.show', compact('type', 'force', 'version', 'changedFiles'));
    }

    /**
     * Start updating package.
     *
     * @param UpdaterRequest $request
     * @param string         $type
     *
     * @return Reponse
     */
    public function update(UpdaterRequest $request, $type = 'core')
    {
        $this->setPackage($request, $type);

        $response = new StreamedResponse(function () use ($request) {
            $force = $request->has('force') && $request->force == 'true';

            Updater::update($force);
        });

        $response->headers->set('Content-Type', 'text/event-stream');

        return $response;
    }

    /**
     * Set package details.
     *
     * @param UpdaterRequest $request
     * @param string         $type
     */
    protected function setPackage(UpdaterRequest $request, $type)
    {
        $packagesVersions = Updater::getPackagesVersions();

        $namespace = $request->namespace;

        $version = array_get($packagesVersions, $type.'.'.$namespace, false);

        if ($version === false) {
            abort(404);
        }

        Updater::setPackageType($type);
        Updater::setPackageNamespace($namespace);
        Updater::setPackageVersion($version);
    }
}
