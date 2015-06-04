<?php

namespace mamaprint\domain\user;

use mamaprint\CRUDRepository;

interface UserRepositoryInterface extends CRUDRepository
{
    public function findGuest($guestId);

    public function findSocial($socialId, $type);
}