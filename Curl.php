<?php

/**
 * Simple wrapper for cURL
 * @author Yuana <andhikayuana@gmail.com>
 * @since March, 3 2017
 * @license MIT
 *
 * --------------------------------------
 * How to use with native PHP
 *
 * require 'Curl.php';
 * and make instance :
 *
 * $curl = new Curl();
 * $curl->get('http://api.halo.com/users,array(
 *      'users_id' => 3
 * ));
 * --------------------------------------
 *
 * --------------------------------------
 * How to use this library for Codeigniter
 * --------------------------------------
 *
 * copy Curl.php to `application/libraries`
 *
 * load the library
 * `$this->load->library('curl');`
 * --------------------------------------
 *
 * HEADERS
 * add some header like this
 * $this->curl->headers = array(
 *      'X-API-KEY' => 'randomapikeyhere',
 *      'some-header-key' => 'some-header-value'
 * );
 *
 * POST
 * $res = $this->curl->post('http://api.domain.com/login', array(
 * 	'email' => 'jarjit@domain.com',
 * 	'password' => 'helloworld'
 * ));
 *
 * var_dump($res);
 *
 * GET
 * $res = $this->curl->get('http://api.domain.com/trips', array(
 * 	'trips_id' => 2
 * ));
 *
 * var_dump($res);
 *
 * another method
 * PUT
 * $this->curl->put($url, array('field' => 'val'));
 *
 * DELETE
 * $this->curl->delete($url, array('field' => 'val'));
 *
 * TIMEOUT [default 30 sec.]
 * $this->curl->timeout = 60;
 *
 * REDIRECT [default TRUE]
 * $this->curl->isRedirect = false;
 *
 * HEADERS
 * $this->curl->headers = array(
 *     'Authorization' => 'Bearer yourtokenhere'
 * );
 *
 * UPLOAD
 * $this->curl->upload('http://api.domain.com/upload', array(
 *     'fieldA' => '/path/to/file/fileA.jpg',
 *     'fieldB' => '/path/to/file/fileB.jpg',
 * ));
 *
 * TODO :
 * 1. proxy
 * 2. composer package
 */
class Curl {

    const VERSION = 'Curl-PHP-' . PHP_VERSION;

    /**
     * @var http methods
     */
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    /**
     * @var mime type for header
     */
    const MIME_X_WWW_FORM = 'application/x-www-form-urlencoded';
    const MIME_FORM_DATA = 'multipart/form-data';
    const MIME_JSON = 'application/json';

    /**
     * [$headers for request]
     * @var array
     */
    public $headers = array();

    /**
     * [$timeout for request]
     * @var integer second
     */
    public $timeout = 30;

    /**
     * [$error]
     * @var string
     */
    private $error = '';

    /**
     * [$request for handle cURL request]
     * @var resource
     */
    private $request;

    /**
     * [$isRedirect for determines whether or not should redirect]
     * @var boolean
     */
    public $isRedirect = true;

    /**
     * [$userAgent for the request]
     * @var string
     */
    public $userAgent;

    /**
     * [$files property for upload file]
     * @var array
     */
    private $files = array();

    public function __construct() {

        $this->userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : self::VERSION;
    }

    /**
     * [Make http GET request]
     * @param  string $url
     * @param  array  $vars queryParams
     * @return string response
     */
    public function get($url, $vars = array()) {
        if (!empty($vars)) {
            $url .= (stripos($url, '?') !== false) ? '&' : '?';
            $url .= (is_string($vars)) ? $vars : http_build_query($vars, '', '&');
        }
        return $this->request(self::GET, $url);
    }

    /**
     * [Make http POST request]
     * @param  string $url
     * @param  array  $vars postFields
     * @return string response
     */
    public function post($url, $vars = array()) {
        return $this->request(self::POST, $url, $vars);
    }

    /**
     * [Make http PUT request]
     * @param  string $url
     * @param  array  $vars postFields
     * @return string response
     */
    public function put($url, $vars = array()) {
        return $this->request(self::PUT, $url, $vars);
    }

    /**
     * [Make http DELETE request]
     * @param  string $url
     * @param  array  $vars postFields
     * @return string response
     */
    public function delete($url, $vars = array()) {
        return $this->request(self::DELETE, $url, $vars);
    }

