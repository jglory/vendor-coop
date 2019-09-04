<?php
namespace Phalconry\Aggregates\Ticket;


use Apps\Libraries\EntityProxy;
use Phalconry\Aggregates\Aggregate;

use Phalconry\Entities\ExternalVendor As ExternalVendorEntity;

class ExternalVendor extends Aggregate
{
    /**
     * @var EntityProxy $externalVendor
     */
    public $externalVendor;

    /**
     * ExternalVendor constructor.
     *
     * @param EntityProxy|null $externalVendor
     */
    public function __construct(EntityProxy $externalVendor = null)
    {
        $this->externalVendor = $externalVendor;
    }

    /**
     *
     * @return EntityProxy|null
     */
    public function getExternalVendor()
    {
        return $this->externalVendor;
    }

    /**
     *
     * @param EntityProxy|null $externalVendor
     * @return self
     */
    public function setExternalVendor($externalVendor): self
    {
        $this->externalVendor = $externalVendor;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return ['externalVendor' => $this->externalVendor];
    }
}