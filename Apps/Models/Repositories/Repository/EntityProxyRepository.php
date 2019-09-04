<?php
namespace Phalconry\Repositories\Repository;

use Apps\Libraries\EntityProxy;
use Phalcon\Mvc\Model\TransactionInterface;

class EntityProxyRepository
{
    const METHOD_DELETE = 'delete';
    const METHOD_INSERT = 'create';
    const METHOD_UPDATE = 'save';

    /**
     * 입력된 메소드 값과 데이터에 따른 데이터베이스 작업을 수행한다.
     * @param string $method
     * @param EntityProxy $entityProxy
     * @param \Phalcon\Mvc\Model\Transaction $transaction
     * @throws \Exception
     */
    private function dbProcess(string $method, EntityProxy $entityProxy, $transaction = null)
    {
        if (is_null($entityProxy)) {
            return;
        }
        $entity = $entityProxy->getTargetEntity();
        if (is_null($transaction)===false) {
            $entity->setTransaction($transaction);
        }

        if ($entity->$method()===false) {
            $message = 'db' . $method . ' -  '. $this->getEntityErrorMessages($entity);
            Log::error(__METHOD__.PHP_EOL . 'error : ' . $message);
            if (is_null($transaction)===false) {
                throw new \Exception($message, 0);
            }
        }
    }

    /**
     * 데이터베이스 삭제 작업을 수행한다.
     * @param EntityProxy $entityProxy
     * @param TransactionInterface|null $transaction
     * @throws \Exception
     */
    public function dbDelete(EntityProxy $entityProxy, TransactionInterface $transaction = null)
    {
        $this->dbProcess(self::METHOD_DELETE, $entityProxy, $transaction);
    }

    /**
     * 데이터베이스 입력 작업을 수행한다.
     * @param EntityProxy $entityProxy
     * @param TransactionInterface|null $transaction
     * @throws \Exception
     */
    public function dbInsert(EntityProxy $entityProxy, TransactionInterface $transaction = null)
    {
        $this->dbProcess(self::METHOD_INSERT, $entityProxy, $transaction);
    }

    /**
     * 데이터베이스 수정 작업을 수행한다.
     * @param EntityProxy $entityProxy
     * @param TransactionInterface|null $transaction
     * @throws \Exception
     */
    public function dbUpdate(EntityProxy $entityProxy, TransactionInterface $transaction = null)
    {
        $this->dbProcess(self::METHOD_UPDATE, $entityProxy, $transaction);
    }
}