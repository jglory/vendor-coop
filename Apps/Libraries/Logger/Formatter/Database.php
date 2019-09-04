<?php
namespace Apps\Libraries\Logger\Formatter;

use Phalconry\Entities\AppsLog;
use Phalconry\Entities\AppsLog as AppsLogEntity;

class Database implements \Phalcon\Logger\FormatterInterface
{
    private $tag;

    public function __construct(string $tag = '')
    {
        $this->tag = $tag;
    }

    /**
     * @inheritDoc
     * @return AppsLogEntity
     */
    public function format($message, $type, $timestamp, $context = null)
    {
        return (new AppsLogEntity())
            ->setTag($this->tag)
            ->setCreated($timestamp)
            ->setType($type)
            ->setMessage($message)
            ->setContext(is_null($context) ? null : json_encode($context, JSON_UNESCAPED_UNICODE));
    }
}