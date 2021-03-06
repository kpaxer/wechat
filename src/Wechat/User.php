<?php
namespace Overtrue\Wechat;

use Overtrue\Wechat\Utils\Bag;

/**
 * 用户
 */
class User
{

    /**
     * Http对象
     *
     * @var Http
     */
    protected $http;

    const API_GET       = 'https://api.weixin.qq.com/cgi-bin/user/info';
    const API_LIST      = 'https://api.weixin.qq.com/cgi-bin/user/get';
    const API_GROUP     = 'https://api.weixin.qq.com/cgi-bin/groups/getid';
    const API_REMARK    = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark';
    const API_OAUTH_GET = 'https://api.weixin.qq.com/sns/userinfo';


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
     * 读取用户信息
     *
     * @param string $openId
     * @param string $lang
     *
     * @return array
     */
    public function get($openId = null, $lang = 'zh_CN')
    {
        if (empty($openId)) {
            return $this->all();
        }

        $params = array(
                   'openid' => $openId,
                   'lang'   => $lang,
                  );

        return new Bag($this->http->get(self::API_GET, $params));
    }

    /**
     * 获取用户列表
     *
     * @param string $nextOpenId
     *
     * @return Bag
     */
    public function lists($nextOpenId = null)
    {
        $params = array('next_openid' => $nextOpenId);

        return new Bag($this->http->get(self::API_LIST, $params));
    }

    /**
     * 修改用户备注
     *
     * @param string $openId
     * @param string $remark 备注
     *
     * @return boolean
     */
    public function remark($openId, $remark)
    {
        $params = array(
                   'openid' => $openId,
                   'remark' => $remark,
                  );

        return $this->http->jsonPost(self::API_REMARK, $params);
    }

    /**
     * 获取用户所在分组
     *
     * @param string $openId
     *
     * @return int
     */
    public function group($openId)
    {
        return $this->getGroup($openId);
    }

    /**
     * 获取用户所在的组
     *
     * @param string $openId
     *
     * @return integer
     */
    public function getGroup($openId)
    {
        $params = array(
                   'openid' => $openId,
                  );

        $response = $this->http->jsonPost(self::API_GROUP, $params);

        return $response['groupid'];
    }
}