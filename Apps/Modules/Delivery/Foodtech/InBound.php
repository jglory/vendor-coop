<?php
namespace Apps\Modules\Delivery\Foodtech;


use Apps\Modules\Delivery\InBound As InBoundBase;

abstract class InBound extends InBoundBase
{
    /**
     * @inheritDoc
     */
    public function getVendorCode() : string
    {
        return Config::VENDOR_CODE;
    }
}