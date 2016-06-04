<?php

use ZEDx\Models\Field;
use ZEDx\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      /* Categories */

      $property = $this->createCategory('IMMOBILIER');
        $sale = $this->createCategory('Ventes Immobilières')->makeChildOf($property);

        $vehicles = $this->createCategory('VEHICULES');
        $car = $this->createCategory('Voitures')->makeChildOf($vehicles);

      /* Fields */
      $this->createPropertyFields($sale);
        $this->createCarFields($car);
    }

    protected function createCategory($name, $is_private = 0, $is_visible = 1)
    {
        return Category::create([
        'name'       => $name,
        'is_private' => $is_private,
        'is_visible' => $is_visible,
      ]);
    }

    protected function createPropertyFields($category)
    {
        $price = $this->createField('Prix', 4, 'Prix immobilier', '{currency}', 1, 1, 1, 1);
        $price->search()->create(['min' => 0, 'max' => 1000000, 'step' => 10000]);
        $surface = $this->createField('Surface', 4, 'Surface immobilier', 'm²', 0, 1, 1, 1);
        $surface->search()->create(['min' => 0, 'max' => 500, 'step' => 10]);
        $room = $this->createField('Pièces', 4, 'Pièces immobilier', '', 0, 1, 1, 1);
        $room->search()->create(['min' => 1, 'max' => 8, 'step' => 1]);
        $type = $this->createField('Type', 2, 'Type immobilier', '', 0, 1, 1);
        $types = ['Maison', 'Appartement', 'Terrain', 'Parking', 'Autre'];
        foreach ($types as $key => $typeName) {
            $type->select()->create(['name' => $typeName, 'position' => $key + 1]);
        }

        $category->fields()->sync([
        $price->id,
        $surface->id,
        $room->id,
        $type->id,
      ]);
    }

    protected function createCarFields($category)
    {
        $price = $this->createField('Prix', 4, 'Prix voiture', '{currency}', 1, 1, 1, 1);
        $price->search()->create(['min' => 0, 'max' => 250000, 'step' => 10000]);
        $year = $this->createField('Année modèle', 4, 'Année modèle voiture', '', 0, 1, 1);
        $year->search()->create(['min' => 1960, 'max' => 2016, 'step' => 1]);
        $km = $this->createField('Kilomètres', 4, 'Kilomètres voiture', 'km', 0, 1, 1, 1);
        $km->search()->create(['min' => 0, 'max' => 300000, 'step' => 10000]);
        $mark = $this->createField('Marque', 1, 'Marque voiture', '', 0, 1, 1);
        $marks = ['Lamborghini', 'Ferrari', 'Audi', 'BMW', 'Dodge'];
        foreach ($marks as $key => $markName) {
            $mark->select()->create(['name' => $markName, 'position' => $key + 1]);
        }
        $energy = $this->createField('Energie', 1, 'Energie voiture', '', 0, 1, 1);
        $energies = ['Essence', 'Diesel', 'GPL', 'Electrique', 'Autre'];
        foreach ($energies as $key => $energyName) {
            $energy->select()->create(['name' => $energyName, 'position' => $key + 1]);
        }
        $gearbox = $this->createField('Boîte de vitesse', 1, 'Boîte de vitesse voiture', '', 0, 1, 1);
        $gearboxes = ['Manuelle', 'Automatique'];
        foreach ($gearboxes as $key => $gearboxName) {
            $gearbox->select()->create(['name' => $gearboxName, 'position' => $key + 1]);
        }
        $category->fields()->sync([
        $price->id,
        $year->id,
        $km->id,
        $mark->id,
        $energy->id,
        $gearbox->id,
      ]);
    }

    protected function createField($name, $type, $title, $unit, $is_price, $is_in_ad, $is_in_search, $is_format = 0)
    {
        return Field::create([
        'name'         => $name,
        'type'         => $type,
        'title'        => $title,
        'unit'         => $unit,
        'is_price'     => $is_price,
        'is_in_ad'     => $is_in_ad,
        'is_in_search' => $is_in_search,
        'is_format'    => $is_format,
      ]);
    }
}
