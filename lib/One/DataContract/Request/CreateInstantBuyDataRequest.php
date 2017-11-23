<?php

namespace Gateway\One\DataContract\Request;

use Gateway\One\DataContract\Common\BaseObject;
use Gateway\One\DataContract\Common\Address;
use Gateway\One\DataContract\Enum\CreditCardBrandEnum;

/**
 * Class InstantBuyDataRequest
 * @package Gateway\One\DataContract\Request\CreateSaleRequestData
 */
class CreateInstantBuyDataRequest extends BaseObject{
    
    /**
     * @var Address Endereço de cobrança do comprador.
     */
    protected $BillingAddress;
    
    /**
     * @var string Bandeira do cartão do comprador.
     */
    protected $CreditCardBrand;
    
    /**
     * @var string Número do cartão do comprador. Informar apenas os números do cartão.
     */
    protected $CreditCardNumber;
    
    /**
     * @var int Mês de expiração do cartão.
     */
    protected $ExpMonth;
    
    /**
     * @var int Ano de expiração do cartão.
     */
    protected $ExpYear;
    
    /**
     * @var string Nome do portador do cartão.
     */
    protected $HolderName;
    
    /**
     * @var string CPF do portador do cartão.
     */
    protected $HolderDocumentNumber;

    /**
     * @var string 
     */
    protected $IsOneDollarAuthEnabled;
    
    /**
     * @var type string
     */
    protected $BuyerKey;
    
    /**
     * @var string Código de segurança do cartão.
     */
    protected $SecurityCode;
    
    public function __construct()
    {
        $this->BillingAddress = null;
        $this->CreditCardBrand = null;
    }
    
     /**
     * @return Address
     */
    public function getBillingAddress()
    {
        if (empty($this->BillingAddress)){
            $this->BillingAddress = new Address();
        }

        return $this->BillingAddress;
    }
    
    /**
     * @param string $CreditCardBrand
     * @return $this
     */
    public function setCreditCardBrand($CreditCardBrand){
        $this->CreditCardBrand = $CreditCardBrand;
        
        return $this;
    }
    
     /**
     * @return Address
     */
    public function getBuyerKey()
    {
        return $this->BuyerKey;
    }
    
    /**
     * @param string $CreditCardBrand
     * @return $this
     */
    public function setBuyerKey($buyerKey){
        $this->BuyerKey = $buyerKey;
        return $this;
    }
    
    /**
     * @return CreditCardBrandEnum
     */
    public function getCreditCardBrand()
    {
        return $this->CreditCardBrand;
    }
    
     /**
     * @param string $creditCardNumber
     * @return $this
     */
    public function setCreditCardNumber($creditCardNumber)
    {
        $this->CreditCardNumber = $creditCardNumber;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getCreditCardNumber()
    {
        return $this->CreditCardNumber;
    }
    
    /**
     * @param int $expMonth
     * @return $this
     */
    public function setExpMonth($expMonth)
    {
        $this->ExpMonth = $expMonth;

        return $this;
    }
    
    /**
     * @return int
     */
    public function getExpMonth()
    {
        return $this->ExpMonth;
    }
    
    /**
     * @param int $expYear
     * @return $this
     */
    public function setExpYear($expYear)
    {
        $this->ExpYear = $expYear;

        return $this;
    }
    
    /**
     * @return int
     */
    public function getExpYear()
    {
        return $this->ExpYear;
    }
    
     /**
     * @param string $holderName
     * @return $this
     */
    public function setHolderName($holderName)
    {
        $this->HolderName = $holderName;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getHolderName()
    {
        return $this->HolderName;
    }

    /**
     * @param string $holderDocumentNumber
     * @return $this
     */
    public function setHolderDocumentNumber($holderDocumentNumber)
    {
        $this->HolderDocumentNumber = $holderDocumentNumber;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getHolderDocumentNumber()
    {
        return $this->HolderDocumentNumber;
    }
    
    /**
     * @param string $isOneDollarAuthEnabled
     * @return $this
     */
    public function setIsOneDollarAuthEnabled($isOneDollarAuthEnabled)
    {
        $this->IsOneDollarAuthEnabled = $isOneDollarAuthEnabled;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getIsOneDollarAuthEnabled()
    {
        return $this->IsOneDollarAuthEnabled;
    }
    
    /**
     * @param string $securityCode
     * @return $this
     */
    public function setSecurityCode($securityCode)
    {
        $this->SecurityCode = $securityCode;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getSecurityCode()
    {
        return $this->SecurityCode;
    }

}

