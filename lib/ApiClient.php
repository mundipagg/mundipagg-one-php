<?php

namespace Gateway;

/**
 * Class ApiClient
 * @package gateway
 */
use Gateway\One\DataContract\Enum\ApiMethodEnum;
use Gateway\One\DataContract\Enum\ApiResourceEnum;
use Gateway\One\DataContract\Response\BaseResponse;
use Gateway\One\Helper\TransactionReportHelper;
use Gateway\One\Helper\XmlPostParseHelper;

/**
 * Class ApiClient
 * @package gateway
 */
class ApiClient
{
    /**
     * @var string
     */
    static private $merchantKey;

    /**
     * @var string
     */
    static private $baseUrl;

    /**
     * @var string
     */
    static private $environment;

    /**
     * @var boolean
     */
    static private $isSslCertsVerificationEnabled = true;

    /**
     * @param string $environment
     */
    public static function setEnvironment($environment)
    {
        self::$environment = $environment;
    }

    /**
     * @return string
     */
    public static function getEnvironment()
    {
        return self::$environment;
    }

    /**
     * @param string $merchantKey
     */
    public static function setMerchantKey($merchantKey)
    {
        self::$merchantKey = $merchantKey;
    }

    /**
     * @return string
     */
    public static function getMerchantKey()
    {
        return self::$merchantKey;
    }

