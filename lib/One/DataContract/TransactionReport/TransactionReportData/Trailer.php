<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 18/09/2015
 * Time: 17:17
 */

namespace MundiPagg\One\DataContract\TransactionReport\TransactionReportData;

/**
 * Class Trailer
 * @package MundiPagg\One\DataContract\TransactionReport\TransactionReportData
 */
class Trailer
{
    protected $OrderDataCount;

    protected $BoletoTransactionDataCount;

    protected $CreditCardTransactionDataCount;

    protected $OnlineDebitTransactionDataCount;

    public function getOrderDataCount()
    {
        return $this->OrderDataCount;
    }

    public function getBoletoTransactionDataCount()
    {
        return $this->BoletoTransactionDataCount;
    }

    public function getCreditCardTransactionDataCount()
    {
        return $this->CreditCardTransactionDataCount;
    }

    public function getOnlineDebitTransactionDataCount()
    {
        return $this->OnlineDebitTransactionDataCount;
    }

    public function setOrderDataCount($OrderDataCount)
    {
        $this->OrderDataCount = $OrderDataCount;

        return $this;
    }

    public function setBoletoTransactionDataCount($BoletoTransactionDataCount)
    {
        $this->BoletoTransactionDataCount = $BoletoTransactionDataCount;

        return $this;
    }

    public function setCreditCardTransactionDataCount($CreditCardTransactionDataCount)
    {
        $this->CreditCardTransactionDataCount = $CreditCardTransactionDataCount;

        return $this;
    }

    public function setOnlineDebitTransactionDataCount($OnlineDebitTransactionDataCount)
    {
        $this->OnlineDebitTransactionDataCount = $OnlineDebitTransactionDataCount;

        return $this;
    }
}