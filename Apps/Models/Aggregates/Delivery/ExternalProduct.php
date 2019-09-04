<?php
namespace Phalconry\Aggregates\Delivery;


use Apps\Libraries\EntityProxy;
use Phalconry\Aggregates\Aggregate;

use Phalconry\Entities\ExternalProduct As ExternalProductEntity;

class ExternalProduct extends Aggregate
{
    /**
     * @var EntityProxy $externalProduct
     */
    public $externalProduct;

    /**
     * ExternalProduct constructor.
     * @param EntityProxy|null $externalProduct
     */
    public function __construct(EntityProxy $externalProduct = null)
    {
        $this->externalProduct = $externalProduct;
    }

    /**
     * @return EntityProxy|null
     */
    public function getExternalProduct()
    {
        return $this->externalProduct;
    }

    /**
     * @param EntityProxy|null $externalProduct
     * @return self
     */
    public function setExternalProduct($externalProduct): self
    {
        $this->externalProduct = $externalProduct;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return ['externalProduct' => $this->externalProduct];
    }
}