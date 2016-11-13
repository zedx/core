<?php

namespace ZEDx\Http\Controllers\Backend;

use Auth;
use Illuminate\Support\Collection;
use Request;
use ZEDx\Events\Ad\AdWasBanned;
use ZEDx\Events\Ad\AdWasCreated;
use ZEDx\Events\Ad\AdWasDeleted;
use ZEDx\Events\Ad\AdWasExpired;
use ZEDx\Events\Ad\AdWasHold;
use ZEDx\Events\Ad\AdWasUpdated;
use ZEDx\Events\Ad\AdWasValidated;
use ZEDx\Events\Ad\AdWillBeCreated;
use ZEDx\Events\Ad\AdWillBeDeleted;
use ZEDx\Events\Ad\AdWillBeModerated;
use ZEDx\Events\Ad\AdWillBeUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\AdRequest;
use ZEDx\Http\Requests\AdtypeRequest;
use ZEDx\Models\Ad;
use ZEDx\Models\Adcontent;
use ZEDx\Models\Adstatus;
use ZEDx\Models\Adtype;
use ZEDx\Models\Category;
use ZEDx\Models\Geolocation;
use ZEDx\Models\Reason;
use ZEDx\Models\SelectField;
use ZEDx\Models\User;
use ZEDx\Utils\Geolocation as GeolocationHelper;
use ZEDx\Utils\PhotoManager;

class AdController extends Controller
{
    protected $admin;

