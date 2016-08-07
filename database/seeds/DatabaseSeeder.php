<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(NotificationsTableSeeder::class);
        // Ensure that website creation is the first notification.
        sleep(1);
        $this->call(AdstatusesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(SubscriptionsTableSeeder::class);
        $this->call(AdtypesTableSeeder::class);
        if (env('VERSION_DEMO', false)) {
            $this->call(UsersTableSeeder::class); // For demo
        }
        $this->call(SubscriptionAdtypesTableSeeder::class);
        if (env('VERSION_DEMO', false)) {
            $this->call(UserAdtypesTableSeeder::class); // For demo
        }
        $this->call(GatewaysTableSeeder::class);
        $this->call(DashboardWidgetsTableSeeder::class);
        $this->call(ThemepartialsTableSeeder::class);
        $this->call(TemplatesTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(PageTableSeeder::class);
        $this->call(CategoriesFieldsSeeder::class);
        if (env('VERSION_DEMO', false)) {
            $this->call(AdsTableSeeder::class); // For demo
        }
        $this->call(FixedPagesSeeder::class);

        Model::reguard();
    }
}
