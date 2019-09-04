<?php
namespace Apps\Modules\Ticket\SmartInfini;

use Apps\Modules\Delivery\WMPO\Config As WMPOConfig;

abstract class CommonConfig
{
    const VENDOR_CODE = 'SMARTINFINI';    // * 필수
    const VENDOR_NAME = 'SmartInfini';    // * 필수

    const RESPONSE_SUCCESS_CODE = '0000';
    const RESPONSE_ERROR_MESSAGES = [
        'E001' => '인증토큰값 에러',
        'E002' => '유효성검사 실패[상세정보 메시지 참고]',
        'E003' => '처리 실패[상세정보 메시지 참고]',
        'E004' => '주문이 존재하지 않습니다.',
        'E005' => '외부시스템 연동 실패(해당 에러의 경우 주문발급은 성공처리, 연동실패의 경우 스케줄러에 의하여 연동시도가 이뤄집니다. 추후 연동여부는 주문조회를 통하여 확인필요)',
        'E006' => '1개 이상의 상품에 오류가 발생하였습니다.',
        'E100' => '스마트인피니 서버 에러'
    ];
}

switch (APP_ENV) {
    case ENV_DEV :
        abstract class Config extends CommonConfig
        {
            const TOKEN = '';
            const RESELLER_CODE = '';
            const BASE_URL = 'https://external.smartinfini.com/v1/test';
            //const URL_ORDER = 'http://jglory.000webhostapp.com/post.php';
            const URL_ORDER = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/process';
            const URL_QUERYORDERBYORDERNO = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/searching/{orderNum}';
            const URL_CANCELORDERBYORDERNO = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/process/{orderNum}';
            const URL_TEXT = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/process/{orderNum}';
        }
        break;

    case ENV_STAGING :
        abstract class Config extends CommonConfig
        {
            const TOKEN = '';
            const RESELLER_CODE = '';
            const BASE_URL = 'https://external.smartinfini.com/v1/test';
            const URL_ORDER = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/process';
            const URL_QUERYORDERBYORDERNO = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/searching/{orderNum}';
            const URL_CANCELORDERBYORDERNO = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/process/{orderNum}';
            const URL_TEXT = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/process/{orderNum}';
        }
        break;

    case ENV_PRODUCTION :
        abstract class Config extends CommonConfig
        {
            const TOKEN = '';
            const RESELLER_CODE = '';
            const BASE_URL = 'https://external.smartinfini.com/v1/test';
            const URL_ORDER = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/process';
            const URL_QUERYORDERBYORDERNO = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/searching/{orderNum}';
            const URL_CANCELORDERBYORDERNO = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/process/{orderNum}';
            const URL_TEXT = self::BASE_URL.'/reseller/'.self::RESELLER_CODE.'/process/{orderNum}';
        }
        break;

}