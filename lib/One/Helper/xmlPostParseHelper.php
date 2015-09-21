<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 18/09/2015
 * Time: 14:30
 */

namespace MundiPagg\One\Helper;

use MundiPagg\One\DataContract\PostNotification\StatusNotification;
use MundiPagg\One\DataContract\PostNotification\StatusNotificationData\BoletoTransactionNotification;
use MundiPagg\One\DataContract\PostNotification\StatusNotificationData\CreditCardTransactionNotification;
use MundiPagg\One\DataContract\PostNotification\StatusNotificationData\OnlineDebitTransactionNotification;

class xmlPostParseHelper
{
    public static function ParseFromXml($xmlString) {
        if (is_null($xmlString)) { return null; }
        if (is_string($xmlString) == false) { return null; }

        $xml = simplexml_load_string($xmlString); // Cria o objeto do Xml

        $xml->AmountPaidInCents = null;

        $statusNotification = new StatusNotification();

        $statusNotification->AmountInCents = (int)$xml->AmountInCents;
        if (isset($xml->AmountPaidInCents) && ((string)$xml->AmountPaidInCents) != '') { $statusNotification->AmountPaidInCents = (int)$xml->AmountPaidInCents; }
        if (isset($xml->OrderKey) && ((string)$xml->OrderKey) != '') { $statusNotification->OrderKey = (string)$xml->OrderKey; }
        $statusNotification->OrderReference = (string)$xml->OrderReference;
        $statusNotification->OrderStatus = (string)$xml->OrderStatus;

        if (isset($xml->BoletoTransaction)) {
            $xmlBoleto = $xml->BoletoTransaction;
            $boletoTrans = null;
            if (!xmlPostParseHelper::IsNullOrEmptyXml($xmlBoleto)) {
                $boletoTrans = new BoletoTransactionNotification();

                $boletoTrans->AmountInCents = (int)$xmlBoleto->AmountInCents;
                if (isset($xmlBoleto->AmountPaidInCents) && !xmlPostParseHelper::IsNullOrEmptyXml($xmlBoleto->AmountPaidInCents)) { $boletoTrans->AmountPaidInCents = (int)$xmlBoleto->AmountPaidInCents; }
                $boletoTrans->BoletoExpirationDate = (string)$xmlBoleto->BoletoExpirationDate;
                $boletoTrans->NossoNumero = (string)$xmlBoleto->NossoNumero;
                $boletoTrans->StatusChangedDate = (string)$xmlBoleto->StatusChangedDate;
                if (isset($xmlBoleto->TransactionKey) && !xmlPostParseHelper::IsNullOrEmptyXml($xmlBoleto->TransactionKey)) { $boletoTrans->TransactionKey = (string)$xmlBoleto->TransactionKey; }

                $boletoTrans->TransactionReference = (string)$xmlBoleto->TransactionReference;
                $boletoTrans->PreviousBoletoTransactionStatus = (string)$xmlBoleto->PreviousBoletoTransactionStatus;
                $boletoTrans->BoletoTransactionStatus = (string)$xmlBoleto->BoletoTransactionStatus;
            }

            $statusNotification->BoletoTransaction = $boletoTrans;
        }

        if (isset($xml->CreditCardTransaction)) {
            $xmlCC = $xml->CreditCardTransaction;
            $ccTrans = null;
            if (!xmlPostParseHelper::IsNullOrEmptyXml($xmlCC) ) {
                $ccTrans = new CreditCardTransactionNotification();

                $ccTrans->Acquirer = (string)$xmlCC->Acquirer;
                if (isset($xmlCC->AmountInCents) && !xmlPostParseHelper::IsNullOrEmptyXml($xmlCC->AmountInCents)) { $ccTrans->AmountInCents = (int)$xmlCC->AmountInCents; }
                if (isset($xmlCC->AuthorizedAmountInCents) && !xmlPostParseHelper::IsNullOrEmptyXml($xmlCC->AuthorizedAmountInCents)) { $ccTrans->AuthorizedAmountInCents = (int)$xmlCC->AuthorizedAmountInCents; }
                if (isset($xmlCC->CapturedAmountInCents) && !xmlPostParseHelper::IsNullOrEmptyXml($xmlCC->CapturedAmountInCents)) { $ccTrans->CapturedAmountInCents = (int)$xmlCC->CapturedAmountInCents; }
                $ccTrans->CreditCardBrand = (string)$xmlCC->CreditCardBrand;
                if (isset($xmlCC->RefundedAmountInCents) && !xmlPostParseHelper::IsNullOrEmptyXml($xmlCC->RefundedAmountInCents)) { $ccTrans->RefundedAmountInCents = (int)$xmlCC->RefundedAmountInCents; }
                $ccTrans->StatusChangedDate = (string)$xmlCC->StatusChangedDate;
                $ccTrans->TransactionIdentifier = (string)$xmlCC->TransactionIdentifier;
                $ccTrans->TransactionKey = (string)$xmlCC->TransactionKey;
                $ccTrans->TransactionReference = (string)$xmlCC->TransactionReference;
                $ccTrans->UniqueSequentialNumber = (string)$xmlCC->UniqueSequentialNumber;
                if (isset($xmlCC->VoidedAmountInCents) && !xmlPostParseHelper::IsNullOrEmptyXml($xmlCC->VoidedAmountInCents)) { $ccTrans->VoidedAmountInCents = (int)$xmlCC->VoidedAmountInCents; }
                $ccTrans->PreviousCreditCardTransactionStatus = (string)$xmlCC->PreviousCreditCardTransactionStatus;
                $ccTrans->CreditCardTransactionStatus = (string)$xmlCC->CreditCardTransactionStatus;
            }

            $statusNotification->CreditCardTransaction = $ccTrans;
        }

        if (isset($xml->OnlineDebitTransaction)) {
            $xmlOnlineDebit = $xml->OnlineDebitTransaction;
            $onlineDebitTrans = null;
            if (!xmlPostParseHelper::IsNullOrEmptyXml($xmlOnlineDebit)) {
                $onlineDebitTrans = new OnlineDebitTransactionNotification();

                if (isset($xmlOnlineDebit->AmountInCents) && !xmlPostParseHelper::IsNullOrEmptyXml($xmlOnlineDebit->AmountInCents)) { $onlineDebitTrans->AmountInCents = (int)$xmlOnlineDebit->AmountInCents; }
                if (isset($xmlOnlineDebit->AmountPaidInCents) && !xmlPostParseHelper::IsNullOrEmptyXml($xmlOnlineDebit->AmountPaidInCents)) { $onlineDebitTrans->AmountPaidInCents = (int)$xmlOnlineDebit->AmountPaidInCents; }
                $onlineDebitTrans->StatusChangedDate = (string)$xmlOnlineDebit->StatusChangedDate;
                $onlineDebitTrans->TransactionKey = (string)$xmlOnlineDebit->TransactionKey;
                $onlineDebitTrans->TransactionReference = (string)$xmlOnlineDebit->TransactionReference;
                $onlineDebitTrans->PreviousOnlineDebitTransactionStatus = (string)$xmlOnlineDebit->PreviousCreditCardTransactionStatus;
                $onlineDebitTrans->OnlineDebitTransactionStatus = (string)$xmlOnlineDebit->CreditCardTransactionStatus;
            }

            $statusNotification->OnlineDebitTransaction = $onlineDebitTrans;
        }

        return $statusNotification;
    }

    private static function IsNullOrEmptyXml($xml) {
        if (is_null($xml)) { return true; }
        if ((string)$xml == '') { return true; }
        return false;
    }

}