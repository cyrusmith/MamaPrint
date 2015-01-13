<?php

use Catalog\CatalogItem;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Eloquent::unguard();

        self::seedRoles();

        $user = new User;
        $user->email = Config::get('mamaprint.adminemail');
        $user->name = 'admin';
        $user->password = Hash::make('admin');
        $user->save();

        $user->roles()->save(Role::getByName(Role::ROLE_ADMIN));
        $user->roles()->save(Role::getByName(Role::ROLE_USER));
        $user->save();

        $winterBook = new CatalogItem();

        $winterBook->title = 'Зимняя тетрадка';
        $winterBook->price = 9900;
        $winterBook->short_description = 'Тридцать творческих уроков на тему: «Новый год и зима» для детей дошкольного возраста';
        $winterBook->registered_price = 3900;
        $winterBook->slug = 'winterbook';
        $winterBook->save();

        // $this->call('UserTableSeeder');
    }

    public static function seedRoles() {

        Eloquent::unguard();

        (new Role([
            'name' => Role::ROLE_ADMIN
        ]))->save();

        (new Role([
            'name' => Role::ROLE_USER
        ]))->save();

    }

}
