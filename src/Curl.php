<?php

namespace Yuana;

/**
 * Simple wrapper for cURL
 * @author Yuana <andhikayuana@gmail.com>
 * @since November, 15 2020
 * @license MIT
 * 
 * =================================================
 * 
 * Create instance
 * ```php
 * require 'vendor/autoload.php';
 * 
 * $curl = new \Yuana\Curl();
 * ```
 * 
 * HTTP GET method
 * ```php
 * $res = $curl->get('http://api.halo.com/users');
 * 
 * // using query
 * // http://api.halo.com/users?users_id=2
 * $res = $curl->get('http://api.halo.com/users', [
 *     'users_id' => 2
 * ]);
 * ```
 * 
 * HTTP POST method
 * ```php
 * $res = $curl->post('http://api.halo.com/login', [
 *     'username' => 'yuana',
 *     'password' => 'yourpassword'
 * ]);
 * ```
 * 
 * HTTP PUT method
 * ```php
 * $res = $curl->put('http://api.halo.com/users', [
 *     'users_id' => 3,
 *     'users_name' => 'Yuana Andhika',
 *     'users_dept' => 'Android Developer'
 * ]);
 * ```
 * 
 * HTTP DELETE method
 * ```php
 * $res = $curl->delete('http://api.halo.com/users', [
 *     'users_id' => 3
 * ]);
 * ```
 * 
 * Uploading file
 * ```php
 * $res = $curl->upload('http://api.domain.com/upload', [
 *     'fieldA' => '/path/to/file/fileA.jpg',
 *     'fieldB' => '/path/to/file/fileB.jpg',
 * ]);
 * ```
 * 
 * Configuration
 * ```php
 * //override timeout [default 30]
 * $curl->timeout = 25;
 * 
 * //override redirection [default true]
 * $curl->isRedirect = false;
 * 
 * //override user agent [default from http user agent]
 * $curl->userAgent = 'Android App 1.1';
 * 
 * //override headers
 * $curl->headers = [
 *     'Authorization' => 'Bearer yourtokenhere'
 * ];
 * ```
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
