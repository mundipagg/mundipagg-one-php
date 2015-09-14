<?php

namespace MundiPagg;

/**
 * Class ApiClient
 * @package MundiPagg
 */
use MundiPagg\One\DataContract\Enum\ApiMethodEnum;
use MundiPagg\One\DataContract\Enum\ApiResourceEnum;
use MundiPagg\One\DataContract\Response\BaseResponse;

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
        switch (self::getEnvironment())
        {
            case One\DataContract\Enum\ApiEnvironmentEnum::PRODUCTION: return 'https://transactionv2.mundipaggone.com';
            case One\DataContract\Enum\ApiEnvironmentEnum::SANDBOX: return 'https://sandbox.mundipaggone.com';
            case One\DataContract\Enum\ApiEnvironmentEnum::INSPECTOR: return 'https://stagingv2-mundipaggone-com-9blwcrfjp9qk.runscope.net';

            default: throw new \Exception("The api environment was not defined.");
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
            CURLOPT_URL => $this->buildUrl($uri),
            CURLOPT_HTTPHEADER => array
            (
                'Content-type: application/json',
                'Accept: application/json',
                'MerchantKey: '.self::getMerchantKey()
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYPEER => self::isSslCertsVerificationEnabled()
        );

        // Se for passado parametro na query string, vamos concatenar eles na url
        if ($queryStringData != null)
        {
            $options[CURLOPT_URL] .= '?'.http_build_query($queryStringData);
        }

        // Associa o certificado para a verificação
        if (self::isSslCertsVerificationEnabled())
        {
            $options[CURLOPT_CAINFO] = dirname(__FILE__) . '/../data/ca-certificates.crt';
        }

        // Se o método http for post ou put e tiver dados para enviar no body
        if (in_array($method, array(One\DataContract\Enum\ApiMethodEnum::POST, One\DataContract\Enum\ApiMethodEnum::PUT)) && $bodyData != null)
        {
            $options[CURLOPT_POSTFIELDS] = json_encode($bodyData);
        }

        return $options;
    }

    /**
     * @param $resource
     * @param $method
     * @param $data
     * @throws \Exception
     * @return mixed
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

        // Decodifica a resposta json
        $response = json_decode($responseBody);

        // Verifica se o http status code for diferente de 2xx ou se a resposta teve erro
        if (!($httpStatusCode >= 200 && $httpStatusCode < 300) || !empty($response->ErrorReport))
        {
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
        if (empty($createSaleResponse->BoletoTransactionResultCollection) && empty($createSaleResponse->CreditCardTransactionResultCollection))
        {
            $isSuccess = false;
        }
        else
        {
            $isSuccess = true;

            if (count($createSaleResponse->BoletoTransactionResultCollection) > 0) foreach ($createSaleResponse->BoletoTransactionResultCollection as $boletoTransaction)
            {
                if (!$boletoTransaction->Success) $isSuccess = false;
            }

            if (count($createSaleResponse->CreditCardTransactionResultCollection) > 0) foreach ($createSaleResponse->CreditCardTransactionResultCollection as $creditCardTransaction)
            {
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
        if (count($captureResponse->CreditCardTransactionResultCollection) <= 0)
        {
            $isSuccess = false;
        }
        else
        {
            $isSuccess = true;

            foreach ($captureResponse->CreditCardTransactionResultCollection as $creditCardTransaction)
            {
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
        if (count($cancelResponse->CreditCardTransactionResultCollection) <= 0)
        {
            $isSuccess = false;
        }
        else
        {
            $isSuccess = true;

            foreach ($cancelResponse->CreditCardTransactionResultCollection as $creditCardTransaction)
            {
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
        if (empty($retryResponse->CreditCardTransactionResultCollection))
        {
            $isSuccess = false;
        }
        else
        {
            $isSuccess = true;
        }

        // Cria objeto de resposta
        $response = new BaseResponse($isSuccess, $retryResponse);

        // Retorna reposta
        return $response;
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
