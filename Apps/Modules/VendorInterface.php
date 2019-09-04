<?php
namespace Apps\Modules;


interface VendorInterface
{
    /**
     * @return string
     */
    public function getVendorCode() : string;

    /**
     * @return mixed
     */
    public function process();
}