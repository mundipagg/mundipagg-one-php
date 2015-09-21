<?php

namespace MundiPagg;

/**
 * Class ApiClient
 * @package MundiPagg
 */
use MundiPagg\One\DataContract\Enum\ApiMethodEnum;
use MundiPagg\One\DataContract\Enum\ApiResourceEnum;
use MundiPagg\One\DataContract\Response\BaseResponse;
use MundiPagg\One\DataContract\TransactionReport\TransactionReportData\Trailer;
use MundiPagg\One\Helper\xmlPostParseHelper;

/**
 * Class ApiClient
 * @package MundiPagg
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
        switch (self::getEnvironment()) {
            case One\DataContract\Enum\ApiEnvironmentEnum::PRODUCTION:
                return 'https://transactionv2.mundipaggone.com';
            case One\DataContract\Enum\ApiEnvironmentEnum::SANDBOX:
                return 'https://sandbox.mundipaggone.com';
            case One\DataContract\Enum\ApiEnvironmentEnum::INSPECTOR:
                return 'https://stagingv2-mundipaggone-com-9blwcrfjp9qk.runscope.net';
            case One\DataContract\Enum\ApiEnvironmentEnum::TRANSACTION_REPORT:
                return 'https://api.mundipaggone.com';

            default:
                throw new \Exception("The api environment was not defined.");
        }
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
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYPEER => self::isSslCertsVerificationEnabled()
        );

        // Se for passado parametro na query string, vamos concatenar eles na url
        if ($queryStringData != null) {
            $options[CURLOPT_URL] .= '?' . http_build_query($queryStringData);
        }

        if (strstr($uri, 'TransactionReportFile') == false) {
            array_push($options[CURLOPT_HTTPHEADER], 'Content-type: application/json', 'Accept: application/json');
        }

        // Associa o certificado para a verificação
        if (self::isSslCertsVerificationEnabled()) {
            $options[CURLOPT_CAINFO] = dirname(__FILE__) . '/../data/ca-certificates.crt';
        }

        // Se o método http for post ou put e tiver dados para enviar no body
        if (in_array($method, array(One\DataContract\Enum\ApiMethodEnum::POST, One\DataContract\Enum\ApiMethodEnum::PUT)) && $bodyData != null) {
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
        curl_setopt_array($curlSession, $this->getOptions($resource, $method, $bodyData, $queryString));

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
    public function GetInstantBuyDataByInstantBuyKey($instantBuyKey)
    {
        $resource = sprintf("creditcard/%s", $instantBuyKey);

        // Dispara a requisição
        $instantBuyKeyResponse = $this->sendRequest($resource, ApiMethodEnum::GET);

        // Cria objeto de resposta
        $response = new BaseResponse(true, $instantBuyKeyResponse);

        // Retorna rsposta
        return $response;
    }

    /**
     * @param $buyerKey
     * @return BaseResponse
     * @throws \Exception
     */
    public function GetInstantBuyDataByBuyerKey($buyerKey)
    {
        $resource = sprintf("creditcard/%s/buyerkey", $buyerKey);

        // Dispara a requisição
        $instantBuyKeyByBuyerKeyResponse = $this->sendRequest($resource, ApiMethodEnum::GET);

        // Cria objeto de resposta
        $response = new BaseResponse(true, $instantBuyKeyByBuyerKeyResponse);

        // Retorna rsposta
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
     * @throws \Exception
     */
    public function DownloadTransactionReportFile($reportDate)
    {
        $reportResponse = $this->reportFileImplementation(date('Ymd', strtotime($reportDate)));

        $fileName = 'TransactionReportFile-' . $reportDate . '.txt';

        header("Content-Type: text/plain");
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header("Content-Length: " . strlen($reportResponse));

        exit;
    }

    /**
     * @param $reportDate
     */
    public function searchTransactionReportFile($reportDate)
    {
        $reportData = $this->reportFileImplementation(date('Ymd', strtotime($reportDate)));

        $response = new \MundiPagg\One\DataContract\TransactionReport\TransactionReport();

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $reportData) as $line) {
            $lineProperties = explode(',', $line);

            switch ($lineProperties[0]) {
                case "01":
                    $header = new One\DataContract\TransactionReport\TransactionReportData\Header();
                    $header->setTransactionProcessedDate($lineProperties[1]);
                    $header->setReportFileCreateDate($lineProperties[2]);
                    $header->setVersion($lineProperties[3]);
                    $response->setHeader($header);
                    break;
                case "20":
                    $creditCardTransaction = new One\DataContract\TransactionReport\TransactionReportData\TransactionReportCreditCardTransaction();
                    $transactionReportOrder = new One\DataContract\TransactionReport\TransactionReportData\TransactionReportOrder();

                    $transactionReportOrder->setOrderKey($lineProperties[1]);
                    $transactionReportOrder->setOrderReference($lineProperties[2]);
                    $transactionReportOrder->setMerchantKey($lineProperties[3]);
                    $transactionReportOrder->setMerchantName($lineProperties[4]);

                    $creditCardTransaction->setOrder($transactionReportOrder);

                    $creditCardTransaction->setTransactionKey($lineProperties[5]);
                    $creditCardTransaction->setTransactionKeyToAcquirer($lineProperties[6]);
                    $creditCardTransaction->setCreditCardTransactionReference($lineProperties[7]);
                    $creditCardTransaction->setCreditCardBrand($lineProperties[8]);
                    $creditCardTransaction->setCreditCardNumber($lineProperties[9]);
                    ($lineProperties[10] == false) ? $creditCardTransaction->setInstallmentCount($lineProperties[10]) : 0;
                    $creditCardTransaction->setAcquirerName($lineProperties[11]);
                    $creditCardTransaction->setStatus($lineProperties[12]);
                    ($lineProperties[13] == false) ? $creditCardTransaction->setAmountInCents($lineProperties[13]) : 0;
                    ($lineProperties[14] == false) ? $creditCardTransaction->setIataAmountInCents($lineProperties[14]) : 0;
                    $creditCardTransaction->setAuthorizationCode($lineProperties[15]);
                    $creditCardTransaction->setTransactionIdentifier($lineProperties[16]);
                    $creditCardTransaction->setUniqueSequentialNumber($lineProperties[17]);
                    ($lineProperties[18] == false) ? $creditCardTransaction->setAuthorizedAmountInCents($lineProperties[18]) : 0;
                    ($lineProperties[19] == false) ? $creditCardTransaction->setCapturedAmountInCents($lineProperties[19]) : 0;
                    ($lineProperties[20] == false) ? $creditCardTransaction->setVoidedAmountInCents($lineProperties[20]) : 0;
                    ($lineProperties[21] == false) ? $creditCardTransaction->setRefundedAmountInCents($lineProperties[21]) : 0;
                    $creditCardTransaction->setAcquirerAuthorizationReturnCode($lineProperties[22]);
                    ($lineProperties[23] == false) ? $creditCardTransaction->setAuthorizedDate($lineProperties[23]) : null;
                    ($lineProperties[24] == false) ? $creditCardTransaction->setCapturedDate($lineProperties[24]) : null;
                    ($lineProperties[25] == false) ? $creditCardTransaction->setVoidedDate($lineProperties[25]) : null;
                    ($lineProperties[26] == false) ? $creditCardTransaction->setLastProbeDate($lineProperties[26]) : null;

                    $response->addCreditCardTransaction($creditCardTransaction);
                    break;
                case "30":
                    $boletoTransaction = new One\DataContract\TransactionReport\TransactionReportData\TransactionReportBoletoTransaction();
                    $transactionReportOrder = new One\DataContract\TransactionReport\TransactionReportData\TransactionReportOrder();

                    $transactionReportOrder->setOrderKey($lineProperties[1]);
                    $transactionReportOrder->setOrderReference($lineProperties[2]);
                    $transactionReportOrder->setMerchantKey($lineProperties[3]);
                    $transactionReportOrder->setMerchantName($lineProperties[4]);
                    $boletoTransaction->setOrder($transactionReportOrder);

                    $boletoTransaction->setTransactionKey($lineProperties[5]);
                    $boletoTransaction->setTransactionReference($lineProperties[6]);
                    $boletoTransaction->setStatus($lineProperties[7]);
                    $boletoTransaction->setNossoNumero($lineProperties[8]);
                    $boletoTransaction->setBankNumber($lineProperties[9]);
                    $boletoTransaction->setAgency($lineProperties[10]);
                    $boletoTransaction->setAccount($lineProperties[11]);
                    $boletoTransaction->setBarCode($lineProperties[12]);
                    $boletoTransaction->setExpirationDate($lineProperties[13]);
                    $boletoTransaction->setAmountInCents($lineProperties[14]);
                    ($lineProperties[15] == false) ? $boletoTransaction->setAmountPaidInCents($lineProperties[15]) : 0;
                    ($lineProperties[16] == false) ? $boletoTransaction->setPaymentDate($lineProperties[16]) : null;
                    ($lineProperties[17] == false) ? $boletoTransaction->setCreditDate($lineProperties[17]) : null;

                    $response->addBoletoTransaction($boletoTransaction);
                    break;
                case "40":
                    $onlineDebitTransaction = new One\DataContract\TransactionReport\TransactionReportData\OnlineDebitTransaction();
                    $transactionReportOrder = new One\DataContract\TransactionReport\TransactionReportData\TransactionReportOrder();
                    $transactionReportOrder->setOrderKey($lineProperties[1]);
                    $transactionReportOrder->setOrderReference($lineProperties[2]);
                    $transactionReportOrder->setMerchantKey($lineProperties[3]);
                    $transactionReportOrder->setMerchantName($lineProperties[4]);

                    $onlineDebitTransaction->setOrder($transactionReportOrder);
                    $onlineDebitTransaction->setTransactionKey($lineProperties[5]);
                    $onlineDebitTransaction->setTransactionReference($lineProperties[6]);
                    $onlineDebitTransaction->setBank($lineProperties[7]);
                    $onlineDebitTransaction->setStatus($lineProperties[8]);
                    $onlineDebitTransaction->setAmountInCents($lineProperties[9]);
                    ($lineProperties[10] == false) ? $onlineDebitTransaction->setAmountPaidInCents($lineProperties[10]) : 0;
                    ($lineProperties[11] == false) ? $onlineDebitTransaction->setPaymentDate($lineProperties[11]) : null;
                    $onlineDebitTransaction->setBankReturnCode($lineProperties[12]);
                    $onlineDebitTransaction->setBankPaymentDate($lineProperties[13]);
                    $onlineDebitTransaction->setSignature($lineProperties[14]);
                    $onlineDebitTransaction->setTransactionKeyToBank($lineProperties[15]);

                    $response->addOnlineDebitTransaction($onlineDebitTransaction);
                    break;
                case "99":
                    $trailer = new One\DataContract\TransactionReport\TransactionReportData\Trailer();
                    $trailer->setOrderDataCount($lineProperties[1]);
                    $trailer->setCreditCardTransactionDataCount($lineProperties[2]);
                    $trailer->setBoletoTransactionDataCount($lineProperties[3]);
                    $trailer->setOnlineDebitTransactionDataCount($lineProperties[4]);
                    $response->setTrailer($trailer);
                    break;
            }
        }
        return $response;
    }


    public function ParseXmlToStatusNotification($xmlStatusNotification)
    {
        $statusNotification = xmlPostParseHelper::ParseFromXml($xmlStatusNotification);

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
