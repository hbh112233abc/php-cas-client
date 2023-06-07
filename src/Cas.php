<?php
namespace bingher\phpcas;

/**
 * CAS 客户端
 * $cas = new \bingher\phpcas\Cas($conf);
 *
 * 1 登录
 * $user = $cas->login();
 *
 * 2 获取用户信息
 * $user = $cas->user();
 *
 * 3 退出
 * $cas->logout();
 */
class Cas
{
    protected $conf = [
        'debug'        => true,
        'host'         => 'cas.server.com',
        'real_hosts'   => ['cas.server.com'],
        'base_uri'     => 'http://cas.server.com',
        'context'      => '/cas',
        'port'         => 8443,
        'ca_cert_file' => '',
        'log_file'     => '',
        'cas_version'  => '3.0',
        'lang'         => 'CAS_Languages_ChineseSimplified',
    ];

    public function __construct($config = [])
    {
        if (!function_exists('curl_init')) {
            throw new \Exception('You need to install the PHP module curl to be able to use CAS authentication.');
        }

        $this->conf = array_merge($this->conf, $config);
        if (!empty($this->conf['log_file'])) {
            $logPath = dirname($this->conf['log_file']);
            if (!is_dir($logPath)) {
                mkdir($logPath, '0775', true);
            }
            \phpCAS::setDebug($this->conf['log_file']); //开启调试模式,设置日志文件路径
        }

        if ($this->conf['debug']) {
            \phpCAS::setVerbose(true); //是否显示错误信息
        } else {
            \phpCAS::setVerbose(false); //是否显示错误信息
        }
        $this->conf['base_uri']   = sprintf('http://%s:%d', $this->conf['host'], $this->conf['port']);
        $this->conf['real_hosts'] = [$this->conf['host']];
        \phpCAS::client(
            $this->conf['cas_version'],
            $this->conf['host'],
            intval($this->conf['port']),
            $this->conf['context'],
            $this->conf['base_uri'],
        ); //配置客户端
        \phpCAS::setLang($this->conf['lang']); //支持中文
        \phpCAS::setNoCasServerValidation();
        if (is_file($this->conf['ca_cert_file'])) {
            \phpCAS::setCasServerCACert($this->conf['ca_cert_file'], false);
        }
        \phpCAS::handleLogoutRequests(); //同步退出
    }

    /**
     * 登录操作
     * 测试环境 测试账号test2 123456
     * @return array 用户信息
     */
    public function login()
    {
        \phpCAS::forceAuthentication(); //调用登录页面
        return self::user();
    }

    /**
     * 重新授权
     *
     */
    public function renew()
    {
        \phpCAS::renewAuthentication();
        return self::user();
    }

    /**
     * 获得用户信息
     * @return array 用户信息
     */
    public static function user()
    {
        if (!\phpCAS::isAuthenticated()) {
            return false;
        }

        return [
            'userInfo' => \phpCAS::getUser(),
            //获取用户信息
            'userAttr' => \phpCAS::getAttributes(), //获取用户附加信息
        ];
    }

    /**
     * 退出
     */
    public function logout($url = '')
    {
        if (empty($url)) {
            $url = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["SERVER_NAME"];
        }
        \phpCAS::logoutWithRedirectService($url);
    }
}
