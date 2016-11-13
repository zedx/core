<?php

namespace ZEDx\Services\Frontend\User;

use Auth;
use Request;
use ZEDx\Events\Ad\AdWasCreated;
use ZEDx\Events\Ad\AdWasDeleted;
use ZEDx\Events\Ad\AdWasUpdated;
use ZEDx\Events\Ad\AdWillBeCreated;
use ZEDx\Events\Ad\AdWillBeDeleted;
use ZEDx\Events\Ad\AdWillBeUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\CreateAdUserRequest;
use ZEDx\Http\Requests\UpdateAdUserRequest;
use ZEDx\Models\Ad;
use ZEDx\Models\Adcontent;
use ZEDx\Models\Adstatus;
use ZEDx\Models\Adtype;
use ZEDx\Models\Category;
use ZEDx\Models\Geolocation;
use ZEDx\Models\SelectField;
use ZEDx\Models\User;
use ZEDx\Utils\Geolocation as GeolocationHelper;
use ZEDx\Utils\PhotoManager;

class AdService extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();

        if ($this->user && !$this->user->is_validate) {
            redirect()->route('user.edit')->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $ads = $this->user->ads()
            ->with('content')
            ->recents()
            ->search(Request::get('q'))
            ->paginate(20);

        return [
            'data' => compact('ads'),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function filterByStatus(Adstatus $adstatus)
    {
        $ads = $this->user->ads()->whereAdstatusId($adstatus->id)
            ->with('content')
            ->recents()
            ->search(Request::get('q'))
            ->paginate(20);

        return [
            'data' => compact('ads', 'adstatus'),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Adtype $adtype, CreateAdUserRequest $request)
    {
        if (($number = $this->numberAdtype($adtype)) <= 0 && $adtype->price > 0) {
            return [
                'adId' => null,
            ];
        }

        if ($this->user->subscription_expired_at) {
            if ($this->user->subscription_expired_at->diffInDays(null, false) >= 0) {
                return [
                    'adId' => null,
                ];
            }
        }

        $geo = new GeolocationHelper($request->get('geolocation_data'));
        $adstatus = Adstatus::whereTitle('pending')->first();
        $category = Category::visible()->findOrFail($request->get('category_id'));

        $geolocation = new Geolocation();
        $geolocation->fill($geo->get());

        $content = new Adcontent();
        $content->fill($request->get('content'));

        $ad = new Ad();
        $ad->user()->associate($this->user);
        $ad->adtype()->associate($adtype);
        $ad->adstatus()->associate($adstatus);
        $ad->category()->associate($category);
        $ad->price = $this->getPrice($ad, $request);

        event(
            new AdWillBeCreated($ad, $content, $geolocation, $this->user)
        );

        $ad->save();

        $ad->geolocation()->save($geolocation);
        $ad->content()->save($content);

        if ($ad->adtype->can_add_pic) {
            $this->syncAdPhotos($ad, $request);
        }
        if ($ad->adtype->can_add_video) {
            $this->syncAdVideos($ad, $request);
        }
        $this->syncAdFields($ad, $request);

        if ($number < 9999 && $number > 0) {
            $this->user->adtypes->find($adtype->id)->pivot->decrement('number');
        }

        event(new AdWasCreated($ad, $this->user));

        return [
            'adId' => $ad->id,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Ad $ad)
    {
        $adtype = $ad->adtype;
        $fields = getAdFields($ad);

        return [
            'data' => compact('ad', 'adtype', 'fields'),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Ad $ad, UpdateAdUserRequest $request)
    {
        if (!$ad->adtype->can_edit) {
            return [
                'adId' => null,
            ];
        }

        $oldStatus = $ad->adstatus->title;
        $geo = new GeolocationHelper($request->get('geolocation_data'));
        $adstatus = Adstatus::whereTitle('pending')->first();
        $category = Category::visible()->findOrFail($request->get('category_id'));

        $ad->category()->associate($category);
        $ad->adstatus()->associate($adstatus);

        $ad->geolocation->fill($geo->get());
        $ad->content->fill($request->get('content'));
        $ad->price = $this->getPrice($ad, $request);

        event(
            new AdWillBeUpdated($ad, $this->user)
        );

        $ad->save();

        $ad->geolocation->save();
        $ad->content->save();

        if ($ad->adtype->can_update_pic) {
            $this->syncAdPhotos($ad, $request);
        }

        if ($ad->adtype->can_update_video) {
            $this->syncAdVideos($ad, $request);
        }

        $this->syncAdFields($ad, $request);

        if ($oldStatus != 'pending') {
            event(new AdWasUpdated($ad, $this->user));
        }

        return [
            'adId' => $ad->id,
        ];
    }

    protected function getPrice(Ad $ad, $request)
    {
        $fields = $request->get('fields');

        if (!is_array($fields)) {
            return 0;
        }

        $priceField = $ad->category->fields()->whereIsPrice(true)->get()->first();

        if (!$priceField) {
            return 0;
        }

        if (!isset($fields[$priceField->id])) {
            return 0;
        }

        return $fields[$priceField->id];
    }

    protected function syncAdFields(Ad $ad, $request)
    {
        $ad->fields()->detach();
        $fields = $request->get('fields');

        if (!is_array($fields)) {
            return false;
        }

        $authorizedFields = $ad->category->fields()
            ->lists('type', 'fields.id')->toArray();
        foreach ($fields as $fieldId => $value) {
            $this->syncAdField($ad, $authorizedFields, $fieldId, $value);
        }
    }

    protected function syncAdField($ad, $authorizedFields, $fieldId, $value)
    {
        if ($this->canAttachFieldToAd($authorizedFields, $fieldId, $value)) {
            $values = is_array($value) ? $value : [$value];
            foreach ($values as $value) {
                $pivot = $authorizedFields[$fieldId] == 5 ? ['string' => $value] : ['value' => $value];
                $ad->fields()->attach($fieldId, $pivot);
            }
        }
    }

    protected function canAttachFieldToAd($authorizedFields, $fieldId, $value)
    {
        if (!in_array($fieldId, array_keys($authorizedFields))) {
            return false;
        }

        $type = $authorizedFields[$fieldId];
        switch ($type) {
            case '1':
            case '2':
                return $this->isValidFieldSelectOption($fieldId, $value);
                break;
            case '3':
                return $this->isValidFieldSelectOptions($fieldId, $value);
                break;

            case '4':
                return is_numeric($value);
                break;

            case '5':
                return true;
                break;

            default:
                return false;
                break;
        }
    }

    protected function isValidFieldSelectOption($fieldId, $optionId)
    {
        $option = SelectField::find($optionId);
        if ($option === null) {
            return false;
        }

        return $option->field_id == $fieldId;
    }

    protected function isValidFieldSelectOptions($fieldId, $value)
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $option) {
            if (!$this->isValidFieldSelectOption($fieldId, $option)) {
                return false;
            }
        }

        return true;
    }

    protected function syncAdPhotos(Ad $ad, $request)
    {
        $ad->photos()->delete();
        $number_pic = 0;
        if ($request->has('oldPhotos')) {
            $number_pic = $this->syncOldAdPhotos($ad, $request);
        }

        if ($request->hasFile('photos')) {
            $this->syncNewAdPhotos($ad, $request, $number_pic);
        }
    }

    protected function syncOldAdPhotos(Ad $ad, $request)
    {
        $max = $ad->adtype->nbr_pic;
        $photos = $request->get('oldPhotos');
        if (is_array($photos)) {
            $i = 0;
            foreach ($photos as $photo) {
                if ($max > $i) {
                    $photo['is_main'] = $i == 0 ? '1' : '0';
                    $ad->photos()->create($photo);
                    $i++;
                }
            }
        }

        return $i;
    }

    protected function syncNewAdPhotos(Ad $ad, $request, $number_pic)
    {
        $max = $ad->adtype->nbr_pic - $number_pic;
        $photoManager = new PhotoManager();
        $photos = $photoManager->save($request->file('photos'));
        $i = 0;
        foreach ($photos as $photo) {
            if ($max > $i) {
                $photo['is_main'] = $number_pic == 0 && $i == 0 ? '1' : '0';
                $ad->photos()->create($photo);
                $i++;
            }
        }
    }

    protected function syncAdVideos(Ad $ad, $request)
    {
        $max = $ad->adtype->nbr_video;
        $ad->videos()->delete();
        $i = 0;
        $videos = $request->get('videos');
        if (is_array($videos)) {
            foreach ($videos as $video) {
                if ($max > $i) {
                    $ad->videos()->create($video);
                    $i++;
                }
            }
        }
    }

    protected function numberAdtype(Adtype $adtype)
    {
        return $this->user
            ->adtypes
            ->find($adtype->id)
            ->pivot
            ->number;
    }

    public function renew(Ad $ad)
    {
        if (!$ad->adtype->can_renew) {
            return false;
        }

        $adstatus = Adstatus::whereTitle('pending')->first();
        $ad->adstatus()->associate($adstatus);
        $ad->save();
        event(new AdRenewRequested($ad, $this->user));

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ad $ad
     *
     * @return Response
     */
    public function destroy(Ad $ad)
    {
        $adstatus = Adstatus::whereTitle('trashed')->first();
        $ad->adstatus()->associate($adstatus);
        $ad->save();
        event(new AdWillBeDeleted($ad, $this->user));
        $ad->delete();
        event(new AdWasDeleted($ad, $this->user));
    }
}
