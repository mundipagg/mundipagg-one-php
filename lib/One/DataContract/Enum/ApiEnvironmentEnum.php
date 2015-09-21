<?php

namespace MundiPagg\One\DataContract\Enum;

/**
 * Class ApiEnvironmentEnum
 * @package MundiPagg\One\DataContract\Enum
 */
abstract class ApiEnvironmentEnum
{
    /**
     * Ambiente de produção
     */
    const PRODUCTION = 'production';

    /**
     * Ambiente de testes
     */
    const SANDBOX = 'sandbox';

    /**
     * Ambiente de testes
     */
    const INSPECTOR = 'inspector';

    /**
     * Api do transactionReport
     */
    const TRANSACTION_REPORT = 'transaction_report';
}
