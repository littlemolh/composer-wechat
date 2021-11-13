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

namespace littlemo\tool\wechat\pay;


/**
 * 微信支付公共方法
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-09-15
 * @version 2021-09-15
 */
class Common
{

    /**
     * 商户账号appid
     * 申请商户号的appid或商户号绑定的appid
     */
    protected $appid = null;

    /**
     * 商户号
     * 微信支付分配的商户号
     */
    protected $mchid = null;

    /**
     * 商户平台设置的密钥key
     */
    protected $key = null;

    /**
     *  请求Url
     */
    protected $url = '';

    /**
     * 接口返回结果
     */
    protected $result = null;




    /**
     * 获取API请求结果
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @return array
     */
    public function getResultData()
    {
        return $this->result;
    }

    /**
     * 制作随机字符串，不长于32位
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @return string
     */
    protected function createNonceStr()
    {
        $data = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        $str = '';
        for ($i = 0; $i < 32; $i++) {
            $str .= substr($data, rand(0, (strlen($data) - 1)), 1);
        }

        return $str;
    }

    /**
     * 制作签名
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param array $params
     * @return string
     */
    protected function createSign($params)
    {
        ksort($params);
        $string = '';
        foreach ($params as $key => $val) {
            if (!empty($val)) {
                $string .= (!empty($string) ? '&' : '') . $key . '=' . $val;
            }
        }

        $string .= '&key=' . $this->key;
        return MD5($string);
    }

    /****************************************************
     *  微信提交API方法，返回微信指定JSON或XML
     *  通用请求微信接口 [ 微信通讯 Communication ]
     ****************************************************/
    protected function request($url, $xml = null, $useCert = false)
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl,CURLOPT_HEADER,0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($xml)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
        }

        if ($useCert == true) {
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            //证书文件请放入服务器的非web目录下
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $this->sslCertPath);
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $this->sslKeyPath);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );

        $output = curl_exec($curl);
        $is_errno = curl_errno($curl);
        if ($is_errno) {
            return 'Errno' . $is_errno;
        }
        curl_close($curl);
        return $output;
    }
}
