<?php
namespace Overtrue\Wechat;

/**
 * 颜色接口
 */
class Color
{
   /**
     * Http对象
     *
     * @var Http
     */
    protected $http;

    /**
     * Cache对象
     *
     * @var Cache
     */
    protected $cache;

    const API_LIST = 'https://api.weixin.qq.com/card/getcolors';


    /**
     * constructor
     *
     * @param string $appId
     * @param string $appSecret
     */
    public function __construct($appId, $appSecret)
    {
        $this->http = new Http(new AccessToken($appId, $appSecret));
    }

    /**
     * 获取颜色列表
     *
     * @return array
     */
    public function lists()
    {
        $key = 'overtrue.wechat.colors';

        return $this->cache->get($key, function($key) {

            $http  = new Http(new AccessToken($this->appId, $this->appSecret));

            $result = $http->get(self::API_LIST);

            $this->cache->set($key, $result['colors'], 86400);// 1 day

            return $result['colors'];
        });
    }
}