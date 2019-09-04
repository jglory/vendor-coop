<?php
namespace Apps\Modules\Delivery\Foodtech;


use Apps\Modules\Delivery\OutBound As OutBoundBase;

abstract class OutBound extends OutBoundBase
{
    /**
     * @inheritDoc
     */
    public function getVendorCode() : string
    {
        return Config::VENDOR_CODE;
    }
}