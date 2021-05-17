<?php

use App\MarketConfig;
use GuzzleHttp\Client;

class IFoodApi
{
  protected $clientId;
  protected $clientSecret;
  protected $baseUrl;
  protected $access_token;
  protected $client;

  function __construct($id)
  {
    $this->clientId     = MarketConfig::select('client_id')->where('id', $id)->first();
    $this->clientSecret     = MarketConfig::select('client_secret')->where('id', $id)->first();
    $this->baseUrl      = 'https://merchant-api.ifood.com.br/';
    $this->client       = new Client([
      'base_uri'  => $this->baseUrl
    ]);
    $this->access_token = $this->auth();
  }

  public function auth()
  {       
    try {
      $headers    = ['Content-Type' => 'application/x-www-form-urlencoded'];
      $body       = [
        'grantType'     => 'client_credentials',
        'clientId'      => $this->clientId['client_id'],
        'clientSecret'  => $this->clientSecret['client_secret'],
      ];
      $response   = $this->client->post('authentication/v1.0/oauth/token',[
        'form_params'   => $body,
        'headers'       => $headers
      ]);
      $res = json_decode($response->getBody()->getContents());
      $this->access_token = $res->accessToken;
      return $res->accessToken;
    }catch (\Exception $e){
      return $e;
    }
  }

  public function getOrders()
  {
    $res = $this->client->get('order/v1.0/events:polling', [
      'headers'   => [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer '.$this->access_token
      ]
    ]);
    $response = json_decode($res->getBody()->getContents());
    
    return $response;
  }

  public function getAcknowledgment($data)
  { 
    $headers    = [
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer '.$this->access_token
    ];
    $object = array(
      (object)
        array(
          'id'        => $data->id,
          'code'      => $data->code,
          'fullCode'  => $data->fullCode,
          'orderId'   => $data->orderId,
          'createdAt' => $data->createdAt
        )
    );

    \Log::debug('acknowledgment: '.print_r($object,1));
    $res = $this->client->request('POST','order/v1.0/events/acknowledgment', [
      'headers'   => $headers,
      'body'      => json_encode($object)
    ]);
    $response = json_decode($res->getBody()->getContents());
    
    return $response;
  }

  public function getOrderDetails($id)
  {
    $res = $this->client->get('order/v1.0/orders/'.$id, [
      'headers' => [
        'Authorization' => 'Bearer '.$this->access_token
      ]
    ]);
    $response = json_decode($res->getBody()->getContents());
    return $response;
  }

  public function confirmOrderApi($id)
  {
    \Log::debug("Entrou");
    try {
      $res   = $this->client->post('order/v1.0/orders/'.$id.'/confirm');
      \Log::debug('Details 1: '.print_r($res->getBody()->getContents(),1));
      $response = json_decode($res->getBody()->getContents());
      return $response;
    }catch (\Exception $e){
      return $e;
    }
  }

  public function rtcOrder($id)
  {
    $headers    = [
      'Content-Type' => 'application/x-www-form-urlencoded',
      'Authorization' => 'Bearer '.$this->access_token
    ];
    $body       = [
        'id'     => $id,
      ];
    $res   = $this->client->post('order/v1.0/orders/'.$id.'/readyToPickup',[
      'form_params'   => $body,
      'headers'       => $headers
    ]);
    $response = json_decode($res->getBody()->getContents());
    \Log::debug("readyToPickup: ".print_r($response,1));
    
  }
}