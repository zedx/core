<?php

namespace ZEDx\Utils;

class Geolocation
{
    protected $data = [
        'country'           => null,
        'location_lat'      => 0,
        'location_lng'      => 0,
        'southwest_lat'     => 0,
        'southwest_lng'     => 0,
        'northeast_lat'     => 0,
        'northeast_lng'     => 0,
        'radius'            => 0,
        'formatted_address' => '',
        'json'              => '',
    ];

    public function __construct($input)
    {
        $json = preg_replace('/:\s*(\-?\d+(\.\d+)?([e|E][\-|\+]\d+)?)/', ': "$1"', $input);
        $data = json_decode($json, true);

        if (! isset($data['formatted_address']) || ! isset($data['address_components'])) {
            return;
        }

        $formatted_address = $data['formatted_address'];

        if (! isset($data['geometry'])) {
            return;
        }

        $geometry = $data['geometry'];
        if (! isset($geometry['location']) || ! isset($geometry['location']['lat']) || ! isset($geometry['location']['lng'])) {
            return;
        }

        $location = $geometry['location'];
        $location_lat = $location['lat'];
        $location_lng = $location['lng'];

        if (! isset($geometry['viewport'])) {
            return;
        }
        $viewport = $geometry['viewport'];

        if (! isset($viewport['northeast']) || ! isset($viewport['southwest'])) {
            return;
        }

        $northeast = $viewport['northeast'];
        $southwest = $viewport['southwest'];

        if (! isset($northeast['lat']) || ! isset($northeast['lng']) || ! isset($southwest['lat']) || ! isset($southwest['lng'])) {
            return;
        }
        $northeast_lat = $northeast['lat'];
        $northeast_lng = $northeast['lng'];

        $southwest_lat = $southwest['lat'];
        $southwest_lng = $southwest['lng'];

        $radius = $this->radius($viewport);
        $country = $this->shortName($data['address_components'], 'country') ?: null;

        if ($radius == 0) {
            return;
        }

        $this->data = [
            'country'           => $country,
            'location_lat'      => $location_lat,
            'location_lng'      => $location_lng,
            'southwest_lat'     => $southwest_lat,
            'southwest_lng'     => $southwest_lng,
            'northeast_lat'     => $northeast_lat,
            'northeast_lng'     => $northeast_lng,
            'radius'            => $radius,
            'formatted_address' => $formatted_address,
            'json'              => $input,
        ];
    }

    public function get()
    {
        return $this->data;
    }

    protected function shortName($geo, $type)
    {
        foreach ($geo as $data) {
            if (isset($data['types']) && (reset($data['types']) == $type) && isset($data['short_name'])) {
                return $data['short_name'];
            }
        }

        return false;
    }

    protected function radius($a)
    {
        return ($this->distance([
            'x' => $a['southwest']['lat'],
            'y' => $a['southwest']['lng'],
        ], [
            'x' => $a['northeast']['lat'],
            'y' => $a['northeast']['lng'],
        ]) / 2);
    }

    protected function distance($p1, $p2)
    {
        return sqrt(pow(($p2['x'] - $p1['x']), 2) + pow(($p2['y'] - $p1['y']), 2));
    }

    protected function _haversine($lat1, $long1, $lat2, $long2, $R = '6367.5')
    {
        $delta_lat = deg2rad($lat2 - $lat1);
        $delta_long = deg2rad($long2 - $long1);
        $a = sin($delta_lat / 2) * sin($delta_lat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($delta_long / 2) * sin($delta_long / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;

        return $d;
    }
}
