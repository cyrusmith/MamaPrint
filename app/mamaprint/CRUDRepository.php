<?php

namespace mamaprint;

interface CRUDRepository {

    public function find($id);
    public function save($entity);
    public function delete($entity);

}