    /**
     * [upload]
     * @param  string $url
     * @param  array  $vars [file as key->value]
     * @return string response
     */
    public function upload($url, $vars = array()) {

        foreach ($vars as $fieldName => $fileName) {
            $this->files[] = [
                'type'      => 'file',
                'fieldname' => $fieldName,
                'file'      => $fileName,
                'basename'  => null,
                'mime_type' => null,
            ];
        }

        $this->headers = array(
            'Content-Type' => self::MIME_FORM_DATA
        );

        return $this->request(self::POST, $url, $this->generateBoundary());
    }

    /**
     * [generateBoundary]
     * @return boundary
     */
    private function generateBoundary()
    {
        $eol = PHP_EOL;
        $boundary = '----' . self::VERSION . md5(microtime());
        $this->headers['Content-Type'] = self::MIME_FORM_DATA . "; boundary=$boundary";
        $data = [];

        foreach ($this->files as $file) {
            if ($file['type'] === 'file') {
                if (empty($file['basename'])) {
                    $file['basename'] = basename($file['file']);
                }
                if (empty($file['mime_type'])) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $file['mime_type'] = finfo_file($finfo, $file['file']);
                    finfo_close($finfo);
                }
            }
            if (empty($file['mime_type'])) {
                $file['mime_type'] = 'application/octet-stream';
            }
            $data[] = "--$boundary$eol";
            $data[] = "Content-Disposition: form-data; name=\"{$file['fieldname']}\"; filename=\"{$file['basename']}\"$eol";
            $data[] = "Content-Type: {$file['mime_type']}$eol$eol";
            $data[] = ($file['type'] === 'file' ? file_get_contents($file['file']) : $file['file']) . $eol;
        }
        $data[] = "--$boundary--$eol$eol";
        return implode('', $data);
    }

    /**
     * request
     * @param  string $method GET|POST|PUT|DELETE
     * @param  string $url
     * @param  array  $vars
     * @return string response
     */
    private function request($method, $url, $vars = array()) {
        $this->error = '';
        $this->request = curl_init();
        if (is_array($vars))
            $vars = http_build_query($vars, '', '&');

        $this->setRequestMethod($method);
        $this->setRequestOptions($url, $vars);
        $this->setRequestHeaders();

        return $this->generateResponse();
    }

    /**
     * [setRequestMethod]
     * @param string $method
     * @return void
     */
    private function setRequestMethod($method) {

        switch ($method) {
            case self::GET:
                curl_setopt($this->request, CURLOPT_HTTPGET, true);
                break;

            case self::POST:
                curl_setopt($this->request, CURLOPT_POST, true);
                break;

            default:
                curl_setopt($this->request, CURLOPT_CUSTOMREQUEST, $method);
                break;
        }
    }

    /**
     * [setRequestOptions]
     * @param string $url
     * @param string $vars
     * @return void
     */
    private function setRequestOptions($url, $vars) {

        curl_setopt($this->request, CURLOPT_URL, $url);
        curl_setopt($this->request, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->request, CURLOPT_HEADER, true);
        curl_setopt($this->request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->request, CURLOPT_USERAGENT, $this->userAgent);

        if ($this->isRedirect)
            curl_setopt($this->request, CURLOPT_FOLLOWLOCATION, true);

        if (!empty($vars))
            curl_setopt($this->request, CURLOPT_POSTFIELDS, $vars);
    }

    /**
     * [setRequestHeaders]
     * @return void
     */
    private function setRequestHeaders() {
        $headers = array();
        foreach ($this->headers as $key => $value) {
            $headers[] = $key . ': ' . $value;
        }
        curl_setopt($this->request, CURLOPT_HTTPHEADER, $headers);
    }

    /**
     * [generateResponse]
     * @return string response
     */
    private function generateResponse() {

        $response = curl_exec($this->request);
        $header_size = curl_getinfo($this->request, CURLINFO_HEADER_SIZE);
        $content = substr($response, $header_size);

        if (!$response) {
            $this->error = curl_errno($this->request) . ' - ' . curl_error($this->request);
            $content = $this->error;
        }

        curl_close($this->request);

        return $content;
    }

}
