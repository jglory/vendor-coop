<?php
namespace Phalconry\Services\Service;


use Apps\Libraries\Log;

class VendorEventListener implements VendorEventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function onProcessed($event, $source, $data)
    {
        Log::debug(__METHOD__ . ' : ' . get_class($source));

        try {
            // get_class($source)
            //      === 'Apps\Vendors\Tickets\Modules\SmartInfini\OutBounds\{$module}'
            //      === 'Apps\Vendors\Deliveries\Modules\Foodtech\OutBounds\{$module}'
            $names = explode('\\', get_class($source));
            $domain = ($names[2] === 'Deliveries' ? 'Delivery' : 'Ticket');
            $vendorName = $names[4];
            $direction = ($names[5] === 'InBounds' ? 'InBound' : 'OutBound');
            $module = $names[6];// $vendorEventListenerName === '// vendor{$domain}{$vendorName}{$direction}EventListener'
            $method = 'onProcessed' . $module;

            $vendorEventListenerName = 'vendor' . $domain . $vendorName . $direction . 'EventListener';
            $di = \Phalcon\Di::getDefault();
            if ($di->has($vendorEventListenerName)) {
                $vendorEventListener = $di->get('vendor' . $domain . $vendorName . $direction . 'EventListener');
                if (method_exists($vendorEventListener, $method)) {
                    $vendorEventListener->$method($event, $source, $data);
                }
            }

        } catch (\Exception $e) {
            // exception 무시
            Log::error(__METHOD__ . ' : ' . $e->getCode() . '. ' . $e->getMessage());
        }
    }
}