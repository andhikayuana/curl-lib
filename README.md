# Simple cURL Library

Simple wrapper for cURL using PHP.

> **_NOTE:_**  If you need native version you can check [v1.0.0](https://github.com/andhikayuana/curl-lib/tree/v1.0.0)

## Installation

1. Native PHP
	- Please place `Curl.php` at your location you want.
	- Require the `Curl.php` at your php file.
	```php
    require 'Curl.php';
    // or
    require_once 'Curl.php';
	```
2. Codeigniter
	- Please place `Curl.php` at `application/libraries` directory


## Usage

1. Native PHP

    Create instance
	```php
    $curl = new Curl();
    ```

    HTTP GET method
    ```php
	$res = $curl->get('http://api.halo.com/users');

    // using query
    // http://api.halo.com/users?users_id=2
	$res = $curl->get('http://api.halo.com/users', [
		'users_id' => 2
    ]);
    ```

    HTTP POST method
    ```php
	$res = $curl->post('http://api.halo.com/login', [
		'username' => 'yuana',
		'password' => 'yourpassword'
    ]);
    ```

    HTTP PUT method
    ```php
	$res = $curl->put('http://api.halo.com/users', [
		'users_id' => 3,
		'users_name' => 'Yuana Andhika',
		'users_dept' => 'Android Developer'
    ]);
    ```

    HTTP DELETE method
    ```php
	$res = $curl->delete('http://api.halo.com/users', [
		'users_id' => 3
    ]);
    ```

    Uploading file
    ```php
    $res = $curl->upload('http://api.domain.com/upload', [
        'fieldA' => '/path/to/file/fileA.jpg',
        'fieldB' => '/path/to/file/fileB.jpg',
    ]);
    ```

    Configuration
    ```php
	//override timeout [default 30]
	$curl->timeout = 25;

	//override redirection [default true]
	$curl->isRedirect = false;

	//override user agent [default from http user agent]
	$curl->userAgent = 'Android App 1.1';

    //override headers
    $curl->headers = [
      'Authorization' => 'Bearer yourtokenhere'
    ];
	```
2. Codeigniter
    Load the library from /application/library/Curl.php
    ```php
    $this->load->library('curl');
    ```

    HTTP GET method
    ```php
    $res = $this->curl->get('http://api.halo.com/users');
    
    // using query
    // http://api.halo.com/users?users_id=2
    $res = $this->curl->get('http://api.halo.com/users', [
        'users_id' => 3
    ]);
    ```

    HTTP POST method
    ```php
    $res = $this->curl->post('http://api.halo.com/login', [
        'username' => 'jarjit',
        'password' => 'yourpassword'
    ]);
    ```

    HTTP PUT method
    ```php
    $res = $this->curl->put('http://api.halo.com/users', [
        'users_id' => 3,
        'users_name' => 'jarjit',
        'users_dept' => 'Web Developer'
    ]);
    ```

    HTTP DELETE method
    ```php
    $res = $this->curl->delete('http://api.halo.com/users', [
        'users_id' => 4
    ]);
    ```

    Uploading file
    ```php
    $res = $this->curl->upload('http://api.domain.com/upload', [
        'fieldA' => '/path/to/file/fileA.jpg',
        'fieldB' => '/path/to/file/fileB.jpg',
    ]);
    ```

    Configuration
    ```php
    //override timeout [default 30]
	$this->curl->timeout = 25;

	//override redirection [default true]
	$this->curl->isRedirect = false;

	//override user agent [default from http user agent]
	$this->curl->userAgent = 'Android App 1.1';

    //override headers
    $this->curl->headers = [
      'Authorization' => 'Bearer yourtokenhere'
    ];

    ```

## Contributing

Feel free to check [CONTRIBUTING.md](./CONTRIBUTING.md) file

## Todos

- [ ] Proxy
- [x] Composer Package