<?php

use Illuminate\Database\Seeder;
use ZEDx\Events\Ad\AdWasCreated;
use ZEDx\Events\Ad\AdWasValidated;
use ZEDx\Models\Ad;
use ZEDx\Models\Adstatus;
use ZEDx\Models\Adtype;
use ZEDx\Utils\Geolocation;

class AdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lambo = $this->getLamboData();
        $dodge = $this->getDodgeData();
        $villa = $this->getVillaData();
        $basic = Adtype::find(1);
        $gold = Adtype::find(3);

        $this->createAd($gold, $lambo);
        $this->createAd($basic, $dodge);
        $this->createAd($gold, $villa);
    }

    protected function createAd($adtype, $data)
    {
        $geo = new Geolocation($data['geolocation_data']);
        $adstatus = Adstatus::whereTitle('pending')->first();

        $ad = new Ad();
        $ad->user_id = $data['user_id'];
        $ad->category_id = $data['category_id'];
        $ad->adstatus()->associate($adstatus);
        $ad->adtype()->associate($adtype);
        $ad->save();

        $ad->geolocation()->create($geo->get());
        $ad->content()->create($data['content']);

        if ($ad->adtype->can_add_pic) {
            $this->syncAdPhotos($ad, $data);
        }
        if ($ad->adtype->can_add_video) {
            $this->syncAdVideos($ad, $data);
        }

        $this->syncAdFields($ad, $data);

        event(new AdWasCreated($ad, 'ZEDx'));

        $this->validateAd($ad);
    }

    protected function validateAd($ad)
    {
        $adstatus = Adstatus::whereTitle('validate')->first();
        $ad->adstatus()->associate($adstatus->id);
        event(new AdWasValidated($ad, 'ZEDx'));
    }

    protected function syncAdFields($ad, $data)
    {
        $fields = $data['fields'];
        foreach ($fields as $fieldId => $value) {
            $values = is_array($value) ? $value : [$value];
            foreach ($values as $value) {
                $ad->fields()->attach($fieldId, ['value' => $value]);
            }
        }
    }

    protected function syncAdPhotos($ad, $data)
    {
        $i = 0;
        $max = $ad->adtype->nbr_pic;
        $photos = $data['photos'];
        foreach ($photos as $photo) {
            if ($max > $i) {
                $ad->photos()->create($photo);
                $i++;
            }
        }
    }

    protected function syncAdVideos($ad, $data)
    {
        $i = 0;
        $max = $ad->adtype->nbr_video;
        $videos = $data['videos'];
        foreach ($videos as $video) {
            if ($max > $i) {
                $ad->videos()->create($video);
                $i++;
            }
        }
    }

    protected function getLamboData()
    {
        return [
        'content' => [
          'title' => 'Lamborghini aventador',
          'body'  => 'La Lamborghini Aventador LP700-4, connue en interne sous les codes « LB834 » (coupé) et « LB835 » (roadster), est une supercar développée par le constructeur italien Lamborghini. Dévoilée au salon de Genève 2011, elle remplace la Lamborghini Murciélago.',
        ],
        'videos' => [
          ['link' => 'g_YToB10qUs'],
        ],
        'user_id'          => '1',
        'geolocation_data' => '{"address_components":[{"long_name":"Paris","short_name":"Paris","types":["locality","political"]},{"long_name":"Paris","short_name":"Paris","types":["administrative_area_level_2","political"]},{"long_name":"Île-de-France","short_name":"Île-de-France","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Paris, France","geometry":{"bounds":{"northeast":{"lat":48.9021449,"lng":2.4699208},"southwest":{"lat":48.815573,"lng":2.225193}},"location":{"lat":48.856614,"lng":2.3522219},"location_type":"APPROXIMATE","viewport":{"northeast":{"lat":48.9021449,"lng":2.4699208},"southwest":{"lat":48.815573,"lng":2.225193}}},"place_id":"ChIJD7fiBh9u5kcRYJSMaMOCCwQ","types":["locality","political"]}',
        'category_id'      => '4',
        'fields'           => [
          5  => '1000000',
          6  => '2011',
          7  => '30',
          8  => '6',
          9  => '11',
          10 => '16',
        ],
        'photos' => [
          ['path' => 'zedx_d82f59e8.jpg', 'is_main' => 1],
          ['path' => 'zedx_c7151070.jpg', 'is_main' => 0],
          ['path' => 'zedx_c3b9aa93.jpg', 'is_main' => 0],
          ['path' => 'zedx_fce82ef4.jpg', 'is_main' => 0],
        ],
      ];
    }

    protected function getDodgeData()
    {
        return [
        'content' => [
          'title' => 'Dodge challenger',
          'body'  => "Challenger est le nom de trois modèles de voitures de Dodge, marque américaine fondée par les frères Dodge en 1914 et rachetée par Chrysler en 1928.

La première Challenger est une pony car fabriquée de 1970 à 1974 qui est devenue une icône de la culture automobile américaine et un modèle classique de style qui sert toujours de référence aux ingénieurs de Dodge.

L'appellation Challenger a ensuite été attribuée de 1978 à 1983 à la Mitsubishi Galant Lambda Coupé importée du Japon par Chrysler. Enfin, l'appellation est reprise en 2008 pour le tout nouveau modèle néo-rétro que Dodge lance en 2009 pour concurrencer la Ford Mustang ainsi que la Chevrolet Camaro.",
        ],
        'videos'           => [],
        'user_id'          => '1',
        'geolocation_data' => '{"address_components":[{"long_name":"Alsace","short_name":"Alsace","types":["colloquial_area","political"]},{"long_name":"Alsace-Champagne-Ardenne-Lorraine","short_name":"Alsace-Champagne-Ardenne-Lorraine","types":["administrative_area_level_1","political"]}],"formatted_address":"Alsace","geometry":{"bounds":{"northeast":{"lat":49.0778581,"lng":8.2335491},"southwest":{"lat":47.4202619,"lng":6.841025999999999}},"location":{"lat":48.3181795,"lng":7.441624099999999},"location_type":"APPROXIMATE","viewport":{"northeast":{"lat":49.0778581,"lng":8.2335491},"southwest":{"lat":47.4202619,"lng":6.841025999999999}}},"place_id":"ChIJv5Z326NGkUcR4CQ3mrlfCgE","types":["colloquial_area","political"]}',
        'category_id'      => '4',
        'fields'           => [
          5  => '250000',
          6  => '2015',
          7  => '50',
          8  => '10',
          9  => '11',
          10 => '16',
        ],
        'photos' => [
          ['path' => 'zedx_82b2706d.jpg', 'is_main' => 1],
          ['path' => 'zedx_4ac9f237.jpg', 'is_main' => 0],
          ['path' => 'zedx_b4c26cd3.jpg', 'is_main' => 0],
        ],
      ];
    }

    protected function getVillaData()
    {
        return [
        'content' => [
          'title' => 'Villa de luxe',
          'body'  => "Batna (en arabe باتنة - Bātnah, en chaoui Bathenth , en tifinagh : Batna in Tifinagh.svg) Prononciation du titre dans sa version originale Écouter est une commune d'Algérie de la wilaya de Batna, dont elle est le chef-lieu, située à 435 km au sud-est d'Alger et à 113 km au sud-ouest de Constantine.\r\n

La ville de Batna est considérée historiquement comme étant la « capitale » des Aurès. Située à 1 058 mètres d'altitude, elle est la 5e plus importante ville du pays avec 375 000 habitants et la plus haute agglomération d'Algérie bien qu'elle ait été construite dans une cuvette entourée de montagnes",
        ],
        'videos'           => [],
        'user_id'          => '1',
        'geolocation_data' => '{"address_components":[{"long_name":"Batna","short_name":"Batna","types":["locality","political"]},{"long_name":"Wilaya de Batna","short_name":"Wilaya de Batna","types":["administrative_area_level_1","political"]},{"long_name":"Algérie","short_name":"DZ","types":["country","political"]}],"formatted_address":"Batna, Algérie","geometry":{"bounds":{"northeast":{"lat":35.6445679,"lng":6.2781502},"southwest":{"lat":35.5123258,"lng":6.0786152}},"location":{"lat":35.5610218,"lng":6.173911599999999},"location_type":"APPROXIMATE","viewport":{"northeast":{"lat":35.6445679,"lng":6.2781502},"southwest":{"lat":35.5123258,"lng":6.0786152}}},"place_id":"ChIJn5Avv5UR9BIRwNPH7wb3Im4","types":["locality","political"]}',
        'category_id'      => '2',
        'fields'           => [
          1 => '150000',
          2 => '200',
          3 => '6',
          4 => [1],
        ],
        'photos' => [
          ['path' => 'zedx_4a167be9.jpg', 'is_main' => 1],
          ['path' => 'zedx_a9170e5b.jpg', 'is_main' => 0],
          ['path' => 'zedx_eba3ae5e.jpg', 'is_main' => 0],
          ['path' => 'zedx_de232a4a.jpg', 'is_main' => 0],
          ['path' => 'zedx_62167abe.jpg', 'is_main' => 0],
        ],
      ];
    }
}
