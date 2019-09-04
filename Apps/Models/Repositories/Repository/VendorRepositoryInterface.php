<?php
namespace Phalconry\Repositories\Repository;


use Phalconry\Aggregates\Aggregate;

use Phalcon\Mvc\Model\TransactionInterface;

interface VendorRepositoryInterface
{
    const METHOD_DELETE = 'delete';
    const METHOD_INSERT = 'insert';
    const METHOD_UPDATE = 'update';
    /**
     * 영속성 컨텍스트에서 모델을 삭제한다.
     * @param Aggregate $model
     * @param TransactionInterface $transaction
     * @throws \Exception
     */
    public function delete(Aggregate $model, TransactionInterface $transaction = null);

    /**
     * 영속성 컨텍스트에 새로운 모델을 생성한다.
     * @param Aggregate $model
     * @param TransactionInterface $transaction
     * @throws \Exception
     */
    public function insert(Aggregate $model, TransactionInterface $transaction = null);

    /**
     * 영속성 컨텍스트에 모델의 정보를 수정한다.
     * @param Aggregate $model
     * @param TransactionInterface $transaction
     * @throws \Exception
     */
    public function update(Aggregate $model, TransactionInterface $transaction = null);
}