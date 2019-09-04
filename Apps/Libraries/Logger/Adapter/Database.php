<?php
namespace Apps\Libraries\Logger\Adapter;

use Apps\Libraries\Logger\Formatter\Database as DatabaseFormatter;

use Phalcon\Logger\Adapter;
use Phalcon\Logger\Exception;
use Phalcon\Logger\FormatterInterface;
use Phalcon\Logger\Formatter\Line as LineFormatter;

use Phalconry\Entities\AppsLog as AppsLogEntity;

/**
 * Apps\Libraries\Logger\Adapter\Database
 *
 * 데이터베이스 레코드 형태로 로그를 저장하는 어댑터
 */
class Database extends Adapter
{
    /**
     * @var DatabaseFormatter $formatter
     */
    protected $formatter;

    /**
     * \Apps\Libraries\Database constructor
     */
    public function __construct(string $tag = '')
	{
	    $this->formatter = new DatabaseFormatter($tag);
    }

	/**
     * @return Database
     */
	public function getFormatter()
	{
		return $this->formatter;
	}

	/**
     * Writes the log to the file itself
     */
	public function logInternal(string $message, int $type, int $time, array $context = null)
	{
        $this->getFormatter()->format($message, $type, $time, $context)->save();
	}

	/**
     * Closes the logger
     */
	public function close()
	{
        return true;
	}

	/**
     * Opens the internal file handler after unserialization
     */
	public function __wakeup()
    {
        throw new \BadMethodCallException("__wakeup() 메소드는 지원하지 않습니다.");
    }
}