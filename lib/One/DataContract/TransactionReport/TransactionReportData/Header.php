<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 18/09/2015
 * Time: 17:11
 */

namespace MundiPagg\One\DataContract\TransactionReport\TransactionReportData;

/**
 * Class Header
 * @package MundiPagg\One\DataContract\TransactionReport\TransactionReportData
 */
class Header
{
    protected $TransactionProcessedDate;

    protected $ReportFileCreateDate;

    protected $Version;

    /**
     * @return string
     */
    public function getTransactionProcessedDate()
    {
        return $this->TransactionProcessedDate;
    }

    /**
     * @param string $TransactionProcessedDate
     * @return $this
     */
    public function setTransactionProcessedDate($TransactionProcessedDate)
    {
        $this->TransactionProcessedDate = $TransactionProcessedDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getReportFileCreateDate()
    {
        return $this->ReportFileCreateDate;
    }

    /**
     * @param string $ReportFileCreateDate
     * @return $this
     */
    public function setReportFileCreateDate($ReportFileCreateDate)
    {
        $this->ReportFileCreateDate = $ReportFileCreateDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->Version;
    }

    /**
     * @param string $Version
     * @return $this
     */
    public function setVersion($Version)
    {
        $this->Version = $Version;

        return $this;
    }
}