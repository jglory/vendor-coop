<?php
namespace Apps\Modules\Delivery\Foodtech\OutBounds\Responses;


use Apps\Libraries\Log;

use Apps\Libraries\ApiClient\Response;

use Apps\Modules\Delivery\Foodtech\Config;

class CancelOrderByOrderNo extends Response
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    protected function initialize()
    {
        if ($this->getResponseText()===false)
        {
            throw new \Exception('vendor사에 주문취소가 전달되지 않았습니다.', -1);
        }
    }
}