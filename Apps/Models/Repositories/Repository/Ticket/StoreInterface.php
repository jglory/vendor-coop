<?php
namespace Phalconry\Repositories\Repository\Ticket;


use Phalconry\Aggregates\Ticket\Store as StoreModel;

interface StoreInterface
{
    /**
     * @param $storeID
     * @return StoreModel|bool
     */
    public function findFirstByID($storeID);
}