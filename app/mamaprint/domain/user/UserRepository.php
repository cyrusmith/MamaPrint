<?php

namespace mamaprint\domain\user;

use User\User;

class UserRepository implements UserRepositoryInterface {

    public function find($id)
    {
        return User::find($id);
    }

    public function save($entity)
    {
        $entity->save();
        return $entity;
    }

    public function delete($entity)
    {
        // TODO: Implement delete() method.
    }

    public function findGuest($guestId)
    {
        return User::where('guestid', '=', $guestId)->first();
    }
}