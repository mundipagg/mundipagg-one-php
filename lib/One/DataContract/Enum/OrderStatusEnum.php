<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 17/09/2015
 * Time: 15:29
 */

namespace MundiPagg\One\DataContract\Enum;

/**
 * Class OrderStatusEnum
 * @package MundiPagg\One\DataContract\Enum
 */
abstract class OrderStatusEnum
{
    const __default = OrderStatusEnum::Opened;

    const Opened = "Opened"; //1;
    const Closed = 'Closed'; //2;
    const Paid = 'Paid'; //3;
    const Overpaid = 'Overpaid'; //4;
    const Canceled = 'Canceled'; //5;
    const PartialPaid = 'PartialPaid'; //6;
    const WithError = 'WithError'; //7;
}