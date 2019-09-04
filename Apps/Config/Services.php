<?php

use Apps\Common\Constants\ExternalCode;
use Apps\Libraries\Log;
use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Db\Profiler as ProfilerDb;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalconry\Entities\Store as EntityStore;

$di = new FactoryDefault();


// Push Message
$di->setShared('pushMessageService', \Phalconry\Services\Service\PushMessage::class);

// Vendor Model Factory
$di->setShared('vendorDeliveryModelFactory', \Phalconry\Aggregates\Delivery\Factory::class);
$di->setShared('vendorTicketModelFactory', \Phalconry\Aggregates\Ticket\Factory::class);

// WMPO Api Client
$di->setShared('wmpoApiClient', \Phalconry\Services\Service\WMPOApiClient::class);

// Vendor Repository
$di->setShared('entityProxyRepository', \Phalconry\Repositories\Repository\EntityProxyRepository::class);

$di->setShared('vendorDeliveryExternalProductRepository',
    [
        'className'  => \Phalconry\Repositories\Repository\Delivery\ExternalProduct::class,
        'properties' => [
            [
                'name'  => 'entityProxyRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'entityProxyRepository',
                ],
            ],
            [
                'name'  => 'modelsManager',
                'value' => [
                    'type'  => 'service',
                    'name' => 'modelsManager',
                ],
            ],
            [
                'name'  => 'vendorModelFactory',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorDeliveryModelFactory',
                ],
            ],
        ]
    ]
);
$di->setShared('vendorDeliveryExternalVendorRepository',
    [
        'className'  => \Phalconry\Repositories\Repository\Delivery\ExternalVendor::class,
        'properties' => [
            [
                'name'  => 'entityProxyRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'entityProxyRepository',
                ],
            ],
            [
                'name'  => 'modelsManager',
                'value' => [
                    'type'  => 'service',
                    'name' => 'modelsManager',
                ],
            ],
            [
                'name'  => 'vendorModelFactory',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorDeliveryModelFactory',
                ],
            ],
        ]
    ]
);
$di->setShared('vendorDeliveryMemberRepository',
    [
        'className'  => \Phalconry\Repositories\Repository\Delivery\Member::class,
        'properties' => [
            [
                'name'  => 'entityProxyRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'entityProxyRepository',
                ],
            ],
            [
                'name'  => 'modelsManager',
                'value' => [
                    'type'  => 'service',
                    'name' => 'modelsManager',
                ],
            ],
            [
                'name'  => 'vendorModelFactory',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorDeliveryModelFactory',
                ],
            ],
        ]
    ]
);
$di->setShared('vendorDeliveryOrderRepository',
    [
        'className'  => \Phalconry\Repositories\Repository\Delivery\Order::class,
        'properties' => [
            [
                'name'  => 'entityProxyRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'entityProxyRepository',
                ],
            ],
            [
                'name'  => 'modelsManager',
                'value' => [
                    'type'  => 'service',
                    'name' => 'modelsManager',
                ],
            ],
            [
                'name'  => 'vendorModelFactory',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorDeliveryModelFactory',
                ],
            ],
        ]
    ]
);
$di->setShared('vendorDeliveryStoreRepository',
    [
        'className'  => \Phalconry\Repositories\Repository\Delivery\Store::class,
        'properties' => [
            [
                'name'  => 'entityProxyRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'entityProxyRepository',
                ],
            ],
            [
                'name'  => 'modelsManager',
                'value' => [
                    'type'  => 'service',
                    'name' => 'modelsManager',
                ],
            ],
            [
                'name'  => 'vendorModelFactory',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorDeliveryModelFactory',
                ],
            ],
        ]
    ]
);


$di->setShared('vendorTicketExternalProductRepository',
    [
        'className'  => \Phalconry\Repositories\Repository\Ticket\ExternalProduct::class,
        'properties' => [
            [
                'name'  => 'entityProxyRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'entityProxyRepository',
                ],
            ],
            [
                'name'  => 'modelsManager',
                'value' => [
                    'type'  => 'service',
                    'name' => 'modelsManager',
                ],
            ],
            [
                'name'  => 'vendorModelFactory',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorTicketModelFactory',
                ],
            ],
        ]
    ]
);
$di->setShared('vendorTicketExternalVendorRepository',
    [
        'className'  => \Phalconry\Repositories\Repository\Ticket\ExternalVendor::class,
        'properties' => [
            [
                'name'  => 'entityProxyRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'entityProxyRepository',
                ],
            ],
            [
                'name'  => 'modelsManager',
                'value' => [
                    'type'  => 'service',
                    'name' => 'modelsManager',
                ],
            ],
            [
                'name'  => 'vendorModelFactory',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorTicketModelFactory',
                ],
            ],
        ]
    ]
);
$di->setShared('vendorTicketMemberRepository',
    [
        'className'  => \Phalconry\Repositories\Repository\Ticket\Member::class,
        'properties' => [
            [
                'name'  => 'entityProxyRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'entityProxyRepository',
                ],
            ],
            [
                'name'  => 'modelsManager',
                'value' => [
                    'type'  => 'service',
                    'name' => 'modelsManager',
                ],
            ],
            [
                'name'  => 'vendorModelFactory',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorTicketModelFactory',
                ],
            ],
        ]
    ]
);
$di->setShared('vendorTicketOrderRepository',
    [
        'className'  => \Phalconry\Repositories\Repository\Ticket\Order::class,
        'properties' => [
            [
                'name'  => 'entityProxyRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'entityProxyRepository',
                ],
            ],
            [
                'name'  => 'modelsManager',
                'value' => [
                    'type'  => 'service',
                    'name' => 'modelsManager',
                ],
            ],
            [
                'name'  => 'vendorModelFactory',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorTicketModelFactory',
                ],
            ],
        ]
    ]
);
$di->setShared('vendorTicketStoreRepository',
    [
        'className'  => \Phalconry\Repositories\Repository\Ticket\Store::class,
        'properties' => [
            [
                'name'  => 'entityProxyRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'entityProxyRepository',
                ],
            ],
            [
                'name'  => 'modelsManager',
                'value' => [
                    'type'  => 'service',
                    'name' => 'modelsManager',
                ],
            ],
            [
                'name'  => 'vendorModelFactory',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorTicketModelFactory',
                ],
            ],
        ]
    ]
);

