<?php
namespace Apps\Modules\Ticket\SmartInfini;


use Apps\Modules\Ticket\InBound As InBoundBase;

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