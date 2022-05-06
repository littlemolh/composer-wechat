<?php

// +----------------------------------------------------------------------
// | Little Mo - Tool [ WE CAN DO IT JUST TIDY UP IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2021 http://ggui.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: littlemo <25362583@qq.com>
// +----------------------------------------------------------------------

namespace littlemo\wechat\pay;

use littlemo\wechat\core\lWechatException;

/**
 * 微信支付公共方法
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-09-15
 * @version 2021-09-15
 */
class  Base
{

    /**
     * 小程序的appid
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $appid = null;


    /**
     * 微信支付商户号
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $mchid = null;

    /**
     * 微信支付商户APIV2支付密钥
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $apiv2key = null;
    /**
     * 微信支付商户APIV3支付密钥
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $apiv3key = null;

    /**
     * 微信支付证书cert路径
     */
    protected $sslCertPath = '';

    /**
     * 微信支付证书key路径
     */
    protected $sslKeyPath = '';

    /**
     * 构造函数
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-05
     * @version 2021-11-05
     * @param string $appid     公众号/小程序的appid
     * @param string $secret    公众号/小程序唯一凭证密钥，即 AppSecret，获取方式同 appid
     * @param string $mchid     商户号
     * @param string $apiv2key       商户号支付密钥
     */
    public function __construct($appid = null, $mchid = null, $apiv2key = null, $certPath = '', $keyPath = '')
    {
        $this->appid = $appid;

        $this->mchid = $mchid;

        $this->apiv2key = $apiv2key;

        $this->sslCertPath = $certPath;
        $this->sslKeyPath = $keyPath;
    }

    public function setApiv3key($key)
    {
        $this->apiv3key = $key;
        return $this;
    }

    /**
     * 整理接口返回结果
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     * @param sring $result
     * @return void
     */
    protected function parseResult($result = '', $error_field = 'result_code', $error_code = 'SUCCESS')
    {

        $code = $result['code'];
        $content = $result['content'];

        if ($code !== 200 || $content === false) {
            throw new lWechatException($result['error_des']);
        }
        $content = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
        $content = $content !== false ? json_encode($content) : $content;
        $content = $content !== false ? json_decode($content) : $content;
        if ($content->$error_field != $error_code) {
            throw new lWechatException($content->err_code_des, $content->err_code, $content);
        }
        return $content;
    }
}
