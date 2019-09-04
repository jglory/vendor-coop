<?php
namespace Phalconry\Repositories\Repository\Delivery;


use Phalconry\Aggregates\Delivery\ExternalProduct as ExternalProductModel;

interface ExternalProductInterface
{
    /**
     * @param $externalproductID
     * @return ExternalProductModel|bool
     */
    public function findFirstByID($externalproductID);
}