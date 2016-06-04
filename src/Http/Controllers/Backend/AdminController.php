<?php

namespace ZEDx\Http\Controllers\Backend;

use Auth;
use Hash;
use ZEDx\Events\Admin\AdminWasUpdated;
use ZEDx\Events\Admin\AdminWillBeUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\UpdateAdminRequest;

class AdminController extends Controller
{
    protected $admin;

    public function __construct()
    {
        $this->admin = Auth::guard('admin')->user();
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
        $admin = $this->admin;

        return view_backend('profile', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdminRequest $request
     *
     * @return bool
     */
    public function update(UpdateAdminRequest $request)
    {
        $inputs = $request->all();
        $admin = $this->admin;

        if (!Hash::check($inputs['my_password'], $admin->password)) {
            return back()->withInput()->withErrors(['my_password' => trans('backend.profile.incorrect_password')]);
        }

        if (empty($inputs['password'])) {
            array_forget($inputs, ['password', 'password_confirm']);
        }

        $admin->fill($inputs);
        event(new AdminWillBeUpdated($admin, $admin));
        $admin->save();
        event(new AdminWasUpdated($admin, $admin));

        return back();
    }
}
