<?php
use MundiPagg\ApiClient;

require_once(dirname(__FILE__) . '/../bootstrap.php');

try
{
    ApiClient::setEnvironment(\MundiPagg\One\DataContract\Enum\ApiEnvironmentEnum::SANDBOX);
    //ApiClient::setMerchantKey("be43cb17-3637-44d0-a45e-d68aaee29f47");
    ApiClient::setMerchantKey("8A2DD57F-1ED9-4153-B4CE-69683EFADAD5"); // SANDBOX MERCHANT

    // Cria objeto de solicitação
    $createSaleRequest = new \MundiPagg\One\DataContract\Request\CreateSaleRequest();

    // Dados da transação de cartão de crédito
    $creditCardTransaction = new \MundiPagg\One\DataContract\Request\CreateSaleRequestData\CreditCardTransaction();
    $createSaleRequest->addCreditCardTransaction($creditCardTransaction);
    $creditCardTransaction
        ->setAmountInCents(199)
        ->setInstallmentCount(1)
        ->setCreditCardOperation(\MundiPagg\One\DataContract\Enum\CreditCardOperationEnum::AUTH_ONLY)
        ->setTransactionDateInMerchant(new DateTime())
        ->setTransactionReference(uniqid())
        ->getCreditCard()
        //->setInstantBuyKey("d9ff84f1-67a1-49b3-9a5d-a18d7af6d6ba")
        ->setCreditCardBrand(\MundiPagg\One\DataContract\Enum\CreditCardBrandEnum::MASTERCARD)
        ->setCreditCardNumber("5555444433332222")
        ->setExpMonth(12)
        ->setExpYear(2030)
        ->setHolderName("MUNDIPAGG TESTE")
        ->setSecurityCode("999")
        ->getBillingAddress()
        ->setAddressType(\MundiPagg\One\DataContract\Enum\AddressTypeEnum::BILLING)
        ->setStreet("Rua da Quitanda")
        ->setNumber("199")
        ->setComplement("10º andar")
        ->setDistrict("Centro")
        ->setCity("Rio de Janeiro")
        ->setState("RJ")
        ->setZipCode("20091005")
        ->setCountry(\MundiPagg\One\DataContract\Enum\CountryEnum::BRAZIL)
    ;

    // Options do credit card transaction
    $creditCardTransaction->getOptions()
        ->setCurrencyIso(\MundiPagg\One\DataContract\Enum\CurrencyIsoEnum::BRL)
        ->setCaptureDelayInMinutes(0)
        ->setIataAmountInCents(0)
        ->setInterestRate(0)
        ->setPaymentMethodCode(\MundiPagg\One\DataContract\Enum\PaymentMethodEnum::SIMULATOR)
        ->setSoftDescriptorText("TESTE")
    ;

    // Dados da transação de boleto
    $boletoTransaction = new \MundiPagg\One\DataContract\Request\CreateSaleRequestData\BoletoTransaction();
    $createSaleRequest->addBoletoTransaction($boletoTransaction);
    $boletoTransaction
        ->setAmountInCents(199)
        ->setBankNumber(\MundiPagg\One\DataContract\Enum\BankEnum::ITAU)
        ->setDocumentNumber("1245")
        ->setInstructions("SR. CAIXA, FAVOR NÃO RECEBER APÓS VENCIMENTO.")
        ->setTransactionDateInMerchant(new DateTime())
        ->setTransactionReference(uniqid())
        ->getOptions()
        ->setCurrencyIso(\MundiPagg\One\DataContract\Enum\CurrencyIsoEnum::BRL)
        ->setDaysToAddInBoletoExpirationDate(5)
    ;

    // Endereço de cobrança do comprador no do boleto
    $boletoTransaction->getBillingAddress()
        ->setAddressType(\MundiPagg\One\DataContract\Enum\AddressTypeEnum::BILLING)
        ->setStreet("Rua da Quitanda")
        ->setNumber("199")
        ->setComplement("10º andar")
        ->setDistrict("Centro")
        ->setCity("Rio de Janeiro")
        ->setState("RJ")
        ->setZipCode("20091005")
        ->setCountry(\MundiPagg\One\DataContract\Enum\CountryEnum::BRAZIL)
    ;

    // Dados do comprador
    $createSaleRequest->getBuyer()
        ->setName("Comprador Mundi")
        //->setBuyerKey("61a86e1d-b132-4636-98ec-339cfe493c00")
        ->setPersonType(\MundiPagg\One\DataContract\Enum\PersonTypeEnum::COMPANY)
        ->setBuyerReference("123456")
        ->setBuyerCategory(\MundiPagg\One\DataContract\Enum\BuyerCategoryEnum::PLUS)
        ->setDocumentNumber("58828172000138")
        ->setDocumentType(\MundiPagg\One\DataContract\Enum\DocumentTypeEnum::CNPJ)
        ->setEmail("comprador@mundipagg.com")
        ->setEmailType(\MundiPagg\One\DataContract\Enum\EmailTypeEnum::COMERCIAL)
        ->setGender(\MundiPagg\One\DataContract\Enum\GenderEnum::MALE)
        ->setHomePhone("3003-0460")
        ->setMobilePhone("99999-8888")
        ->setBirthDate(\DateTime::createFromFormat('d/m/Y', '11/05/1990'))
        ->setFacebookId("1234567890")
        ->setTwitterId("1234567890")
        ->setCreateDateInMerchant(new \DateTime())
        ->setLastBuyerUpdateInMerchant(new \DateTime())
        ->addAddress()
        ->setAddressType(\MundiPagg\One\DataContract\Enum\AddressTypeEnum::COMMERCIAL)
        ->setStreet("Rua da Quitanda")
        ->setNumber("199")
        ->setComplement("10º andar")
        ->setDistrict("Centro")
        ->setCity("Rio de Janeiro")
        ->setState("RJ")
        ->setZipCode("20091005")
        ->setCountry(\MundiPagg\One\DataContract\Enum\CountryEnum::BRAZIL)
    ;

    $createSaleRequest->getMerchant()
        ->setMerchantReference("MUNDIPAGG LOJA 1")
    ;

    $createSaleRequest->getOptions()
        ->disableAntiFraud()
        ->setAntiFraudServiceCode("123")
        ->setCurrencyIso(\MundiPagg\One\DataContract\Enum\CurrencyIsoEnum::BRL)
        ->setRetries(3)
    ;

    $createSaleRequest->getOrder()
        ->setOrderReference(uniqid())
    ;

    $createSaleRequest->getRequestData()
        ->setEcommerceCategory(\MundiPagg\One\DataContract\Enum\EcommerceCategoryEnum::B2B)
        ->setIpAddress("255.255.255.255")
        ->setOrigin("123")
        ->setSessionId(uniqid())
    ;

    // Carrinho de compras
    $shoppingCart = $createSaleRequest->addShoppingCart();
    $shoppingCart->setDeliveryDeadline(new DateTime());
    $shoppingCart->setEstimatedDeliveryDate(new DateTime());
    $shoppingCart->setFreightCostInCents(199);
    $shoppingCart->setShippingCompany("Correios");
    $shoppingCart->getDeliveryAddress()
        ->setAddressType(\MundiPagg\One\DataContract\Enum\AddressTypeEnum::SHIPPING)
        ->setStreet("Rua da Quitanda")
        ->setNumber("199")
        ->setComplement("10º andar")
        ->setDistrict("Centro")
        ->setCity("Rio de Janeiro")
        ->setState("RJ")
        ->setZipCode("20091005")
        ->setCountry(\MundiPagg\One\DataContract\Enum\CountryEnum::BRAZIL)
    ;

    $shoppingCart->addShoppingCartItem()
        ->setDescription("Apple iPhone 5s 16gb")
        ->setDiscountAmountInCents(20000)
        ->setItemReference("AI5S")
        ->setName("iPhone 5S")
        ->setQuantity(1)
        ->setUnitCostInCents(1800)
        ->setTotalCostInCents(1600)
    ;

    $shoppingCart->addShoppingCartItem()
        ->setDescription("TESTE")
        ->setDiscountAmountInCents(0)
        ->setItemReference("TESTE")
        ->setName("TESTE")
        ->setQuantity(2)
        ->setUnitCostInCents(1099)
        ->setTotalCostInCents(2198)
    ;

    // Cria um objeto ApiClient
    $apiClient = new MundiPagg\ApiClient();

    // Faz a chamada para criação do token
    //$createSaleResponse = $apiClient->createSale($createSaleRequest);


    //Criando Requisição do Retry
    $retryRequest = new \MundiPagg\One\DataContract\Request\RetryRequest();

    // Dados da transação de cartão de crédito
    $retryRequest->setOrderKey('32F1A28C-7D3F-4874-A896-8D313861F97D');
    $creditCardTransaction = new \MundiPagg\One\DataContract\Request\RetryRequestData\RetrySaleCreditCardTransaction();
    $creditCardTransaction->setSecurityCode('999');
    $creditCardTransaction->setTransactionKey('A2C49022-42E8-4F1B-B069-E5BDBBCE16A8');


    $retryRequest->addRetrySaleCreditCardTransactionCollection($creditCardTransaction);



    //$response = $apiClient->Retry($retryRequest);

    //$response = $apiClient->searchSaleByCreditCardTransactionKey("5CD6FFEF-184C-4343-BD45-5610DC8A6D4D"); //Requisição do Query

    //$response = $apiClient->createSale($createSaleRequest); //Requisição do CreateSale

    //print "<pre>";
    //    print var_dump($response->getData());
    //print "</pre>";

    // Imprime json
    //print "<pre>";
    //echo json_encode($createSaleRequest->getData(), JSON_PRETTY_PRINT);
    //print "</pre>";
}
catch (\MundiPagg\One\DataContract\Report\ApiError $error)
{
    echo $error;
    // Imprime json
    echo "<pre>";
    echo json_encode($error, JSON_PRETTY_PRINT);
    echo "</pre>";
}
catch (Exception $ex)
{
    echo $ex;
    // Imprime json
    echo "<pre>";
    echo json_encode($ex, JSON_PRETTY_PRINT);
    echo "</pre>";
}
?>