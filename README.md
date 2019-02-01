# php-cas-client
php cas client,create from phpCAS

## composer install

```
composer require bingher/php-cas-client dev-master
```

## how to use at thinkphp5.1

1.create config file `config/cas.php`
```
<?php
return [
    'debug'      => true,
    'host'       => 'cas.server.com',
    'real_hosts' => ['cas.server.com'],
    'context'    => '/cas',
    'port'       => 8443,
    'ca_cert_file' => \Env::get('config_path') . '/cas.pem',
    'log_file'   => \Env::get('runtime_path') . '/log/' . date('Ym') . '/' . date('d') . '_cas.log',
];
```
2.import at controller `app\index\controller\Test.php`
```
<?php
namespace app\index\controller;

use bingher\phpcas\Cas;
use think\facade\Config;

class Test
{
    /**
     * http://www.tp.com/index/test/login
     * @return [type] [description]
     * 测试环境 测试账号test2 123456
     */
    public function login()
    {
        $cas  = new Cas(Config::get('cas.'));
        $user = $cas->login();
        return view();
    }

    /**
     * 退出登录
     * @return [type] [description]
     */
    public function logout()
    {
        $cas = new Cas(Config::get('cas.'));
        $cas->logout();
    }

}
```
