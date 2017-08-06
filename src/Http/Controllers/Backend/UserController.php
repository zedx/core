<?php

namespace ZEDx\Http\Controllers\Backend;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Request;
use ZEDx\Events\User\UserWasCreated;
use ZEDx\Events\User\UserWasDeleted;
use ZEDx\Events\User\UserWasUpdated;
use ZEDx\Events\User\UserWillBeCreated;
use ZEDx\Events\User\UserWillBeDeleted;
use ZEDx\Events\User\UserWillBeUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\CreateUserRequest;
use ZEDx\Http\Requests\UpdateUserRequest;
use ZEDx\Models\Role;
use ZEDx\Models\Subscription;
use ZEDx\Models\User;

class UserController extends Controller
{
    /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
      $users = User::search(Request::get('q'))->paginate(10);
      if (Request::ajax()) {
          return $users;
      }

      return view_backend('user.index', compact('users'));
  }

  /**
   * Display a user.
   *
   * @return Response
   */
  public function show(User $user)
  {
      if (Request::ajax()) {
          return $user;
      }
      abort(404);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      $subscriptions = Subscription::has('adtypes')->get();

      return view_backend('user.create', compact('subscriptions'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(CreateUserRequest $request)
  {
      $admin = Auth::guard('admin')->user();

      $subscription = Subscription::findOrFail($request->get('subscription_id'));
      $role = Role::whereName('user')->firstOrFail();

      $inputs = $request->all();

      if (!isset($inputs['phone']) || empty($inputs['phone'])) {
          $inputs['is_phone'] = false;
      }

      $user = new User();
      $user->fill($inputs);
      $user->subscription_id = $subscription->id;
      $user->role_id = $role->id;
      $user->subscribed_at = $request->get('subscribed_at');
      $user->subscription_expired_at = $subscription->days >= 9999 ? null : Carbon::createFromFormat('d/m/Y', $user->subscribed_at)->addDays($subscription->days + 1);
      $adtypes = $request->get('adtypes', []);
      event(new UserWillBeCreated($user, $admin, $adtypes));
      $user->save();
      $user->adtypes()->sync($adtypes);
      event(new UserWasCreated($user, $admin));

      return redirect()->route('zxadmin.user.edit', $user->id);
  }

    protected function saveUserAdtype(User $user, $request)
    {
        if ($request->has('adtypes')) {
            $adtypes = $request->get('adtypes');
            $user->adtypes()->sync($adtypes);
        }
    }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   *
   * @return Response
   */
  public function edit(User $user)
  {
      $subscriptions = Subscription::has('adtypes')->get();

      return view_backend('user.edit', compact('user', 'subscriptions'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param int $id
   *
   * @return Response
   */
  public function update(User $user, UpdateUserRequest $request)
  {
      $admin = Auth::guard('admin')->user();

      $inputs = $request->all();

      $subscription_id = isset($inputs['subscription_id']) ? $inputs['subscription_id'] : null;
      $subscription = Subscription::findOrFail($subscription_id);
      $adtypes = isset($inputs['adtypes']) ? $inputs['adtypes'] : [];

      if (empty($inputs['password'])) {
          array_forget($inputs, ['password', 'password_confirm']);
      }

      if (!isset($inputs['phone']) || empty($inputs['phone'])) {
          $inputs['is_phone'] = false;
      }

      $user->fill($inputs);
      $user->subscription()->associate($subscription)->save();
      $user->adtypes()->sync($adtypes);
      $user->subscribed_at = isset($inputs['subscribed_at']) ? $inputs['subscribed_at'] : Carbon::now()->format('d/m/Y');
      $user->subscription_expired_at = $subscription->days >= 9999 ? null : Carbon::createFromFormat('d/m/Y', $user->subscribed_at)->addDays($subscription->days + 1);
      $user->is_validate = 1;

      event(new UserWillBeUpdated($user, $admin));
      $user->save();
      event(new UserWasUpdated($user, $admin));

      return redirect()->route('zxadmin.user.edit', $user->id);
  }

  /**
   * Remove a Collection of Users.
   *
   * @param Collection $users
   *
   * @return Response
   */
  public function destroyUsersCollection(Collection $users)
  {
      foreach ($users as $user) {
          $this->destroy($user);
      }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param User $user
   *
   * @return Response
   */
  protected function destroy(User $user)
  {
      $admin = Auth::guard('admin')->user();
      event(new UserWillBeDeleted($user, $admin));
      $user->delete();
      event(new UserWasDeleted($user, $admin));
  }
}
