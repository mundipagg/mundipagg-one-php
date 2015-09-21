<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 18/09/2015
 * Time: 18:24
 */

namespace MundiPagg\One\DataContract\TransactionReport\TransactionReportData;


class TransactionReportCreditCardTransaction
{
    protected $Order;

    protected $TransactionKey;

    protected $TransactionKeyToAcquirer;

    protected $CreditCardTransactionReference;

    protected $CreditCardBrand;

    protected $CreditCardNumber;

    protected $InstallmentCount;

    protected $AcquirerName;

    protected $Status;

    protected $AmountInCents;

    protected $IataAmountInCents;

    protected $AuthorizationCode;

    protected $TransactionIdentifier;

    protected $UniqueSequentialNumber;

    protected $AuthorizedAmountInCents;

    protected $CapturedAmountInCents;

    protected $VoidedAmountInCents;

    protected $RefundedAmountInCents;

    protected $CapturedDate;

    protected $AuthorizedDate;

    protected $VoidedDate;

    protected $LastProbeDate;

    protected $AcquirerAuthorizationReturnCode;

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

    public function getTransactionKeyToAcquirer()
    {
        return $this->TransactionKeyToAcquirer;
    }

    public function setTransactionKeyToAcquirer($TransactionKeyToAcquirer)
    {
        $this->TransactionKeyToAcquirer = $TransactionKeyToAcquirer;

        return $this;
    }

    public function getCreditCardTransactionReference()
    {
        return $this->CreditCardTransactionReference;
    }

    public function setCreditCardTransactionReference($CreditCardTransactionReference)
    {
        $this->CreditCardTransactionReference = $CreditCardTransactionReference;

        return $this;
    }

    public function getCreditCardBrand()
    {
        return $this->CreditCardBrand;
    }

    public function setCreditCardBrand($CreditCardBrand)
    {
        $this->CreditCardBrand = $CreditCardBrand;

        return $this;
    }

    public function getCreditCardNumber()
    {
        return $this->CreditCardNumber;
    }

    public function setCreditCardNumber($CreditCardNumber)
    {
        $this->CreditCardNumber = $CreditCardNumber;

        return $this;
    }

    public function getInstallmentCount()
    {
        return $this->InstallmentCount;
    }

    public function setInstallmentCount($InstallmentCount)
    {
        $this->InstallmentCount = $InstallmentCount;

        return $this;
    }

    public function getAcquirerName()
    {
        return $this->AcquirerName;
    }

    public function setAcquirerName($AcquirerName)
    {
        $this->AcquirerName = $AcquirerName;

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

    public function getIataAmountInCents()
    {
        return $this->IataAmountInCents;
    }

    public function setIataAmountInCents($IataAmountInCents)
    {
        $this->IataAmountInCents = $IataAmountInCents;

        return $this;
    }

    public function getAuthorizationCode()
    {
        return $this->AuthorizationCode;
    }

    public function setAuthorizationCode($AuthorizationCode)
    {
        $this->AuthorizationCode = $AuthorizationCode;

        return $this;
    }

    public function getTransactionIdentifier()
    {
        return $this->TransactionIdentifier;
    }

    public function setTransactionIdentifier($TransactionIdentifier)
    {
        $this->TransactionIdentifier = $TransactionIdentifier;

        return $this;
    }

    public function getUniqueSequentialNumber()
    {
        return $this->UniqueSequentialNumber;
    }

    public function setUniqueSequentialNumber($UniqueSequentialNumber)
    {
        $this->UniqueSequentialNumber = $UniqueSequentialNumber;

        return $this;
    }

    public function getAuthorizedAmountInCents()
    {
        return $this->AuthorizedAmountInCents;
    }

    public function setAuthorizedAmountInCents($AuthorizedAmountInCents)
    {
        $this->AuthorizedAmountInCents = $AuthorizedAmountInCents;

        return $this;
    }

    public function getCapturedAmountInCents()
    {
        return $this->CapturedAmountInCents;
    }

    public function setCapturedAmountInCents($CapturedAmountInCents)
    {
        $this->CapturedAmountInCents = $CapturedAmountInCents;

        return $this;
    }

    public function getVoidedAmountInCents()
    {
        return $this->VoidedAmountInCents;
    }

    public function setVoidedAmountInCents($VoidedAmountInCents)
    {
        $this->VoidedAmountInCents = $VoidedAmountInCents;

        return $this;
    }

    public function getRefundedAmountInCents()
    {
        return $this->RefundedAmountInCents;
    }

    public function setRefundedAmountInCents($RefundedAmountInCents)
    {
        $this->RefundedAmountInCents = $RefundedAmountInCents;

        return $this;
    }

    public function getCapturedDate()
    {
        return $this->CapturedDate;
    }

    public function setCapturedDate($CapturedDate)
    {
        $this->CapturedDate = $CapturedDate;

        return $this;
    }

    public function getAuthorizedDate()
    {
        return $this->AuthorizedDate;
    }

    public function setAuthorizedDate($AuthorizedDate)
    {
        $this->AuthorizedDate = $AuthorizedDate;

        return $this;
    }

    public function getVoidedDate()
    {
        return $this->VoidedDate;
    }

    public function setVoidedDate($VoidedDate)
    {
        $this->VoidedDate = $VoidedDate;

        return $this;
    }

    public function getLastProbeDate()
    {
        return $this->LastProbeDate;
    }

    public function setLastProbeDate($LastProbeDate)
    {
        $this->LastProbeDate = $LastProbeDate;

        return $this;
    }

    public function getAcquirerAuthorizationReturnCode()
    {
        return $this->AcquirerAuthorizationReturnCode;
    }

    public function setAcquirerAuthorizationReturnCode($AcquirerAuthorizationReturnCode)
    {
        $this->AcquirerAuthorizationReturnCode = $AcquirerAuthorizationReturnCode;

        return $this;
    }
}