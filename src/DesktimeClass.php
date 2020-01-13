<?php

namespace Samaphp\Desktime;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Desktime.
 */
class DesktimeClass {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * The credentials.
   *
   * @var array
   */
  private $credentials;

  /**
   * The base URL of the targeted API.
   *
   * @var string
   */
  private $url;

  /**
   * The needful response format.
   *
   * @var string
   */
  private $format;

  /**
   * Constructs a new object.
   */
  public function __construct() {
    $this->httpClient = new HttpClient();
    $this->credentials = [
      'api_key' => '',
    ];

    // We will set the default URL value.
    $url = 'https://desktime.com/api/v2';
    $this->setUrl($url);

    // Set the default format.
    $this->setFormat('json');
  }

  /**
   * Set the API URL value.
   */
  public function setUrl($url) {
    $this->url = $url;
    return $this;
  }

  /**
   * Set the authorization value.
   */
  public function setCredentials($credentials) {
    // Streak using only username.
    $this->credentials['api_key'] = $credentials['api_key'];
    return $this;
  }

  /**
   * Set the needful format.
   */
  public function setFormat($format) {
    $allowed_formats = ['json', 'jsonp', 'plist'];
    if (in_array(mb_strtolower($format), $allowed_formats)) {
      $this->format = mb_strtolower($format);
    }
    return $this;
  }

  /**
   * Build the full URL of the needful service.
   *
   * @param string $service_path
   *   The direct service path in the integration URL.
   *
   * @return string
   *   The full URL.
   */
  public function buildUrl($service_path, $query = []) {
    $url = $this->url;
    $url .= '/' . $this->format;
    $url .= '/' . $service_path;
    $url .= '?' . $this->buildQuery($query);
    return $url;
  }

  /**
   * To build the URL query string.
   *
   * @param array $query
   *   The URL query.
   *
   * @return string
   *   The full URL query.
   */
  public function buildQuery(array $query) {
    $query['apiKey'] = $this->credentials['api_key'];
    return http_build_query($query);
  }

  /**
   * To make the GET call.
   *
   * We can hack this function to provide mocks or caching the results.
   *
   * @param string $url
   *   The full service URL without query parameters.
   *
   * @param string $query
   *   The already build URL query.
   *
   * @return object
   *   The call result.
   */
  public function get($url, $query) {
    // @TODO: Making this function cachable.
    // Making the call.
    $data = $this->makeGetCall($url, $query);
    return $data;
  }

  /**
   * Making the real GET call.
   */
  public function makeGetCall($url, $query) {
    $client = $this->httpClient;
    try {
      // @TODO: Allow override header values.
      $request = $client->get($url, [
        'verify' => FALSE,
        // 'timeout' => 1,
      ]);

      // If the HTTP status code is OK we will parse the body.
      if ($request->getStatusCode() == 200) {
        $result = (object) [
          'pass' => TRUE,
          'code' => $request->getStatusCode(),
          // 'headers' => $request->getHeaders(), //.
          'body' => (object) json_decode((string) $request->getBody()),
        ];
      }
      else {
        $result = (object) [
          'pass' => FALSE,
          'code' => $request->getStatusCode(),
          'body' => [],
        ];
      }
    }
    catch (RequestException $e) {
      if ($e->getResponse() && $e->getResponse()->getBody() && $e->getResponse()->getBody()->getContents()) {
        $body = json_decode($e->getResponse()->getBody()->getContents());
        // $e->getResponse()->getReasonPhrase()
        // $e->getResponse()->getStatusCode() //.
        $body->http_response = $e->getResponse()->getReasonPhrase();
        $response_code = $e->getResponse()->getStatusCode();
      }
      else {
        // Can not fetch the response body contents.
        $body = 'FAILED_TO_FETCH_BODY_CONTENTS';
        $response_code = 0;
      }
      $result = (object) [
        'pass' => FALSE,
        'code' => $response_code,
        'body' => $body,
      ];
    }

    return $result;
  }

}
