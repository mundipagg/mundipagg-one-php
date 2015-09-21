<?php

namespace MundiPagg\One\DataContract\PostNotification\StatusNotificationData;

/**
 * Class CreditCardTransactionNotification
 * @package MundiPagg\One\DataContract\PostNotification\StatusNotificationData
 */
class CreditCardTransactionNotification
{
    /*@var [string] Acquirer name */
    public $Acquirer;
    /*@var [long] Amount In Cents */
    public $AmountInCents;
    /*@var [long] Authorized Amount In Cents */
    public $AuthorizedAmountInCents;
    /*@var [long] Captured Amount In Cents */
    public $CapturedAmountInCents;
    /*@var [string] CreditCard Brand */
    public $CreditCardBrand;
    /*@var [long] Refunded Amount In Cents */
    public $RefundedAmountInCents;
    /*@var [DateTime]Status Changed Date */
    public $StatusChangedDate;
    /*@var [long] Transaction Identifier */
    public $TransactionIdentifier;
    /*@var [string] Transaction Key */
    public $TransactionKey;
    /*@var [string] Transaction Reference */
    public $TransactionReference;
    /*@var [long] Unique Sequential Number */
    public $UniqueSequentialNumber;
    /*@var [long] Transaction Voided Amount in cents */
    public $VoidedAmountInCents;
    /*@var [string] Previus CreditCard Transaction Status */
    public $PreviousCreditCardTransactionStatus;
    /*@var [string] CreditCard Transaction Status */
    public $CreditCardTransactionStatus;
}