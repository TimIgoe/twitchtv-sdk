<?php

namespace TSIHosting\TwitchSDK\Calls;

use GuzzleHttp\Client as Guzzle;

class Base
{
  /**
   * @var Guzzle|null
   */
  private $guzzle = null;

  /**
   * @var array
   */
  private $headers = [];

  /**
   * Base constructor.
   * @param Guzzle $guzzle
   * @param $authToken
   */
  public function __construct(Guzzle $guzzle, $authToken = null)
  {
    $this->guzzle = $guzzle;

    if ($authToken)
    {
      $this->headers['Accept'] = 'application/vnd.twitchtv.v5+json';
      $this->headers['Client-ID'] = $authToken;
    }
  }

  /**
   * @param $method
   * @param $uri
   * @param $params
   * @return mixed|void
   */
  protected function request($method, $uri, $params = [])
  {
    $params['headers'] = $this->headers;

    if (is_array($this->pagination))
    {
      $joiner = (strpos($uri, '?') === false ? '?' : '&');
      $uri .= $joiner . implode('&', $this->pagination);
    }

    $result = $this->guzzle->request($method, $uri, $params);

    if ($result->getStatusCode() != 200)
    {
      // TODO: Throw Exception
      return;
    }

    $body = $result->getbody();

    $data = json_decode($body);

    return $data;
  }

  /**
   * Set the Limit and Offset (Pagination) up
   * @param
   */
  public function setPage($offset = 0, $limit = 100)
  {
    $this->pagination = [
      'limit' => limit,
      'offset' => $offset
    ];
  }
}