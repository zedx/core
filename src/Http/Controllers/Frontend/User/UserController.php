<?php

namespace ZEDx\Http\Controllers\Frontend\User;

use Auth;
use Route;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\UpdateUserRequest;
use ZEDx\Services\Frontend\PageService;
use ZEDx\Services\Frontend\User\UserService;

class UserController extends Controller
{
    /**
     * The page service instance.
     *
     * @var \ZEDx\Services\Frontend\PageService
     */
    protected $pageService;

    /**
     * Auth User.
     *
     * @var \ZEDx\Models\User
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @param PageService $service
     *
     * @return void
     */
    public function __construct(PageService $service)
    {
        $this->pageService = $service;
        $this->user = Auth::user();

        if (!Route::is('user.edit') && $this->user && !$this->user->is_validate) {
            redirect()->route('user.edit')->send();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit()
    {
        $page = (object) $this->pageService->show('user.edit', true);

        return view('__templates::'.$page->templateFile, $page->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update(UpdateUserRequest $request)
    {
        $updated = (new UserService())->update($request);

        if (!$updated) {
            return redirect()->back();
        }

        return redirect()->route('user.edit');
    }
}
