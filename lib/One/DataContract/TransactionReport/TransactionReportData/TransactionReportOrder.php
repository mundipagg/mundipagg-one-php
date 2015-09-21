<?php
/**
 * Created by PhpStorm.
 * User: Felippe
 * Date: 18/09/2015
 * Time: 17:59
 */

namespace MundiPagg\One\DataContract\TransactionReport\TransactionReportData;


class TransactionReportOrder
{
    protected $MerchantKey;

    protected $MerchantName;

    protected $OrderKey;

    protected $OrderReference;

    public function getMerchantKey()
    {
        return $this->MerchantKey;
    }

    public function setMerchantKey($MerchantKey)
    {
        $this->MerchantKey = $MerchantKey;

        return $this;
    }

    public function getMerchantName()
    {
        return $this->MerchantName;
    }

    public function setMerchantName($MerchantName)
    {
        $this->MerchantName = $MerchantName;

        return $this;
    }

    public function getOrderKey()
    {
        return $this->OrderKey;
    }

    public function setOrderKey($OrderKey)
    {
        $this->OrderKey = $OrderKey;

        return $this;
    }

    public function getOrderReference()
    {
        return $this->OrderReference;
    }

    public function setOrderReference($OrderReference)
    {
        $this->OrderReference = $OrderReference;

        return $this;
    }
}