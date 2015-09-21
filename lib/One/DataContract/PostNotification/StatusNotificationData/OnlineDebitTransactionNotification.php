<?php

namespace MundiPagg\One\DataContract\PostNotification\StatusNotificationData;

/**
 * Class OnlineDebitTransactionNotification
 * @package MundiPagg\One\DataContract\PostNotification\StatusNotificationData
 */
class OnlineDebitTransactionNotification
{
    /*@var [long] Amount in Cents */
    public $AmountInCents;
    /*@var [long] Amount in Cents */
    public $AmountPaidInCents;
    /*@var [DateTime] Status Changed Date */
    public $StatusChangedDate;
    /*@var [string] Transaction Key */
    public $TransactionKey;
    /*@var [string] Transaction Reference */
    public $TransactionReference;
    /*@var [string] Previous Online Debit Transaction Status */
    public $PreviousOnlineDebitTransactionStatus;
    /*@var [string] Online Debit Transaction Status */
    public $OnlineDebitTransactionStatus;
}