    /**
     * @return boolean
     */
    public static function isSslCertsVerificationEnabled()
    {
        return self::$isSslCertsVerificationEnabled;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getBaseUrl()
    {
        return self::$baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public static function setBaseUrl($baseUrl)
    {
        self::$baseUrl = $baseUrl;
    }

    /**
     * @param $uri
     * @return string
     */
    private function buildUrl($uri)
    {
        $url = sprintf("%s/%s", $this->getBaseUrl(), $uri);

        return $url;
    }


    /**
     * @param $uri
     * @param $method
     * @param null $bodyData
     * @param null $queryStringData
     * @return array
     */
    private function getOptions($uri, $method, $bodyData = null, $queryStringData = null)
    {
        $options = array
        (
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTPHEADER => array
            (
                'MerchantKey: ' . self::getMerchantKey()
            ),
            CURLOPT_URL => $this->buildUrl($uri),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYPEER => self::isSslCertsVerificationEnabled()
        );

        // Se for passado parametro na query string, vamos concatenar eles na url
        if ($queryStringData != null) {
            $options[CURLOPT_URL] .= '?' . http_build_query($queryStringData);
        }

        if (strstr($uri, 'TransactionReportFile') == false) {
            $options[CURLOPT_HTTPHEADER][] = 'Content-type: application/json';
            $options[CURLOPT_HTTPHEADER][] = 'Accept: application/json';
        }

        // Associa o certificado para a verificação
        if (self::isSslCertsVerificationEnabled()) {
            $options[CURLOPT_CAINFO] = dirname(__FILE__) . '/../data/ca-certificates.crt';
        }

        // Se o método http for post ou put e tiver dados para enviar no body
        if (in_array($method, array(One\DataContract\Enum\ApiMethodEnum::POST, One\DataContract\Enum\ApiMethodEnum::PUT, One\DataContract\Enum\ApiMethodEnum::PATCH)) && $bodyData != null) {
            $options[CURLOPT_POSTFIELDS] = json_encode($bodyData);
        }
        return $options;
    }

    /**
     * @param $resource
     * @param $method
     * @param null $bodyData
     * @param null $queryString
     * @return mixed
     * @throws \Exception
     */
    private function sendRequest($resource, $method, $bodyData = null, $queryString = null)
    {
        // Inicializa sessão cURL
        $curlSession = curl_init();

        // Define as opções da sessão
        //curl_setopt_array($curlSession, $this->getOptions($resource, $method, $bodyData, $queryString));
        $options =  $this->getOptions($resource, $method, $bodyData, $queryString);
        
        
        foreach($options as $index => $value) {
            curl_setopt($curlSession, $index, $value);
        }

        // Dispara a requisição cURL
        $responseBody = curl_exec($curlSession);
        

        // Obtém o status code http retornado
        $httpStatusCode = curl_getinfo($curlSession, CURLINFO_HTTP_CODE);

        // Fecha a sessão cURL
        curl_close($curlSession);

        // Verifica se não obteve resposta
        if (!$responseBody) throw new \Exception("Error Processing Request", 1);

        //Verifica se é o método do transactionReport, o formato de resposta dele não é em JSON
        if (strstr($resource, 'TransactionReportFile') != false) {
            return $responseBody;
        }

        // Decodifica a resposta json
        $response = json_decode($responseBody);

        // Verifica se o http status code for diferente de 2xx ou se a resposta teve erro
        if (!($httpStatusCode >= 200 && $httpStatusCode < 300) || !empty($response->ErrorReport)) {
            @$this->handleApiError($httpStatusCode, $response->RequestKey, $response->ErrorReport, $queryString, $bodyData, $responseBody);
        }
        // Retorna a resposta
        return $response;
    }

    /**
     * @param One\DataContract\Request\CreateSaleRequest $createSaleRequest
     * @return BaseResponse
     * @throws \Exception
     */
    public function createSale(One\DataContract\Request\CreateSaleRequest $createSaleRequest)
    {
        // Dispara a requisição
        $createSaleResponse = $this->sendRequest(ApiResourceEnum::SALE, ApiMethodEnum::POST, $createSaleRequest->getData());

        // Verifica sucesso
        if (empty($createSaleResponse->BoletoTransactionResultCollection) && empty($createSaleResponse->CreditCardTransactionResultCollection)) {
            $isSuccess = false;
        } else {
            $isSuccess = true;

            if (count($createSaleResponse->BoletoTransactionResultCollection) > 0) foreach ($createSaleResponse->BoletoTransactionResultCollection as $boletoTransaction) {
                if (!$boletoTransaction->Success) $isSuccess = false;
            }

            if (count($createSaleResponse->CreditCardTransactionResultCollection) > 0) foreach ($createSaleResponse->CreditCardTransactionResultCollection as $creditCardTransaction) {
                if (!$creditCardTransaction->Success) $isSuccess = false;
            }
        }

        // Cria objeto de resposta
        $response = new BaseResponse($isSuccess, $createSaleResponse);

        // Retorna reposta
        return $response;
    }

    public function createOneRestRequest(One\DataContract\Request\OneRestRequestData\createOneRestRequest $createOneRestRequest){
        $createOneRestResponse = $this->sendRequest(null, ApiMethodEnum::POST, $createOneRestRequest);
        if ($getCreditCardResponse->ErrorReport == null) {
            $isSuccess = true;
        } else {
            $isSuccess = false;
        }
        $response = new BaseResponse($isSuccess, $createOneRestResponse);
        return $response;
    }

    public function createToken(One\DataContract\Request\TokenRequest $tokenRequest)
    {
        // Dispara a requisição
        $tokenResponse = $this->sendRequest(ApiResourceEnum::TOKEN, ApiMethodEnum::POST, $tokenRequest->getData());

        // Cria objeto de resposta
        $response = new BaseResponse($tokenResponse->Success, $tokenResponse);

        // Retorna reposta
        return $response;

    }

    public function createBuyer(One\DataContract\Request\CreateBuyerRequest $buyerContract)
    {
        // Dispara a requisição
        $buyerRequest = $this->sendRequest("Buyer/", ApiMethodEnum::POST, $buyerContract->getData());


         // Cria objeto de resposta
        $response = new BaseResponse($buyerRequest->Success, $buyerRequest);

         // Retorna reposta
        return $response;
    }

    public function getBuyer ($buyerKey)
    {
        $resource = "Buyer/" . $buyerKey;


        // Dispara a requisição
        $buyerResponse = $this->sendRequest($resource, ApiMethodEnum::GET);

        // Cria objeto de resposta
        $response = new BaseResponse($buyerResponse->Success, $buyerResponse);

        // Retorna rsposta
        return $response;
    }

    public function createCreditCard(One\DataContract\Request\CreateInstantBuyDataRequest $createInstantBuyDataRequest)
    {
        // Dispara a requisição
        $createCreditCardResponse = $this->sendRequest("CreditCard/", ApiMethodEnum::POST, $createInstantBuyDataRequest->getData());

        // Cria objeto de resposta
        $response = new BaseResponse($createCreditCardResponse->Success, $createCreditCardResponse);

        return $response;
    }

    public function deleteCreditCard($instantBuyKey)
    {
        $resource = sprintf("creditcard/%s", $instantBuyKey);

        // Dispara a requisição
        $deleteCreditCardResponse = $this->sendRequest($resource, ApiMethodEnum::DELETE);

        // Cria objeto de resposta
        $response = new BaseResponse($deleteCreditCardResponse->Success, $deleteCreditCardResponse);

        return $response;
    }

    /**
     * @param One\DataContract\Request\CaptureRequest $captureRequest
     * @return BaseResponse
     * @throws \Exception
     */
    public function capture(One\DataContract\Request\CaptureRequest $captureRequest)
    {
        // Dispara a requisição
        $captureResponse = $this->sendRequest(ApiResourceEnum::CAPTURE, ApiMethodEnum::POST, $captureRequest->getData());

        // Verifica sucesso
        if (count($captureResponse->CreditCardTransactionResultCollection) <= 0) {
            $isSuccess = false;
        } else {
            $isSuccess = true;

            foreach ($captureResponse->CreditCardTransactionResultCollection as $creditCardTransaction) {
                if (!$creditCardTransaction->Success) {
                    $isSuccess = false;
                }
            }
        }

        // Cria objeto de resposta
        $response = new BaseResponse($isSuccess, $captureResponse);

        // Retorna rsposta
        return $response;
    }


    /**
     * @param One\DataContract\Request\CancelRequest $cancelRequest
     * @return BaseResponse
     * @throws \Exception
     */
    public function cancel(One\DataContract\Request\CancelRequest $cancelRequest)
    {
        // Dispara a requisição
        $cancelResponse = $this->sendRequest(ApiResourceEnum::CANCEL, ApiMethodEnum::POST, $cancelRequest->getData());

        // Verifica sucesso
        if (count($cancelResponse->CreditCardTransactionResultCollection) <= 0) {
            $isSuccess = false;
        } else {
            $isSuccess = true;

            foreach ($cancelResponse->CreditCardTransactionResultCollection as $creditCardTransaction) {
                if (!$creditCardTransaction->Success) {
                    $isSuccess = false;
                }
            }
        }

        // Cria objeto de resposta
        $response = new BaseResponse($isSuccess, $cancelResponse);

        // Retorna rsposta
        return $response;
    }

    /**
     * @param $httpStatusCode
     * @param $requestKey
     * @param $errorCollection
     * @param $requestData
     * @param $responseBody
     * @throws One\DataContract\Report\ApiError
     */
    private function handleApiError($httpStatusCode, $requestKey, $errorCollection, $requestQueryStringData, $requestBodyData, $responseBody)
    {
        throw new One\DataContract\Report\ApiError($httpStatusCode, $requestKey, $errorCollection, $requestQueryStringData, $requestBodyData, $responseBody);
    }

    /**
     * @param $instantBuyKey
     * @return BaseResponse
     * @throws \Exception
     */
    public function getInstantBuyDataByInstantBuyKey($instantBuyKey) {
        return $this->getCreditCard($instantBuyKey);
    }

    public function getCreditCard($instantBuyKey) {
        $resource = sprintf("creditcard/%s", $instantBuyKey);

        // Dispara a requisição
        $getCreditCardResponse = $this->sendRequest($resource, ApiMethodEnum::GET);

        // Cria objeto de resposta
        if ($getCreditCardResponse->ErrorReport == null) {
            $isSuccess = true;
        } else {
            $isSuccess = false;
        }
        $response = new BaseResponse($isSuccess, $getCreditCardResponse);

        // Retorna rsposta
        return $response;
    }

    /**
     * @param $buyerKey
     * @return BaseResponse
     * @throws \Exception
     */
    public function getInstantBuyDataByBuyerKey($buyerKey) {
        return $this->getCreditCardWithBuyerKey($buyerKey);
    }

    public function getCreditCardWithBuyerKey($buyerKey) {
        $resource = sprintf("creditcard/BuyerKey=%s", $buyerKey);

        // Dispara a requisição
        $getCreditCardResponse = $this->sendRequest($resource, ApiMethodEnum::GET);

        // Cria objeto de resposta
        if ($getCreditCardResponse->ErrorReport == null) {
            $isSuccess = true;
        } else {
            $isSuccess = false;
        }
        $response = new BaseResponse($isSuccess, $getCreditCardResponse);

        // Retorna rsposta
        return $response;
    }

    public function updateInstantBuyData($instantBuyKey, One\DataContract\Request\UpdateInstantBuyDataRequest $updateBuyerKeyRequest)
    {
        $resource = sprintf("creditcard/%s", $instantBuyKey);

        //Dispara a requisição
        $updateInstantBuyDataResponse = $this->sendRequest($resource, ApiMethodEnum::PATCH, $updateBuyerKeyRequest->getData());

        // Cria objeto de resposta
        $response = new BaseResponse($updateInstantBuyDataResponse->Success, $updateInstantBuyDataResponse);

        // Retorna resposta
        return $response;
    }

    /**
     * @param $orderReference
     * @return BaseResponse
     * @throws \Exception
     */
    public function searchSaleByOrderReference($orderReference)
    {
        // Cria objeto de resposta
        $response = $this->QueryImplementation('orderreference', $orderReference);

        // Retorna resposta
        return $response;
    }

    /**
     * @param $orderKey
     * @return BaseResponse
     * @throws \Exception
     */
    public function searchSaleByOrderKey($orderKey)
    {
        // Cria objeto de resposta
        $response = $this->QueryImplementation('orderkey', $orderKey);

        // Retorna resposta
        return $response;
    }

    public function Retry(One\DataContract\Request\RetryRequest $retryRequest)
    {
        // Dispara a requisição
        $retryResponse = $this->sendRequest(ApiResourceEnum::RETRY, ApiMethodEnum::POST, $retryRequest->getData());

        // Verifica sucesso
        if (empty($retryResponse->CreditCardTransactionResultCollection)) {
            $isSuccess = false;
        } else {
            $isSuccess = true;
        }

        // Cria objeto de resposta
        $response = new BaseResponse($isSuccess, $retryResponse);

        // Retorna reposta
        return $response;
    }

    /**
     * @param $reportDate
     * @param $filePath
     */
    public function DownloadTransactionReportFile($reportDate, $filePath)
    {
        $reportResponse = $this->reportFileImplementation(date('Ymd', strtotime($reportDate)));

        $fileName = 'TransactionReportFile-' . $reportDate . '.txt';

        $myFile = fopen($filePath . "\\" . $fileName, "w");
        fwrite($myFile, $reportResponse);
        fclose($myFile);
    }

     /**
     * @param $reportDate
     * @return string
     */
    public function SearchTransactionReportFile($reportDate)
    {
        $reportResponse = $this->reportFileImplementation(date('Ymd', strtotime($reportDate)));

        return $reportResponse;
    }

    /**
     * @param $reportDate
     */
    public function ParseTransactionReportFile($reportFileData)
    {
        $response = new \Gateway\One\DataContract\TransactionReport\TransactionReport();

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $reportFileData) as $line) {
            $lineProperties = explode(',', $line);

            switch ($lineProperties[0]) {
                case "01":
                    $header = TransactionReportHelper::HeaderTransactionParser($lineProperties);

                    $response->setHeader($header);
                    break;
                case "20":
                    $creditCardTransaction = TransactionReportHelper::CreditCardTransactionParser($lineProperties);

                    $response->addCreditCardTransaction($creditCardTransaction);
                    break;
                case "30":
                    $boletoTransaction = new One\DataContract\TransactionReport\TransactionReportData\TransactionReportBoletoTransaction();
                    $transactionReportOrder = new One\DataContract\TransactionReport\TransactionReportData\TransactionReportOrder();

                    $transactionReportOrder->setOrderKey($lineProperties[1])
                        ->setOrderReference($lineProperties[2])
                        ->setMerchantKey($lineProperties[3])
                        ->setMerchantName($lineProperties[4]);
                    $boletoTransaction->setOrder($transactionReportOrder);

                    $boletoTransaction->setTransactionKey($lineProperties[5])
                        ->setTransactionReference($lineProperties[6])
                        ->setStatus($lineProperties[7])
                        ->setNossoNumero($lineProperties[8])
                        ->setBankNumber($lineProperties[9])
                        ->setAgency($lineProperties[10])
                        ->setAccount($lineProperties[11])
                        ->setBarCode($lineProperties[12])
                        ->setExpirationDate($lineProperties[13])
                        ->setAmountInCents($lineProperties[14]);
                    ($lineProperties[15] == false) ? $boletoTransaction->setAmountPaidInCents($lineProperties[15]) : 0;
                    ($lineProperties[16] == false) ? $boletoTransaction->setPaymentDate($lineProperties[16]) : null;
                    ($lineProperties[17] == false) ? $boletoTransaction->setCreditDate($lineProperties[17]) : null;

                    $response->addBoletoTransaction($boletoTransaction);
                    break;
                case "40":
                    $onlineDebitTransaction = new One\DataContract\TransactionReport\TransactionReportData\OnlineDebitTransaction();
                    $transactionReportOrder = new One\DataContract\TransactionReport\TransactionReportData\TransactionReportOrder();
                    $transactionReportOrder->setOrderKey($lineProperties[1])
                        ->setOrderReference($lineProperties[2])
                        ->setMerchantKey($lineProperties[3])
                        ->setMerchantName($lineProperties[4]);

                    $onlineDebitTransaction->setOrder($transactionReportOrder)
                        ->setTransactionKey($lineProperties[5])
                        ->setTransactionReference($lineProperties[6])
                        ->setBank($lineProperties[7])
                        ->setStatus($lineProperties[8])
                        ->setAmountInCents($lineProperties[9]);
                    ($lineProperties[10] == false) ? $onlineDebitTransaction->setAmountPaidInCents($lineProperties[10]) : 0;
                    ($lineProperties[11] == false) ? $onlineDebitTransaction->setPaymentDate($lineProperties[11]) : null;
                    $onlineDebitTransaction->setBankReturnCode($lineProperties[12])
                        ->setBankPaymentDate($lineProperties[13])
                        ->setSignature($lineProperties[14])
                        ->setTransactionKeyToBank($lineProperties[15]);

                    $response->addOnlineDebitTransaction($onlineDebitTransaction);
                    break;
                case "99":
                    $trailer = new One\DataContract\TransactionReport\TransactionReportData\Trailer();
                    $trailer->setOrderDataCount($lineProperties[1])
                        ->setCreditCardTransactionDataCount($lineProperties[2])
                        ->setBoletoTransactionDataCount($lineProperties[3])
                        ->setOnlineDebitTransactionDataCount($lineProperties[4]);
                    $response->setTrailer($trailer);
                    break;
            }
        }
        return $response;
    }


    public function ParseXmlToNotification($xmlStatusNotification)
    {
        $statusNotification = XmlPostParseHelper::ParseFromXml($xmlStatusNotification);

        return $statusNotification;
    }


    private function reportFileImplementation($reportDate)
    {
        $resource = sprintf('TransactionReportFile/GetStream?fileDate=%s', $reportDate);

        // Dispara a requisição
        $reportResponse = $this->sendRequest($resource, ApiMethodEnum::GET);

        return $reportResponse;
    }

    /**
     * @param $method
     * @param $key
     * @return BaseResponse
     * @throws \Exception
     */
    private function QueryImplementation($method, $key)
    {
        // Monta o parametro
        $resource = sprintf("sale/query/%s=%s", $method, $key);

        // Dispara a requisição
        $queryResponse = $this->sendRequest($resource, ApiMethodEnum::GET);

        // Cria objeto de resposta
        $response = new BaseResponse(true, $queryResponse);

        // Retorna rsposta
        return $response;
    }
}
