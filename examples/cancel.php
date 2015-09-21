<?php

require_once(dirname(__FILE__) . '/../bootstrap.php');

try
{
    // Define o ambiente utilizado (produção ou homologação)
    \MundiPagg\ApiClient::setEnvironment(\MundiPagg\One\DataContract\Enum\ApiEnvironmentEnum::SANDBOX);

    // Define a chave da loja
    \MundiPagg\ApiClient::setMerchantKey("be43cb17-3637-44d0-a45e-d68aaee29f47");

    // Cria objeto requisição
    $request = new \MundiPagg\One\DataContract\Request\CancelRequest();

    // Define dados da requisição
    $request->setOrderKey("47e80399-fe75-40cd-8c41-b6457b37e75e");

    //Cria um objeto ApiClient
    $client = new MundiPagg\ApiClient();

    // Faz a chamada para criação
    $response = $client->cancel($request);

    // Imprime responsta
    print "<pre>";
    print json_encode(array('success' => $response->isSuccess(), 'data' => $response->getData()), JSON_PRETTY_PRINT);
    print "</pre>";
}
catch (\MundiPagg\One\DataContract\Report\ApiError $error)
{
    // Imprime json
    print "<pre>";
    print json_encode($error, JSON_PRETTY_PRINT);
    print "</pre>";
}
catch (Exception $ex)
{
    // Imprime json
    print "<pre>";
    print json_encode($ex, JSON_PRETTY_PRINT);
    print "</pre>";
}