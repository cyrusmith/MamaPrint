<?php

namespace mamaprint\domain\user;

use User\User;

class UserRepository implements UserRepositoryInterface
{

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
        $entity->delete();
    }

    public function findGuest($guestId)
    {
        if(empty($guestId)) return null;
        return User::where('guestid', '=', $guestId)->first();
    }

    public function findSocial($socialId, $type)
    {
        if (empty($socialId) || empty($type)) return null;
        return User::where('socialid', '=', $socialId)->where('type', '=', $type)->first();
    }
}