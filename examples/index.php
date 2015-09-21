<?php
use MundiPagg\ApiClient;

require_once(dirname(__FILE__) . '/../bootstrap.php');

try {
    //ApiClient::setEnvironment(\MundiPagg\One\DataContract\Enum\ApiEnvironmentEnum::SANDBOX);
    ApiClient::setMerchantKey("be43cb17-3637-44d0-a45e-d68aaee29f47");
    //ApiClient::setMerchantKey("8A2DD57F-1ED9-4153-B4CE-69683EFADAD5"); // SANDBOX MERCHANT
    //ApiClient::setMerchantKey("41BE3484-9CD4-4332-98B1-145DAEBE7CCB"); // SANDBOX MERCHANT APIV1

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
        ->setCountry(\MundiPagg\One\DataContract\Enum\CountryEnum::BRAZIL);

    // Options do credit card transaction
    $creditCardTransaction->getOptions()
        ->setCurrencyIso(\MundiPagg\One\DataContract\Enum\CurrencyIsoEnum::BRL)
        ->setCaptureDelayInMinutes(0)
        ->setIataAmountInCents(0)
        ->setInterestRate(0)
        ->setPaymentMethodCode(\MundiPagg\One\DataContract\Enum\PaymentMethodEnum::SIMULATOR)
        ->setSoftDescriptorText("TESTE");

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
        ->setDaysToAddInBoletoExpirationDate(5);

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
        ->setCountry(\MundiPagg\One\DataContract\Enum\CountryEnum::BRAZIL);

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
        ->setCountry(\MundiPagg\One\DataContract\Enum\CountryEnum::BRAZIL);

    $createSaleRequest->getMerchant()
        ->setMerchantReference("MUNDIPAGG LOJA 1");

    $createSaleRequest->getOptions()
        ->disableAntiFraud()
        ->setAntiFraudServiceCode("123")
        ->setCurrencyIso(\MundiPagg\One\DataContract\Enum\CurrencyIsoEnum::BRL)
        ->setRetries(3);

    $createSaleRequest->getOrder()
        ->setOrderReference(uniqid());

    $createSaleRequest->getRequestData()
        ->setEcommerceCategory(\MundiPagg\One\DataContract\Enum\EcommerceCategoryEnum::B2B)
        ->setIpAddress("255.255.255.255")
        ->setOrigin("123")
        ->setSessionId(uniqid());

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
        ->setCountry(\MundiPagg\One\DataContract\Enum\CountryEnum::BRAZIL);

    $shoppingCart->addShoppingCartItem()
        ->setDescription("Apple iPhone 5s 16gb")
        ->setDiscountAmountInCents(20000)
        ->setItemReference("AI5S")
        ->setName("iPhone 5S")
        ->setQuantity(1)
        ->setUnitCostInCents(1800)
        ->setTotalCostInCents(1600);

    $shoppingCart->addShoppingCartItem()
        ->setDescription("TESTE")
        ->setDiscountAmountInCents(0)
        ->setItemReference("TESTE")
        ->setName("TESTE")
        ->setQuantity(2)
        ->setUnitCostInCents(1099)
        ->setTotalCostInCents(2198);

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


    $xmlToPost = utf8_encode(utf8_encode('<StatusNotification xmlns="http://schemas.datacontract.org/2004/07/MundiPagg.NotificationService.DataContract"
                    xmlns:i="http://www.w3.org/2001/XMLSchema-instance"
                    i:schemaLocation="http://schemas.datacontract.org/2004/07/MundiPagg.NotificationService.DataContract StatusNotificationXmlSchema.xsd">
  <AmountInCents>500</AmountInCents>
  <AmountPaidInCents>0</AmountPaidInCents>
  <BoletoTransaction>
    <AmountInCents>500</AmountInCents>
    <AmountPaidInCents>0</AmountPaidInCents>
    <BoletoExpirationDate>2013-02-08T00:00:00</BoletoExpirationDate>
    <NossoNumero>0123456789</NossoNumero>
    <StatusChangedDate>2012-11-06T08:55:49.753</StatusChangedDate>
    <TransactionKey>4111D523-9A83-4BE3-94D2-160F1BC9C4BD</TransactionKey>
    <TransactionReference>B2E32108</TransactionReference>
    <PreviousBoletoTransactionStatus>Generated</PreviousBoletoTransactionStatus>
    <BoletoTransactionStatus>Paid</BoletoTransactionStatus>
  </BoletoTransaction>
  <CreditCardTransaction>
    <Acquirer>Simulator</Acquirer>
    <AmountInCents>2000</AmountInCents>
    <AuthorizedAmountInCents>2000</AuthorizedAmountInCents>
    <CapturedAmountInCents>2000</CapturedAmountInCents>
    <CreditCardBrand>Visa</CreditCardBrand>
    <RefundedAmountInCents i:nil="true"/>
    <StatusChangedDate>2012-11-06T10:52:55.93</StatusChangedDate>
    <TransactionIdentifier>123456</TransactionIdentifier>
    <TransactionKey>351FC96A-7F42-4269-AF3C-1E3C179C1CD0</TransactionKey>
    <TransactionReference>24de0432</TransactionReference>
    <UniqueSequentialNumber>123456</UniqueSequentialNumber>
    <VoidedAmountInCents i:nil="true"/>
    <PreviousCreditCardTransactionStatus>AuthorizedPendingCapture</PreviousCreditCardTransactionStatus>
    <CreditCardTransactionStatus>Captured</CreditCardTransactionStatus>
  </CreditCardTransaction>
  <!--O nó OnlineDebitTransaction só é enviado caso uma transação de débito esteja sendo notificada-->
  <OnlineDebitTransaction>
    <AmountInCents>100</AmountInCents>
    <AmountPaidInCents>0</AmountPaidInCents>
    <StatusChangedDate>2013-06-27T19:46:46.87</StatusChangedDate>
    <TransactionKey>fb3f158a-0309-4ae3-b8ef-3c5ac2d603d2</TransactionKey>
    <TransactionReference>30bfee13-c908-4e3b-9f70-1f84dbe79fbf</TransactionReference>
    <PreviousOnlineDebitTransactionStatus>OpenedPendingPayment</PreviousOnlineDebitTransactionStatus>
    <OnlineDebitTransactionStatus>NotPaid</OnlineDebitTransactionStatus>
  </OnlineDebitTransaction>
  <!--O nó OnlineDebitTransaction só é enviado caso uma transação de débito esteja sendo notificada-->
  <MerchantKey>B1B1092C-8681-40C2-A734-500F22683D9B</MerchantKey>
  <OrderKey>18471F05-9F6D-4497-9C24-D60D5BBB6BBE</OrderKey>
  <OrderReference>64a85875</OrderReference>
  <OrderStatus>Paid</OrderStatus>
</StatusNotification>'));

    $apiClient->setEnvironment(\MundiPagg\One\DataContract\Enum\ApiEnvironmentEnum::TRANSACTION_REPORT);

    //$postObject = $apiClient->ParseXmlToNotification($xmlToPost);

    //echo '<pre>' . var_dump($postObject) . '<pre/>';
    $apiClient->DownloadTransactionReportFile('20150321');

    //$response = $apiClient->searchTransactionReportFile('20150321');

    //echo var_dump($response);

    //$response = $apiClient->Retry($retryRequest);

    //$response = $apiClient->searchSaleByOrderKey("E7D25F8D-DABF-4D1F-824F-2ECA07E9B35B"); //Requisição do Query

    //$response = $apiClient->createSale($createSaleRequest); //Requisição do CreateSale

    //print "<pre>";
    //    print var_dump($response->getData());
    //print "</pre>";

    // Imprime json
    //print "<pre>";
    //echo json_encode($createSaleRequest->getData(), JSON_PRETTY_PRINT);
    //print "</pre>";
} catch (\MundiPagg\One\DataContract\Report\ApiError $error) {
    echo $error;
    // Imprime json
    echo "<pre>";
    echo json_encode($error, JSON_PRETTY_PRINT);
    echo "</pre>";
} catch (Exception $ex) {
    echo $ex;
    // Imprime json
    echo "<pre>";
    echo json_encode($ex, JSON_PRETTY_PRINT);
    echo "</pre>";
}
?>