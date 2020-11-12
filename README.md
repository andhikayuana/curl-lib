# Simple cURL Library

Simple wrapper for cURL using PHP.

> **_NOTE:_**  Supported composer package from [v2.0.0](https://github.com/andhikayuana/curl-lib/tree/v2.0.0), If you need native version you can check [v1.0.0](https://github.com/andhikayuana/curl-lib/tree/v1.0.0)

## Installation

```bash
composer require andhikayuana/curl-lib
```


## Usage

Create instance
```php
$curl = new \Yuana\Curl();
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

## Contributing

Feel free to check [CONTRIBUTING.md](./CONTRIBUTING.md) file

## Todos

- [ ] Proxy
- [x] Composer Package