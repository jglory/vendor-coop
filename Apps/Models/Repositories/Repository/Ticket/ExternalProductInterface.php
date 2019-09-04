<?php
namespace Phalconry\Repositories\Repository\Ticket;


use Phalconry\Aggregates\Ticket\ExternalProduct as ExternalProductModel;

interface ExternalProductInterface
{
    /**
     * @param $externalproductID
     * @return ExternalProductModel|bool
     */
    public function findFirstByID($externalproductID);
}