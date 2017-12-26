<?php

namespace App\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Stream\Stream;
// use GuzzleHttp\Message\Request;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class ActiveResource {

    public $base_url = '';
    private $method = 'GET';
    private $uri = '';
    private $headers = [];
    private $form_params = [];
    private $config = [];
    private $query = [];
    private $multipart = [];
    private $json = [];
    private $guzzle;
    public $rules = [];
    private $appendUri = '';
    private $queryRules = [];
    private $filePath = '';
    private $urlEncode = true;

    private $debugInfoFormat = 'DateTime: {date_common_log}
                                Method: {method}
                                Host: {host}
                                URI: {uri}
                                Headers: {req_headers}
                                Target: {target}
                                Request Body: {req_body}
                                Response Code: {code}
                                Error: {error}
                                Response Headers: {res_headers}
                                Response Body: {res_body}';

    function __construct($config = []) {
        $this->config = $config;
        $this->setupConfig();

        $initial_config = array(
            'timeout' => 60,
            'http_errors' => true,
            'verify' => true,
            'curl' => [
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_TIMEOUT_MS => 0,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_RETURNTRANSFER => true,
            ],
        );

        try {

            $monolog = \Log::getMonolog();
            
            $stack = HandlerStack::create();
            $stack->push(
                Middleware::log(
                    $monolog,
                    new MessageFormatter($this->debugInfoFormat)
                )
            );

            $initial_config['handler'] = $stack;
        } catch(\Exception $e) {
            throw new ActiveResourceException($e->getResponse()->getBody(), 500);
        }

        $this->guzzle = new Client($initial_config);
    }

    private function setupConfig() {
        $this->setBaseUrl();
        $this->setUri();
        $this->setHeaders();
        $this->setFormParams();
        $this->setMultipart();
        $this->setJson();
        $this->setFilePath();
    }

    private function setMethod($method = 'GET') {
        $this->method = $method;
    }

    private function getMethod() {
        return $this->method;
    }

    public function setFilePath($filePath = '') {
        if($filePath != '') {
            $this->filePath = $filePath;
            return $this;
        }

        if(isset($this->config['file_path'])) {
            $this->setFilePath($this->config['file_path']);
        }
    }

    public function getFilePath() {
        return $this->filePath;
    }

    public function setBaseUrl($base_url = '') {

        if($base_url != '') {
            $this->base_url = $base_url;
            return $this;
        }

        if(isset($this->config['base_url'])) {
            $this->setBaseUrl($this->config['base_url']);
        }
    }

    public function getBaseUrl() {
        return $this->base_url;
    }

    public function setUri($uri = '') {
        
        if($uri != '') {
            $this->uri = $uri;
            return $this;
        }

        if(isset($this->config['uri'])) {
            $this->setUri($this->config['uri']);
        }
    }

    public function getUri() {
        return $this->uri;
    }

    public function setAppendUri($appendUri) {
        $this->appendUri = $appendUri;
        return $this;
    }

    public function getAppendUri() {
        return $this->appendUri;
    }

    public function setHeaders($headers = []) {

        if(sizeof($headers) > 0) {
            $this->headers = $headers;
            return $this;
        }

        if(isset($this->config['headers'])) {
            $this->setHeaders($this->config['headers']);
        }
    }

    public function addHeader($header = []) {

        if(sizeof($header) > 0) {
            $this->headers = array_merge($this->headers, $header);
        }
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function setFormParams($form_params = []) {

        if(sizeof($form_params) > 0) {
            $this->form_params = $form_params;
            return $this;
        }

        if(isset($this->config['form_params'])) {
            $this->setFormParams($this->config['form_params']);
        }
    }

    public function addFormParam($form_param = []) {

        if(sizeof($form_param) > 0) {
            $this->form_params = array_merge($this->form_params, $form_param);
        }
    }

    public function getFormParams() {
        return $this->form_params;
    }

    public function setMultipart($multipart = []) {

        if(sizeof($multipart) > 0) {
            $this->multipart = array_merge($this->multipart, $multipart);
            // $this->addHeader(['Content-Type' => 'multipart/form-data']);
            return $this;
        }

        if(isset($this->config['multipart'])) {
            $this->setMultipart($this->config['multipart']);
        }
    }

    public function getMultipart() {
        return $this->multipart;
    }

    public function addMultipart($multipart = []) {

        if(sizeof($multipart) > 0) {
            $this->multipart = array_merge($this->multipart, $multipart);
        }
    }

    public function setJson($json = []) {

        if(sizeof($json) > 0) {
            $this->json = $json;
            $this->addHeader(['Content-Type' => 'application/json']);
            return $this;
        }

        if(isset($this->config['json'])) {
            $this->setJson($this->config['json']);
        }
    }

    public function getJson() {
        return $this->json;
    }

    public function addJson($json = []) {

        if(sizeof($json) > 0) {
            $this->json = array_merge($this->json, $json);
        }
    }

    public function addCondition($condition = []) {

        if(sizeof($condition) > 0) {
            $this->query = array_merge($this->query, $condition);
        }
    }

    private function getFinalUrl() {
        
        $finalUrl = $this->getBaseUrl() . '/' . $this->getUri();

        if($this->getAppendUri() != '') {
            $finalUrl = $finalUrl . '/' . $this->getAppendUri();
        }
        \Log::info('finalUrl: ' . $finalUrl);
        return $finalUrl;
    }

    private function setUrlEncode($urlEncode) {
        $this->urlEncode = $urlEncode;
    }

    private function getUrlEncode() {
        return $this->urlEncode;
    }

    public function get($urlEncode = true) {

        $this->setUrlEncode($urlEncode);

        $this->setMethod();
        try {
            $result = $this->makeCall();
            $body = $result->getBody();
            return $body;
        } catch(ActiveResourceException $e) {
            throw new ActiveResourceException($e->getMessage(), $e->getCode());
        }
    }

    public function post() {

        $this->setMethod('POST');
        try {
            $result = $this->makeCall();
            $body = $result->getBody();
            return $body;
        } catch(ActiveResourceException $e) {
            throw new ActiveResourceException($e->getMessage(), $e->getCode());
        }
    }

    public function put() {

        $this->setMethod('PUT');
        try {
            return $this->makeCall();
        } catch(ActiveResourceException $e) {
            throw new ActiveResourceException($e->getMessage(), $e->getCode());
        }
    }

    public function delete() {

        $this->setMethod('DELETE');
        try {
            return $this->makeCall();
        } catch(ActiveResourceException $e) {
            throw new ActiveResourceException($e->getMessage(), $e->getCode());
        }
    }

    public function downloadFile() {

        // die($this->getFilePath());
        // $resource = Stream::factory(fopen($this->getFilePath(), 'w'));
        $this->guzzle->request($this->getMethod(), $this->getBaseUrl(), ['sink' => $this->getFilePath()]);
    }

    public function setQueryRules($queryRules) {
        $this->queryRules = $queryRules;
        return $this;
    }

    private function makeCall() {

        $response = new Response();

        $options = array(
            // 'query' => $this->query,
            'headers' => $this->getHeaders(),
        );

        if(sizeof($this->query)) {
            $options['query'] = $this->query;
        }

        if(sizeof($this->getFormParams()) > 0) {
            $options['form_params'] = $this->getFormParams();
        } else if(sizeof($this->getMultipart()) > 0) {
            $options['multipart'] = $this->getMultipart();
        } else if(sizeof($this->getJson()) > 0) {
            $options['json'] = $this->getJson();
        }
        
        try {
            $response = $this->guzzle->request($this->getMethod(),
                $this->getFinalUrl(),
                $options
            );

        } catch (RequestException $e) {
            if($e->getResponse() == null) {
                throw new ActiveResourceException($e->getMessage(), 500);    
            }
            throw new ActiveResourceException($e->getResponse()->getBody(), $e->getCode());
        } catch (ClientException $e) {
            if($e->getResponse() == null) {
                throw new ActiveResourceException($e->getMessage(), 500);    
            }
            throw new ActiveResourceException($e->getResponse()->getBody(), $e->getCode());
        } catch (ServerException $e) {
            if($e->getResponse() == null) {
                throw new ActiveResourceException($e->getMessage(), 500);    
            }
            throw new ActiveResourceException($e->getResponse()->getBody(), $e->getCode());
        }

        return $response;
    }
}

class ActiveResourceException extends \Exception {

    private $result = '';
    protected $code = 0;

    function __construct($result, $code) {
        $this->result = $result;
        $this->code = $code;
        
        parent::__construct($this->result, $this->code);
    }

    public function getErrors() {

        $resultArray = json_decode($this->result, true);

        $errorMessage = $resultArray;
        if(isset($resultArray['error']['message'])) {
            $errorMessage = $resultArray['error']['message'];
        }
        
        return json_encode(['error' => [
            'code' => $this->code,
            'message' => $errorMessage,
            'type' => '',
            ]
        ]);
    }

    public function getErrorCode() {
        return $this->code;
    }

}