<?php

namespace ZEDx\Services\Frontend\User;

use Auth;
use Carbon\Carbon;
use Exception;
use Hash;
use Image;
use Storage;
use ZEDx\Events\User\UserWasCreated;
use ZEDx\Events\User\UserWasUpdated;
use ZEDx\Events\User\UserWillBeCreated;
use ZEDx\Events\User\UserWillBeUpdated;
use ZEDx\Http\Requests\UpdateUserRequest;
use ZEDx\Models\Role;
use ZEDx\Models\Subscription;
use ZEDx\Models\User;

class UserService
{
    /**
     * Auth User.
     *
     * @var \ZEDx\Models\User
     */
    protected $user;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     *
     * @return bool
     */
    public function update($request)
    {
        $inputs = $request->all();

        if ($this->user->is_validate && !Hash::check($inputs['current_password'], $this->user->password)) {
            return false;
        }

        if (!$this->user->is_validate && empty($inputs['password'])) {
            return false;
        }

        if (empty($inputs['password'])) {
            array_forget($inputs, ['password', 'password_confirm']);
        }

        if ($request->hasFile('avatar')) {
            $this->makeAvatarFor($this->user, $request->file('avatar'));
        }

        $this->user->fill($inputs);
        $this->user->is_validate = 1;
        event(new UserWillBeUpdated($this->user, $this->user));
        $this->user->save();
        event(new UserWasUpdated($this->user, $this->user));

        return true;
    }

    /**
     * Make avatar for a specific user.
     *
     * @param User                $user
     * @param string/UploadedFile $avatar
     *
     * @return void
     */
    public function makeAvatarFor(User $user, $avatar)
    {
        $config = config('zedx.images.avatar');
        $size = $config['size'];

        try {
            $img = Image::make($avatar);
            if ($config['resizeCanvas'] && ($img->width() < $size['width'] || $img->height() < $size['height'])) {
                $img->resizeCanvas($size['width'], $size['height'], 'center', false, $config['colorCanvas']);
            } else {
                $img->fit($size['width'], $size['height']);
            }
        } catch (Exception $e) {
            dd($e->getMessage());

            return;
        }

        $img->encode('jpg', 75);
        $filename = 'user-'.$user->id.'.jpg';

        Storage::put($config['path'].'/'.$filename, $img->getEncoded());

        $user->avatar = $filename;
        $user->save();
    }

    /**
     * Store new user.
     *
     * @param array      $data
     * @param Admin/User $actor
     *
     * @return array
     */
    public function store(array $data, $actor, $provider = false)
    {
        $subscription = Subscription::whereIsDefault(1)->firstOrFail();
        $role = Role::whereName('user')->firstOrFail();
        $adtypes = [];

        foreach ($subscription->adtypes as $adtype) {
            $adtypes[$adtype->id] = ['number' => $adtype->pivot->number];
        }

        $user = new User();
        $user->fill($data);
        if ($provider) {
            $user->is_validate = $data['is_validate'];
        }
        $user->subscription_id = $subscription->id;
        $user->role_id = $role->id;
        $user->subscribed_at = Carbon::now()->format('d/m/Y');
        $user->subscription_expired_at = $subscription->days >= 9999 ? null : Carbon::createFromFormat('d/m/Y', $user->subscribed_at)->addDays($subscription->days + 1);

        event(new UserWillBeCreated($user, $actor, $adtypes));

        $user->save();
        $user->adtypes()->sync($adtypes);

        event(new UserWasCreated($user, $actor));

        return [
            'user' => $user,
        ];
    }
}
