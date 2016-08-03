<?php

namespace ZEDx\Services\Frontend\Ad;

use Cache;
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Support\Collection;
use Request;
use Validator;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Mailers\AdMail;
use ZEDx\Models\Ad;
use ZEDx\Models\Category;

class AdService extends Controller
{
    /**
     * Contact user.
     *
     * @param Ad      $ad
     * @param Request $request
     *
     * @return Response
     */
    public function contact(Ad $ad, BaseRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'message' => 'required|min:20',
            'email'   => 'required|email',
        ]);

        if ($validator->fails()) {
            return [
                'error'   => true,
                'message' => $validator->errors(),
            ];
        }

        if ($this->sendMailToUser($ad, $request)) {
            return [
                'error'   => false,
                'message' => trans('frontend.ad.contact.sent'),
            ];
        }

        return [
            'error'   => true,
            'message' => trans('frontend.ad.contact.cant_send'),
        ];
    }

    /**
     * Send mail to user.
     *
     * @param Ad      $ad
     * @param Request $request
     *
     * @return bool
     */
    protected function sendMailToUser(Ad $ad, BaseRequest $request)
    {
        $mailer = new AdMail();

        $dataSubject = [
            'ad_title'      => $ad->content->title,
            'website_title' => setting()->website_title,
        ];

        $dataMessage = [
            'message'       => $request->message,
            'sender_name'   => $request->name,
            'sender_email'  => $request->email,
            'sender_phone'  => $request->phone,
            'ad_title'      => $ad->content->title,
            'website_title' => setting()->website_title,
            'ad_url'        => route('ad.show', [$ad->id, str_slug($ad->content->title)]),
        ];

        return $mailer
            ->user()
            ->contactUser($ad->user, ['data' => $dataMessage], $dataSubject);
    }

    /**
     * Show user phone.
     *
     * @param Ad $ad
     *
     * @return string
     */
    public function phone(Ad $ad)
    {
        $number = null;

        if (!Request::ajax()) {
            abort(404);
        }

        if ($ad->user->is_phone) {
            $number = $ad->user->phone;
        }

        if (strlen($number)) {
            return $this->phoneToEncodedImage($number);
        }
    }

    /**
     * Encode phone to image.
     *
     * @param string $number
     *
     * @return string
     */
    protected function phoneToEncodedImage($number)
    {
        $im = imagecreate(strlen($number) * 10, 40);
        $bg = imagecolorallocate($im, 255, 255, 255);
        $textcolor = imagecolorallocate($im, 0, 0, 0);
        imagestring($im, 5, 0, 10, $number, $textcolor);
        ob_start();
        imagepng($im);
        $pngData = ob_get_contents();
        ob_end_clean();

        return 'data:image/png;base64,'.base64_encode($pngData);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function search($params)
    {
        $filters = (object) $this->getFilters($params);

        $ads = Ad::where('adstatus_id', '=', 1);

        if (!is_null($filters->user_status)) {
            $ads = $ads->join('users', function ($join) use ($filters) {
                $join->on('ads.user_id', '=', 'users.id')->where('users.status', '=', $filters->user_status);
            });
        }

        if ($category = Category::find($filters->category_id)) {
            $categories = $category->getDescendantsAndSelf(['id'])->keyBy('id')->keys()->toArray();
            $ads = $ads->whereIn('category_id', $categories);
        }

        $ads = $ads->join('adcontents', 'ads.id', '=', 'adcontents.ad_id');

        /*
        // Fulltext (valid only on MySql)
        $ads = $query ? $ads->whereRaw('MATCH('.\DB::getTablePrefix().'adcontents.title,'.\DB::getTablePrefix().'adcontents.body) AGAINST(? IN BOOLEAN MODE)', [str_replace(' ', ' +', $query) . "*"]) : $ads;
         */

        // Like (valid on all SGBD)
        $ads = $filters->query ? $ads->where('adcontents.title', 'like', '%'.$filters->query.'%')
            ->orWhere('adcontents.body', 'like', '%'.$filters->query.'%') : $ads;

        if (is_numeric($filters->lat) && is_numeric($filters->lng) && is_numeric($filters->radius)) {
            $ads = $ads->join('geolocations', 'ads.id', '=', 'geolocations.ad_id')
            //->whereRaw('? >= (SELECT SQRT(POW((? - `location_lat`),2) + POW((? - `location_lng`),2)))', [$filters->radius, $filters->lat, $filters->lng]);
                ->whereRaw('? >= (SELECT ((? - `location_lat`) * (? - `location_lat`)) + ((? - `location_lng`) * (? - `location_lng`)))', [pow($filters->radius, 2), $filters->lat, $filters->lat, $filters->lng, $filters->lng]);
        }

        $ads = $this->joinFields($ads, $filters->fields);
        $ads = $ads->join('adtypes', 'ads.adtype_id', '=', 'adtypes.id')
            ->orderBy('is_headline', 'desc')
            ->orderBy('published_at', 'desc')
            ->select('ads.*', 'adcontents.title', 'is_headline')
            ->groupBy('ads.id')
            ->paginate(20);

        $fields = Collection::make($filters->fields);

        return [
            'data' => compact('ads', 'query', 'category_id', 'lat', 'lng', 'radius', 'location', 'fields'),
        ];
    }

    /**
     * Get filters.
     *
     * @param string $params
     *
     * @return array
     */
    public function getFilters($params = '')
    {
        $data = explode('/', $params);
        $location = isset($data[1]) ? $data[1] : null;
        $query = Request::get('q');
        $user_status = in_array(Request::get('us'), ['0', '1']) ? Request::get('us') : null;
        $category_id = Request::get('c');
        $lat = Request::get('lat');
        $lng = Request::get('lng');
        $radius = Request::get('radius');
        $fields_data = Request::get('fields');
        $fields = Cache::rememberForever('search-fields-'.$fields_data, function () use ($fields_data) {
            return $this->getFields($fields_data);
        });

        return [
            'location'    => $location,
            'query'       => $query,
            'user_status' => $user_status,
            'category_id' => $category_id,
            'lat'         => $lat,
            'lng'         => $lng,
            'radius'      => $radius,
            'fields'      => $fields,
        ];
    }

    /**
     * Prepare ad Fields.
     *
     * @param Builder $ads
     * @param array   $fields
     *
     * @return Builder
     */
    protected function joinFields($ads, $fields)
    {
        $f = 1;
        foreach ($fields as $field) {
            switch ($field['type']) {
                case 'select':
                    $ads = $this->attachQueryJoin($ads, $f, $field);
                    $ads = $ads->where('zedx__tmpF'.$f.'.value', '=', $field['value']);
                    break;
                case 'checkbox':
                    $ads = $this->attachQueryJoin($ads, $f, $field);
                    $ads = $ads->whereIn('zedx__tmpF'.$f.'.value', $field['value']);
                    break;
                case 'input':
                    if ($field['value']['min'] != '*' || $field['value']['max'] != '*') {
                        $ads = $this->attachQueryJoin($ads, $f, $field);
                    }
                    if ($field['value']['min'] != '*' && $field['value']['max'] != '*') {
                        $ads = $ads->whereBetween('zedx__tmpF'.$f.'.value', [$field['value']['min'], $field['value']['max']]);
                    } elseif ($field['value']['min'] != '*') {
                        $ads = $ads->where('zedx__tmpF'.$f.'.value', '>=', $field['value']['min']);
                    } elseif ($field['value']['max'] != '*') {
                        $ads = $ads->where('zedx__tmpF'.$f.'.value', '<=', $field['value']['max']);
                    }
                    break;
            }
            $f++;
        }

        return $ads;
    }

    /**
     * Attach join queries.
     *
     * @param Builder $ads
     * @param int     $f
     * @param array   $field
     *
     * @return Builder
     */
    protected function attachQueryJoin($ads, $f, $field)
    {
        $ads = $ads->join('ad_field AS zedx__tmpF'.$f, function ($join) use ($f, $field) {
            $join->on('ads.id', '=', 'zedx__tmpF'.$f.'.ad_id')->where('zedx__tmpF'.$f.'.field_id', '=', $field['field_id']);
        });

        return $ads;
    }

    /**
     * Get Fields from request.
     *
     * @param string $fields_data
     *
     * @return array
     */
    protected function getFields($fields_data)
    {
        $list_fields = [];

        if ($fields_data == null) {
            return [];
        }

        $_fields_data = array_filter(explode('f', $fields_data));
        foreach ($_fields_data as $fields) {
            $_fields = explode('a', $fields);
            if (count($_fields) == 2 && is_numeric($_fields[0])) {
                $minmax = explode('b', $_fields[1]);
                $checkbox = explode('|', $_fields[1]);
                if (count($minmax) == 2 && (is_numeric($minmax[0]) || $minmax[0] == '*') && (is_numeric($minmax[1]) || $minmax[1] == '*')) {
                    $list_fields[$_fields[0]] = $this->constructNumericInput($_fields, $minmax);
                } elseif (count($checkbox) > 1 && is_numeric_array($checkbox)) {
                    $list_fields[$_fields[0]] = $this->constructCheckbox($_fields, $checkbox);
                } elseif (is_numeric($_fields[1])) {
                    $list_fields[$_fields[0]] = $this->constructSelect($_fields, $_fields[1]);
                }
            }
        }

        return $list_fields;
    }

    /**
     * Construct numeric input fields.
     *
     * @param array $fields
     * @param array $values
     *
     * @return array
     */
    protected function constructNumericInput($fields, $values)
    {
        return [
            'field_id' => $fields[0],
            'type'     => 'input',
            'value'    => [
                'min' => $values[0],
                'max' => $values[1],
            ],
        ];
    }

    /**
     * Construct checkbox fields.
     *
     * @param array $fields
     * @param array $values
     *
     * @return array
     */
    protected function constructCheckbox($fields, $values)
    {
        return [
            'field_id' => $fields[0],
            'type'     => 'checkbox',
            'value'    => $values,
        ];
    }

    /**
     * Construct selectbox fields.
     *
     * @param array $fields
     * @param array $value
     *
     * @return array
     */
    protected function constructSelect($fields, $value)
    {
        return [
            'field_id' => $fields[0],
            'type'     => 'select',
            'value'    => $value,
        ];
    }
}
