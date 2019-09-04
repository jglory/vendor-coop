<?php
namespace Phalconry\Repositories\Repository\Ticket;


use Phalconry\Aggregates\Ticket\Member as MemberModel;

interface MemberInterface
{
    /**
     * @param $memberID
     * @return MemberModel|bool
     */
    public function findFirstByID($memberID);
}