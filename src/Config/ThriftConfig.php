<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/3/23
 * Time: 11:45
 */

namespace xltxlm\thrift\Config;

/**
 * 服务器的链接配置
 * Class ThriftConfig
 * @package xltxlm\thrift\Config
 */
class ThriftConfig
{
    //服务器类型定义
    const HTTP = 'http';
    const HTTPS = 'https';

    /** @var string 服务端的登录地址 */
    protected $hosturl = "localhost";
    /** @var string 服务端的接口ip地址 */
    protected $host = "127.0.0.1";
    /** @var int 服务端提供的端口 */
    protected $port = 80;
    /** @var int 服务端提供的端口 */
    protected $httpsport = 443;
    /** @var string 类型 */
    protected $type = self::HTTPS;

    /**
     * @return int
     */
    public function getHttpsport(): int
    {
        return $this->port;
    }

    /**
     * @param int $httpsport
     * @return ThriftConfig
     */
    public function setHttpsport(int $httpsport): ThriftConfig
    {
        $this->httpsport = $httpsport;
        return $this;
    }


    /**
     * @return string
     */
    public function getHosturl(): string
    {
        return $this->hosturl;
    }

    /**
     * @param string $hosturl
     * @return $this
     */
    public function setHosturl(string $hosturl)
    {
        $this->hosturl = $hosturl;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return static
     */
    public function setHost(string $host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return static
     */
    public function setPort(int $port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        if ($type) {
            $this->type = $type;
        }
        return $this;
    }

}