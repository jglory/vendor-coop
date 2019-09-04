<?php
namespace Phalconry\Aggregates\Ticket;


use Apps\Libraries\EntityProxy;
use Phalconry\Aggregates\Aggregate;

use Phalconry\Entities\Member As MemberEntity;

class Member extends Aggregate
{
    /**
     * @var EntityProxy $member
     */
    public $member;
    /**
     * @var EntityProxy $memberToken
     */
    public $memberToken;
    /**
     * @var EntityProxy $token
     */
    public $token;

    /**
     * Member constructor.
     *
     * @param EntityProxy|null $member
     */
    public function __construct(EntityProxy $member = null, EntityProxy $memberToken = null, EntityProxy $token = null)
    {
        $this->member = $member;
        $this->memberToken = $memberToken;
        $this->token = $token;
    }

    /**
     *
     * @return EntityProxy|null
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     *
     * @param EntityProxy|null $member
     * @return self
     */
    public function setMember($member): self
    {
        $this->member = $member;
        return $this;
    }

    /**
     * @return EntityProxy|null
     */
    public function getMemberToken()
    {
        return $this->memberToken;
    }

    /**
     * @param EntityProxy|null $memberToken
     * @return self
     */
    public function setMemberToken($memberToken): self
    {
        $this->memberToken = $memberToken;
    }

    /**
     * @return EntityProxy|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param EntityProxy|null $token
     * @return self
     */
    public function setToken($token): self
    {
        $this->token = $token;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'member' => $this->member,
            'memberToken' => $this->memberToken,
            'token' => $this->token
        ];
    }
}