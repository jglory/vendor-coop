<?php
namespace Apps\Modules\Ticket\SmartInfini;


use Apps\Modules\Ticket\OutBound As OutBoundBase;

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