// Vendor Service
$di->setShared('vendorDeliveryService',
    [
        'className'  => \Phalconry\Services\Service\Delivery\Vendor::class,
        'properties' => [
            [
                'name'  => 'externalProductRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'vendorDeliveryExternalProductRepository',
                ],
            ],
            [
                'name'  => 'externalVendorRepository',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorDeliveryExternalVendorRepository',
                ],
            ],
            [
                'name'  => 'memberRepository',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorDeliveryMemberRepository',
                ],
            ],
            [
                'name'  => 'orderRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'vendorDeliveryOrderRepository',
                ],
            ],
            [
                'name'  => 'storeRepository',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorDeliveryStoreRepository',
                ],
            ]
        ]
    ]
);
$di->set(
    'vendorTicketService',
    [
        'className'  => \Phalconry\Services\Service\Ticket\Vendor::class,
        'properties' => [
            [
                'name'  => 'externalProductRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'vendorTicketExternalProductRepository',
                ],
            ],
            [
                'name'  => 'externalVendorRepository',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorTicketExternalVendorRepository',
                ],
            ],
            [
                'name'  => 'memberRepository',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorTicketMemberRepository',
                ],
            ],
            [
                'name'  => 'orderRepository',
                'value' => [
                    'type' => 'service',
                    'name' => 'vendorTicketOrderRepository',
                ],
            ],
            [
                'name'  => 'storeRepository',
                'value' => [
                    'type'  => 'service',
                    'name' => 'vendorTicketStoreRepository',
                ],
            ]
        ]
    ]
);

// Vendor Module Resolver
$di->setShared('vendorModuleResolver', \Phalconry\Services\Service\VendorModuleResolver::class);

// PushMessage Notifier & Repository
$di->setShared('vendorEventListener', \Phalconry\Services\Service\VendorEventListener::class);

// vendor{$domain}{$vendorName}{$direction}EventListener
$di->setShared(
    'vendorDeliveryFoodtechOutBoundEventListener',
    [
        'className'  => \Phalconry\Services\Service\Delivery\EventListeners\Foodtech\InBound::class,
        'properties' => [
            [
                'name'  => 'pushMessageService',
                'value' => [
                    'type' => 'service',
                    'name' => 'pushMessageService',
                ],
            ],
            [
                'name'  => 'vendorService',
                'value' => [
                    'type' => 'service',
                    'name' => 'vendorTicketService',
                ],
            ],
        ]
    ]
);
$di->setShared(
    'vendorDeliveryFoodtechOutBoundEventListener',
    [
        'className'  => \Phalconry\Services\Service\Delivery\EventListeners\Foodtech\OutBound::class,
        'properties' => [
            [
                'name'  => 'pushMessageService',
                'value' => [
                    'type' => 'service',
                    'name' => 'pushMessageService',
                ],
            ],
            [
                'name'  => 'vendorService',
                'value' => [
                    'type' => 'service',
                    'name' => 'vendorTicketService',
                ],
            ],
        ]
    ]
);


$di->setShared(
    'vendorTicketSmartInfiniInBoundEventListener',
    [
        'className'  => \Phalconry\Services\Service\Ticket\EventListeners\SmartInfini\InBound::class,
        'properties' => [
            [
                'name'  => 'pushMessageService',
                'value' => [
                    'type' => 'service',
                    'name' => 'pushMessageService',
                ],
            ],
            [
                'name'  => 'vendorService',
                'value' => [
                    'type' => 'service',
                    'name' => 'vendorTicketService',
                ],
            ],
        ]
    ]
);
$di->setShared(
    'vendorTicketSmartInfiniOutBoundEventListener',
    [
        'className'  => \Phalconry\Services\Service\Ticket\EventListeners\SmartInfini\OutBound::class,
        'properties' => [
            [
                'name'  => 'pushMessageService',
                'value' => [
                    'type' => 'service',
                    'name' => 'pushMessageService',
                ],
            ],
            [
                'name'  => 'vendorService',
                'value' => [
                    'type' => 'service',
                    'name' => 'vendorTicketService',
                ],
            ],
        ]
    ]
);


// Event
$di->setShared(
    'eventsManager',
    function() use ($di) {
        $vendorEventListener = $di->get('vendorEventListener');

        $eventsManager = new Phalcon\Events\Manager();
        $eventsManager->attach(\Apps\Modules\Vendor::PROCESSED_EVENT_NAME, $vendorEventListener);
        return $eventsManager;
    }
);