    public function __construct()
    {
        $this->admin = Auth::guard('admin')->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $ads = Ad::with('content')
            ->recents()
            ->search(Request::get('q'))
            ->paginate(20);

        return view_backend('ad.index', compact('ads'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function filterByStatus(Adstatus $adstatus)
    {
        $ads = Ad::whereAdstatusId($adstatus->id)
            ->with('content')
            ->recents()
            ->withTrashed()
            ->search(Request::get('q'))
            ->paginate(20);

        return view_backend('ad.index', compact('ads', 'adstatus'));
    }

    /**
     * Moderate a Collection of Ads.
     *
     * @param Collection $ads
     * @param Adstatus   $adstatus
     *
     * @return Response
     */
    public function moderateAdsCollection(Collection $ads, Adstatus $adstatus)
    {
        if (Request::ajax()) {
            foreach ($ads as $ad) {
                event(new AdWillBeModerated($ad, $this->admin, $adstatus));
                $this->restoreIfTrashed($ad);
                $this->moderate($ad, $adstatus);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Moderate an Ad.
     *
     * @param Ad       $ad
     * @param Adstatus $adstatus
     *
     * @return Response
     */
    protected function moderate(Ad $ad, Adstatus $adstatus)
    {
        $oldStatus = $ad->adstatus->title;
        $ad->adstatus()->associate($adstatus->id);
        $ad->save();
        switch ($adstatus->title) {
            case 'validate':
                if ($oldStatus != 'validate') {
                    event(new AdWasValidated($ad, $this->admin));
                }
                break;
            case 'pending':
                if ($oldStatus != 'pending') {
                    event(new AdWasHold($ad, $this->admin));
                }
                break;
            case 'expired':
                if ($oldStatus != 'expired') {
                    event(new AdWasExpired($ad, $this->admin));
                }
                break;
            case 'banned':
                return $this->banish($ad, $oldStatus);
                break;
        }
    }

    /**
     * Banish an Ad.
     *
     * @param Ad $ad
     *
     * @return Reponse
     */
    protected function banish(Ad $ad, $oldStatus)
    {
        $reasons = [];
        $existingReasons = Request::get('reasons');
        $newReasons = Request::get('newReasons');

        $existingReasons = is_array($existingReasons) ? $existingReasons : [];

        if (is_array($newReasons)) {
            foreach ($newReasons as $reason) {
                $reasons[] = Reason::firstOrCreate($reason)->id;
            }
        }

        $reasons = array_merge($reasons, $existingReasons);
        $ad->reasons()->sync($reasons);
        if ($oldStatus != 'banned') {
            event(new AdWasBanned($ad, $this->admin));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Adtype $adtype)
    {
        return view_backend('ad.create', compact('adtype'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Adtype $adtype, AdRequest $request)
    {
        $geo = new GeolocationHelper($request->get('geolocation_data'));
        $adstatus = Adstatus::whereTitle('pending')->first();
        $user = User::findOrFail($request->get('user_id'));
        $category = Category::findOrFail($request->get('category_id'));

        $geolocation = new Geolocation();
        $geolocation->fill($geo->get());

        $content = new Adcontent();
        $content->fill($request->get('content'));

        $ad = new Ad();
        $ad->user()->associate($user);
        $ad->category()->associate($category);
        $ad->adstatus()->associate($adstatus);
        $ad->adtype()->associate($adtype);
        $ad->price = $this->getPrice($ad, $request);

        event(
            new AdWillBeCreated($ad, $content, $geolocation, $this->admin)
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

        event(
            new AdWasCreated($ad, $this->admin)
        );

        return redirect()->route('zxadmin.ad.edit', $ad->id);
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

        return view_backend('ad.edit', compact('ad', 'adtype', 'fields'));
    }

    public function updateAdtype(Ad $ad, Adtype $adtype, AdtypeRequest $request)
    {
        $inputs = $request->all();

        if ($ad->adtype_id != $adtype->id) {
            abort(400);
        }

        if ($adtype->is_customized) {
            unset($inputs['title']);
            $adtype->update($inputs);
        } else {
            $inputs['is_customized'] = 1;
            $adtype = Adtype::create($inputs);
            $ad->adtype()->associate($adtype)->save();
        }

        $nbrDays = $ad->adtype->nbr_days;
        $ad->expired_at = $nbrDays >= 9999 ? null : $ad->published_at->addDays($nbrDays + 1);
        $ad->save();

        return redirect()->route('zxadmin.ad.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Ad $ad, AdRequest $request)
    {
        $this->restoreIfTrashed($ad);
        $oldStatus = $ad->adstatus->title;
        $geo = new GeolocationHelper($request->get('geolocation_data'));
        $user = User::findOrFail($request->get('user_id'));
        $category = Category::findOrFail($request->get('category_id'));

        $ad->user()->associate($user);
        $ad->category()->associate($category);

        $ad->geolocation->fill($geo->get());
        $ad->content->fill($request->get('content'));
        $ad->price = $this->getPrice($ad, $request);

        event(
            new AdWillBeUpdated($ad, $this->admin)
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
            event(new AdWasUpdated($ad, $this->admin));
        }

        return redirect()->route('zxadmin.ad.edit', $ad->id);
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

    protected function restoreIfTrashed(Ad $ad)
    {
        if ($ad->trashed()) {
            $ad->restore();
        }
    }

    /**
     * Remove a Collection of Ads.
     *
     * @param Collection $ads
     *
     * @return Response
     */
    public function destroyAdsCollection(Collection $ads)
    {
        $forceDelete = Request::has('__forceDelete');
        foreach ($ads as $ad) {
            $this->destroy($ad, $forceDelete);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ad $ad
     *
     * @return Response
     */
    protected function destroy(Ad $ad, $forceDelete)
    {
        $adstatus = Adstatus::whereTitle('trashed')->first();
        $ad->adstatus()->associate($adstatus);
        $ad->save();
        event(new AdWillBeDeleted($ad, $this->admin, $forceDelete));
        if ($forceDelete) {
            $ad->forceDelete();
        } else {
            $ad->delete();
        }
        event(new AdWasDeleted($ad, $this->admin, $forceDelete));
    }

    /**
     * Choose the adType.
     *
     * @return Response
     */
    public function choose()
    {
        $adtypes = Adtype::all();

        return view_backend('ad.choose', compact('adtypes'));
    }
}
