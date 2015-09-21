<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 18/09/2015
 * Time: 16:49
 */

namespace MundiPagg\One\DataContract\Enum;


abstract class TransactionReportEnum
{
    /**
     * Header do arquivo
     */
    const HEADER = "01";

    /**
     * Credit card transaction
     */
    const CREDIT_CARD_TRANSACTION = "20";

    /**
     * Boleto Transaction
     */
    const BOLETO_TRANSACTION = "30";

    /**
     * Online Debit Transaction
     */
    const ONLINE_DEBIT_TRANSACTION = "40";

    /**
     * Trailer do Arquivo
     */
    const TRAILER = "99";
}