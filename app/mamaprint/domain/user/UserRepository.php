<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 24.04.2015
 * Time: 14:43
 */

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
}