<?php
namespace Phalconry\Services\Service;


use Apps\Libraries\Log;

class VendorModuleResolver implements VendorModuleResolverInterface
{
    private function direction2DirectoryName($direction)
    {
    }

    private function vendorCode2VendorName($vendorCode)
    {
    }

    protected function moduleName2ClassName($vendorCode, $direction, $module)
    {
    }

    public function resolve(string $vendorCode, int $direction, string $moduleName)
    {
        $className = $this->moduleName2ClassName($vendorCode, $direction, $moduleName);
        if (class_exists($className)===false) {
            return null;
        }

        return new $className();
    }
}