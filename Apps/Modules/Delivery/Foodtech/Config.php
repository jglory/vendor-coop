<?php
namespace Apps\Modules\Delivery\Foodtech;


abstract class CommonConfig
{
}

switch (APP_ENV) {
    case ENV_DEV :
        abstract class Config extends CommonConfig
        {
        }
        break;

    case ENV_STAGING :
        abstract class Config extends CommonConfig
        {
        }
        break;

    case ENV_PRODUCTION :
        abstract class Config extends CommonConfig
        {
        }
        break;

}