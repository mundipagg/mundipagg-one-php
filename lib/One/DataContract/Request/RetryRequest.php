<?php

namespace MundiPagg\One\DataContract\Request;

use MundiPagg\One\DataContract\Common\BaseObject;

/**
 * Class RetryRequest
 * @package MundiPagg\One\DataContract\Request
 */
class RetryRequest extends BaseObject
{
    /**
     * @var Identificação da requisição na plataforma One
     */
    protected $RequestKey;

    /**
     * @var Identificação da Ordem na plataforma One
     */
    protected $OrderKey;

    /**
     * @return RequestKey
     */
    public function getRequestKey()
    {
        return $this->RequestKey;
    }

    /**
     * @param $RequestKey
     * @return $this
     */
    public function setRequestKey($RequestKey)
    {
        $this->RequestKey = $RequestKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderKey()
    {
        return $this->OrderKey;
    }

    /**
     * @param string $OrderKey
     * @return $this
     */
    public function setOrderKey($OrderKey)
    {
        $this->OrderKey = $OrderKey;

        return $this;
    }

    /**
     * @var RetryRequestData\SaleCreditCardTransaction[] Coleção de transações de cartão de crédito das transações que serão retentadas
     */
    protected $RetrySaleCreditCardTransactionCollection;

    public function __contruct()
    {
        $this->RetrySaleCreditCardTransactionCollection = null;
    }

    /**
     * @return array|RetryRequestData\SaleCreditCardTransaction[]
     */
    public function getRetrySaleCreditCardTransactionCollection()
    {
        if (empty($this->RetrySaleCreditCardTransactionCollection)){
            $this->RetrySaleCreditCardTransactionCollection = array();
        }

        return $this->RetrySaleCreditCardTransactionCollection;
    }

    /**
     * @param RetryRequestData\RetrySaleCreditCardTransaction|null $retrySaleCreditCardTransaction
     * @return RetryRequestData\RetrySaleCreditCardTransaction
     */
    public function addRetrySaleCreditCardTransactionCollection(RetryRequestData\RetrySaleCreditCardTransaction $retrySaleCreditCardTransaction = null)
    {
        if ($retrySaleCreditCardTransaction == null){
            $retrySaleCreditCardTransaction = new RetryRequestData\RetrySaleCreditCardTransaction();
        }

        $this->RetrySaleCreditCardTransactionCollection[] = $retrySaleCreditCardTransaction;

        return $retrySaleCreditCardTransaction;
    }
}