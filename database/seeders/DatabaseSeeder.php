<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $users = new Collection([
             ['name' => 'Bram'],
             ['name' => 'Ulijn'],
             ['name' => 'Martin'],
             ['name' => 'Maaike'],
             ['name' => 'Ezra'],
             ['name' => 'Nienke'],
             ['name' => 'Dylan'],
         ]);

         $users->each(function(array $user) {
             User::create($user);
         });

         $locations = new Collection([
             [
                 'uuid' => 'd9b0cde9-9ab6-4859-87b3-563c7ba1ccb9',
                 'name' => 'Vechtsebanen',
             ],
             [
                 'uuid' => 'f37fb2ae-bf24-44f1-9b81-61e6c0784840',
                 'name' => 'Zeehaenkade',
             ],
         ]);

         $locations->each(function(array $location) {
             Location::create($location);
         });
    }
}
