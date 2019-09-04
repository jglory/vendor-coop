<?php
namespace Phalconry\Repositories\Repository\Delivery;


use Phalconry\Aggregates\Delivery\Member as MemberModel;

interface MemberInterface
{
    /**
     * @param $memberID
     * @return MemberModel|bool
     */
    public function findFirstByID($memberID);
}