<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 18/09/2015
 * Time: 16:55
 */

namespace MundiPagg\One\DataContract\TransactionReport;


class TransactionReport
{
    /**
     * @var TransactionReportData\Header
     */
    protected $Header;

    /**
     * @var TransactionReportData\Trailer
     */
    protected $Trailer;

    /**
     * @var TransactionReportData\TransactionReportBoletoTransaction Transacao do tipo Boleto
     */
    protected $BoletoTransactionCollection;

    /**
     * @var TransactionReportData\TransactionReportCreditCardTransaction Transacao do tipo Cartao de Credito
     */
    protected $CreditCardTransactionCollection;

    /**
     * @var TransactionReportData\OnlineDebitTransaction Transacao do tipo Online Debit
     */
    protected $OnlineDebitTransactionCollection;

    /**
     * @return TransactionReportData\TransactionReportCreditCardTransaction[]
     */
    public function getCreditCardTransactionCollection()
    {
        if (empty($this->CreditCardTransactionCollection)) {
            $this->CreditCardTransactionCollection = array();
        }

        return $this->CreditCardTransactionCollection;
    }

    /**
     * @param TransactionReportData\TransactionReportCreditCardTransaction $creditCardTransaction
     * @return TransactionReportData\TransactionReportCreditCardTransaction
     */
    public function addCreditCardTransaction(TransactionReportData\TransactionReportCreditCardTransaction $creditCardTransaction = null)
    {
        if ($creditCardTransaction == null) {
            $creditCardTransaction = new TransactionReportData\TransactionReportCreditCardTransaction();
        }

        $this->CreditCardTransactionCollection[] = $creditCardTransaction;

        return $creditCardTransaction;
    }

    /**
     * @return TransactionReportData\TransactionReportBoletoTransaction[]
     */
    public function getBoletoTransactionCollection()
    {
        if (empty($this->BoletoTransactionCollection)) {
            $this->BoletoTransactionCollection = array();
        }

        return $this->BoletoTransactionCollection;
    }

    /**
     * @param TransactionReportData\TransactionReportBoletoTransaction $boletoTransaction
     * @return TransactionReportData\TransactionReportBoletoTransaction
     */
    public function addBoletoTransaction(TransactionReportData\TransactionReportBoletoTransaction $boletoTransaction = null)
    {
        if ($boletoTransaction == null) {
            $boletoTransaction = new TransactionReportData\TransactionReportBoletoTransaction();
        }

        $this->CreditCardTransactionCollection[] = $boletoTransaction;

        return $boletoTransaction;
    }

    /**
     * @return TransactionReportData\$OnlineDebitTransactionCollection[]
     */
    public function getOnlineDebitCollection()
    {
        if (empty($this->OnlineDebitTransactionCollection)) {
            $this->OnlineDebitTransactionCollection = array();
        }

        return $this->OnlineDebitTransactionCollection;
    }

    /**
     * @param TransactionReportData\OnlineDebitTransaction $onlineDebitTransaction
     * @return TransactionReportData\OnlineDebitTransaction
     */
    public function addOnlineDebitTransaction(TransactionReportData\OnlineDebitTransaction $onlineDebitTransaction = null)
    {
        if ($onlineDebitTransaction == null) {
            $onlineDebitTransaction = new TransactionReportData\OnlineDebitTransaction();
        }

        $this->CreditCardTransactionCollection[] = $onlineDebitTransaction;

        return $onlineDebitTransaction;
    }

    /**
     * @return TransactionReportData\Header
     */
    public function getHeader()
    {
        return $this->Header;
    }

    /**
     * @param $Header
     * @return $this
     */
    public function setHeader($Header)
    {
        $this->Header = $Header;

        return $this;
    }

    /**
     * @return TransactionReportData\Trailer
     */
    public function getTrailer()
    {
        return $this->Trailer;
    }

    /**
     * @param $Trailer
     * @return $this
     */
    public function setTrailer($Trailer)
    {
        $this->Trailer = $Trailer;

        return $this;
    }
}