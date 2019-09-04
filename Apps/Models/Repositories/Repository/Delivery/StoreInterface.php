<?php
namespace Phalconry\Repositories\Repository\Delivery;


use Phalconry\Aggregates\Delivery\Store as StoreModel;

interface StoreInterface
{
    /**
     * @param $storeID
     * @return StoreModel|bool
     */
    public function findFirstByID($storeID);
}