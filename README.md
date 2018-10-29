# Simple cURL Library

Simple wrapper for cURL using PHP.

## Installation

1. Native PHP
	- Please place `Curl.php` at your location you want.
	- Require the `Curl.php` at your php file.
	```php
	require 'Curl.php';
	```
2. Codeigniter
	- Please place `Curl.php` at `application/libraries` directory

## Usage

1. Native PHP

	```php
	//create instance
	$curl = new Curl();

	//http method GET
	$res = $curl->get('http://api.halo.com/users');

	var_dump($res);

	$res = $curl->get('http://api.halo.com/users', [
		'users_id' => 2
	]);

	var_dump($res);

	//http mehotd POST
	$res = $curl->post('http://api.halo.com/login', [
		'username' => 'yuana',
		'password' => 'yourpassword'
	]);

	var_dump($res);

	//http method PUT
	$res = $curl->put('http://api.halo.com/users', [
		'users_id' => 3,
		'users_name' => 'Yuana Andhika',
		'users_dept' => 'Android Developer'
	]);

	var_dump($res);

	//http method DELETE
	$res = $curl->delete('http://api.halo.com/users', [
		'users_id' => 3
	]);

	var_dump($res);

    // to upload file
    $res = $curl->upload('http://api.domain.com/upload', [
        'fieldA' => '/path/to/file/fileA.jpg',
        'fieldB' => '/path/to/file/fileB.jpg',
    ]);

    var_dump($res);

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

    ```php
    //load the library
    $this->load->library('curl');

    //http method GET
	$res = $this->curl->get('http://api.halo.com/users');

	var_dump($res);

    $res = $this->curl->get('http://api.halo.com/users', [
        'users_id' => 3
    ]);

    var_dump($res);

    //http method POST
    $res = $this->curl->post('http://api.halo.com/login', [
        'username' => 'jarjit',
        'password' => 'yourpassword'
    ]);

    var_dump($res);

    //http method PUT
    $res = $this->curl->put('http://api.halo.com/users', [
        'users_id' => 3,
        'users_name' => 'jarjit',
        'users_dept' => 'Web Developer'
    ]);

    var_dump($res);

    //http method DELETE
    $res = $this->curl->delete('http://api.halo.com/users', [
        'users_id' => 4
    ]);

    var_dump($res);

    // to upload file
    $res = $this->curl->upload('http://api.domain.com/upload', [
        'fieldA' => '/path/to/file/fileA.jpg',
        'fieldB' => '/path/to/file/fileB.jpg',
    ]);

    var_dump($res);

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

1. Proxy
2. Composer Package