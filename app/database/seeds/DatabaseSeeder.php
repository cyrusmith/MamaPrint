<?php

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
        $user->save();

        $winterBook = new \Catalog\CatalogItem();

        $winterBook->title = 'Winter book';
        $winterBook->price = 99;
        $winterBook->registered_price = 39;
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
