<?php

use Catalog\CatalogItem;
use User\User;

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

        DB::transaction(function () {
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
            $gallery = new \Gallery\Gallery();
            $gallery->save();
            $winterBook->galleries()->save($gallery);

            $winterBook = new CatalogItem();
            $winterBook->title = 'Набор для рисования';
            $winterBook->price = 14900;
            $winterBook->short_description = 'Набор для рисования кистями и маслом для очень тлантливых детей';
            $winterBook->registered_price = 7900;
            $winterBook->slug = 'paintset';
            $winterBook->save();
            $gallery = new \Gallery\Gallery();
            $gallery->save();
            $winterBook->galleries()->save($gallery);

            // $this->call('UserTableSeeder');
        });
    }

    public static function seedRoles()
    {

        Eloquent::unguard();

        (new Role([
            'name' => Role::ROLE_ADMIN
        ]))->save();

        (new Role([
            'name' => Role::ROLE_USER
        ]))->save();

    }

}
