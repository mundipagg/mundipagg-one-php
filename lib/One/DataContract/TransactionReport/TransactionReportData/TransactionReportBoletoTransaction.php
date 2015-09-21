<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 18/09/2015
 * Time: 18:17
 */

namespace MundiPagg\One\DataContract\TransactionReport\TransactionReportData;


class TransactionReportBoletoTransaction
{
    protected $Order;

    protected $TransactionKey;

    protected $TransactionReference;

    protected $Status;

    protected $NossoNumero;

    protected $BankNumber;

    protected $Agency;

    protected $Account;

    protected $BarCode;

    protected $ExpirationDate;

    protected $AmountInCents;

    protected $AmountPaidInCents;

    protected $PaymentDate;

    protected $CreditDate;

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

    public function getStatus()
    {
        return $this->Status;
    }

    public function setStatus($Status)
    {
        $this->Status = $Status;

        return $this;
    }

    public function getNossoNumero()
    {
        return $this->NossoNumero;
    }

    public function setNossoNumero($NossoNumero)
    {
        $this->NossoNumero = $NossoNumero;

        return $this;
    }

    public function getBankNumber()
    {
        return $this->BankNumber;
    }

    public function setBankNumber($BankNumber)
    {
        $this->BankNumber = $BankNumber;

        return $this;
    }

    public function getAgency()
    {
        return $this->Agency;
    }

    public function setAgency($Agency)
    {
        $this->Agency = $Agency;

        return $this;
    }

    public function getAccount()
    {
        return $this->Account;
    }

    public function setAccount($Account)
    {
        $this->Account = $Account;

        return $this;
    }

    public function getBarCode()
    {
        return $this->BarCode;
    }

    public function setBarCode($BarCode)
    {
        $this->BarCode = $BarCode;

        return $this;
    }

    public function getExpirationDate()
    {
        return $this->ExpirationDate;
    }

    public function setExpirationDate($ExpirationDate)
    {
        $this->ExpirationDate = $ExpirationDate;

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

    public function getAmountPaidInCents()
    {
        return $this->AmountPaidInCents;
    }

    public function setAmountPaidInCents($AmountPaidInCents)
    {
        $this->AmountPaidInCents = $AmountPaidInCents;

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

    public function getCreditDate()
    {
        return $this->CreditDate;
    }

    public function setCreditDate($CreditDate)
    {
        $this->CreditDate = $CreditDate;

        return $this;
    }

}