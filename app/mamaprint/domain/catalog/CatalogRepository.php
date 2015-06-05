<?php
namespace mamaprint\domain\catalog;

use mamaprint\CRUDRepository;

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.06.2015
 * Time: 11:42
 */
class CatalogRepository implements CatalogRepositoryInterface
{

    public function find($id)
    {
        return CatalogItem::find($id);
    }

    public function save($entity)
    {
        $entity->save();
    }

    public function delete($entity)
    {
        //TODO
    }
}