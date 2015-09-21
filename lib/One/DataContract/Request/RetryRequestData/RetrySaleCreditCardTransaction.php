<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 14/09/2015
 * Time: 14:54
 */

namespace MundiPagg\One\DataContract\Request\RetryRequestData;

use MundiPagg\One\DataContract\Common\BaseObject;

/**
 * Class RetrySaleCreditCardTransaction
 * @package MundiPagg\One\DataContract\Request\RetryRequestData
 */
class RetrySaleCreditCardTransaction extends BaseObject
{
    /**
     * @var string Código de segurança do cartão de crédito
     */
    protected $SecurityCode;

    /**
     * @var Guid Identificador da transação na plataforma One
     */
    protected $TransactionKey;

    /**
     * @return string
     */
    public function getSecurityCode()
    {
        return $this->SecurityCode;
    }

    /**
     * @return Guid
     */
    public function getTransactionKey()
    {
        return $this->TransactionKey;
    }

    /**
     * @param $securityCode
     * @return $this
     */
    public function setSecurityCode($securityCode)
    {
        $this->SecurityCode = $securityCode;

        return $this;
    }

    /**
     * @param $transactionKey
     * @return $this
     */
    public function setTransactionKey($transactionKey)
    {
        $this->TransactionKey = $transactionKey;

        return $this;
    }
}