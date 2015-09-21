<?php

namespace MundiPagg\One\DataContract\PostNotification;
use MundiPagg\One\DataContract\Enum\OrderStatusEnum;

/**
 * Class StatusNotification
 * @package MundiPagg\One\DataContract\PostNotification
 */
class StatusNotification
{
    /*@var [long] Amount In Cents */
    public $AmountInCents;
    /*@var [long] Amount Paid In Cents */
    public $AmountPaidInCents;
    /*@var [BoletoTransactionNotification] Boleto Transaction */
    public $BoletoTransaction;
    /*@var [CreditCardTransactionNotification] Credit Card Transaction */
    public $CreditCardTransaction;
    /*@var [OnlineDebitTransactionNotification] Online Debit Transaction */
    public $OnlineDebitTransaction;
    /*@var [string] Order Key */
    public $OrderKey;
    /*@var [string] Order Reference */
    public $OrderReference;
    /*@var [string] Order Status */
    public $OrderStatus;

    function __construct()
    {
        $this->AmountInCents = 0;
        $this->AmountPaidInCents = null;
        $this->BoletoTransaction = null;
        $this->CreditCardTransaction = null;
        $this->OrderKey = null;
        $this->OrderReference = "";
        $this->OrderStatus = OrderStatusEnum::__default;
    }
}