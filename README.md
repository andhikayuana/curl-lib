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

	//override timeout [default 30]
	$curl->timeout = 25;

	//override redirection [default true]
	$curl->isRedirect = false;

	//override user agent [default from http user agent]
	$curl->userAgent = 'Android App 1.1';

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

    //override timeout [default 30]
	$this->curl->timeout = 25;

	//override redirection [default true]
	$this->curl->isRedirect = false;

	//override user agent [default from http user agent]
	$this->curl->userAgent = 'Android App 1.1';

    ```

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## Todos

1. Upload File
2. Proxy
3. Composer Package

## License

MIT License

Copyright (c) 2017 Andhika Yuana

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.