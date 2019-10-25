# php-cas-client
php cas client,create from phpCAS

## composer install

```
composer require bingher/php-cas-client
```

## how to use at thinkphp5.1

> ### create config file `config/cas.php`
```
<?php
return [
    'debug'      => true,
    'host'       => 'cas.server.com',
    'context'    => '/cas',
    'port'       => 8443,
    'ca_cert_file' => \Env::get('config_path') . '/cas.pem',
    'log_file'   => \Env::get('runtime_path') . '/log/' . date('Ym') . '/' . date('d') . '_cas.log',
];
```
|field|type|default|remark|
|-|-|-|-|
|debug|boolean|true|debug=true and log_file=filepath then the log will write,and show error message|
|host|string|cas.server.com|the domain of cas server|
|context|string|/cas|the cas server request url path,ex:https://cas.server.com/cas|
|port|int|8443|set the port of cas server|
|ca_cert_file|string|''|if `ca_cert_file` not empty and ca_cert_file is exist,then will setCasServerCACert()|
|log_file|string|''|if not empty and debug=true,will write the log at log_file|
|cas_version|string|3.0|cas_version options:`1.0`,`2.0`,`3.0`,`S1`|
|lang|string|CAS_Languages_ChineseSimplified|message language support,options:`CAS_Languages_English`,`CAS_Languages_French`,`CAS_Languages_Greek`,`CAS_Languages_German`,`CAS_Languages_Japanese`,`CAS_Languages_Spanish`,`CAS_Languages_Catalan`,`CAS_Languages_ChineseSimplified`|

> ### import at controller `app\index\controller\Test.php`
```
<?php
namespace app\index\controller;

use bingher\phpcas\Cas;
use think\facade\Config;

class Test
{
    /**
     * http://www.tp.com/index/test/login
     * 测试环境 测试账号test2 123456
     */
    public function login()
    {
        $cas  = new Cas(Config::get('cas.'));
        $user = $cas->login();
        //TODO you login logic
    }

    /**
     * 退出登录
     * @return [type] [description]
     */
    public function logout()
    {
        $callbackUrl = 'http://www.tp.com'; //如果设置为空默认回调到网站首页
        $cas = new Cas(Config::get('cas.'));
        $cas->logout($callbackUrl);
    }

}
```
