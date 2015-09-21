<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 18/09/2015
 * Time: 17:06
 */

namespace MundiPagg\One\DataContract\TransactionReport\TransactionReportData;

/**
 * Class OnlineDebitTransaction
 * @package MundiPagg\One\DataContract\TransactionReport\TransactionReportData
 */
class OnlineDebitTransaction
{
    protected $Order;

    protected $TransactionKey;

    protected $TransactionReference;

    protected $Bank;

    protected $Status;

    protected $AmountInCents;

    protected $TransactionKeyToBank;

    protected $AmountPaidInCents;

    protected $Signature;

    protected $PaymentDate;

    protected $BankReturnCode;

    protected $BankPaymentDate;

    public function getOrder()
    {
        return $this->Order;
    }

    public function setOrder($Order)
    {
        $this->Order = $Order;

        return $this;
    }

    public function getTransactionKey()
    {
        return $this->TransactionKey;
    }

    public function setTransactionKey($TransactionKey)
    {
        $this->TransactionKey = $TransactionKey;

        return $this;
    }

    public function getTransactionReference()
    {
        return $this->TransactionReference;
    }

    public function setTransactionReference($TransactionReference)
    {
        $this->TransactionReference = $TransactionReference;

        return $this;
    }

    public function getBank()
    {
        return $this->Bank;
    }

    public function setBank($Bank)
    {
        $this->Bank = $Bank;

        return $this;
    }

    public function getStatus()
    {
        return $this->Status;
    }

    public function setStatus($Status)
    {
        $this->Status = $Status;

        return $this;
    }

    public function getAmountInCents()
    {
        return $this->AmountInCents;
    }

    public function setAmountInCents($AmountInCents)
    {
        $this->AmountInCents = $AmountInCents;

        return $this;
    }

    public function getTransactionKeyToBank()
    {
        return $this->TransactionKeyToBank;
    }

    public function setTransactionKeyToBank($TransactionKeyToBank)
    {
        $this->TransactionKeyToBank = $TransactionKeyToBank;

        return $this;
    }

    public function getAmountPaidInCents()
    {
        return $this->AmountPaidInCents;
    }

    public function setAmountPaidInCents($AmountPaidInCents)
    {
        $this->AmountPaidInCents = $AmountPaidInCents;

        return $this;
    }

    public function getSignature()
    {
        return $this->Signature;
    }

    public function setSignature($Signature)
    {
        $this->Signature = $Signature;

        return $this;
    }

    public function getPaymentDate()
    {
        return $this->PaymentDate;
    }

    public function setPaymentDate($PaymentDate)
    {
        $this->PaymentDate = $PaymentDate;

        return $this;
    }

    public function getBankReturnCode()
    {
        return $this->BankReturnCode;
    }

    public function setBankReturnCode($BankReturnCode)
    {
        $this->BankReturnCode = $BankReturnCode;

        return $this;
    }

    public function getBankPaymentDate()
    {
        return $this->BankPaymentDate;
    }

    public function setBankPaymentDate($BankPaymentDate)
    {
        $this->BankPaymentDate = $BankPaymentDate;

        return $this;
    }
}