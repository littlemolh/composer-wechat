<?php

namespace littlemo\wechat\mp;

use littlemo\wechat\Base;
use littlemo\utils\HttpClient;

/**
 * TODO 小程序码
 * 注意事项 https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/qr-code.html#%E6%B3%A8%E6%84%8F%E4%BA%8B%E9%A1%B9
 * @author sxd
 * @Date 2019-07-25 10:43
 */
class QRCode extends Base
{

    /**
     * 获取小程序二维码
     *
     * @description 适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制。
     * 官方文档：https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.createQRCode.html
     * 
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-07-06
     * @version 2021-07-06
     * @param string $access_token  接口调用凭证
     * @param string $data['path']          扫码进入的小程序页面路径,最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}。
     * @param string $data['width']         二维码的宽度,默认值：430,单位 px。最小 280px，最大 1280px
     * @return array
     */
    public function createQRCode($access_token, $data)
    {

        //POST https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=ACCESS_TOKEN

        $url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode";

        //请求参数
        $body = array_merge([
            'path' => null,
            'width' => 430,
        ], $data);

        $params = compact('access_token');


        return $this->init_result((new HttpClient())->post($url, json_encode($body), $params));
    }

    /**
     * 获取小程序码
     *
     * @description 适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制
     * 官方文档：https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.get.html
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-03-11
     * @version 2021-03-11
     * @param string $access_token  接口调用凭证
     * @param string $data['path']          扫码进入的小程序页面路径，最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}。
     * @param string $data['width']         二维码的宽度，单位 px。最小 280px，最大 1280px
     * @param string $data['auto_color']    自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调
     * @param string $data['line_color']    auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
     * @param string $data['is_hyaline']    是否需要透明底色，为 true 时，生成透明底色的小程序码
     * @return array   
     */
    public function get($access_token, $data)
    {
        //POST https://api.weixin.qq.com/wxa/getwxacode?access_token=ACCESS_TOKEN

        $url = "https://api.weixin.qq.com/wxa/getwxacode";
        $body = array_merge([
            'path' => null,
            'width' => 430,
            'auto_color' => false,
            'line_color' => null,
            'is_hyaline' => false
        ], $data);

        $params = compact('access_token');

        return $this->init_result((new HttpClient())->post($url, json_encode($body), $params));
    }
    /**
     * 获取小程序码
     * 
     * @description 适用于需要的码数量极多的业务场景。通过该接口生成的小程序码，永久有效，数量暂无限制
     * 官方文档：https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
     * 
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-07-06
     * @version 2021-07-06
     * @param array     $access_token     接口调用凭证      
     * @param string    $data['scene']            最大32个可见字符，只支持数字，大小写英文以及部分特殊字符：!#$data['&'()*+,/:;=?@-.']_~，其它字符请自行编码为合法字符（因不支持%，中文无法使用 urlencode 处理，请使用其他编码方式）
     * @param string    $data['page']	          必须是已经发布的小程序存在的页面（否则报错），例如 pages/index/index, 根路径前不要填加 /,不能携带参数（参数请放在scene字段里），如果不填写这个字段，默认跳主页面
     * @param string    $data['check_path']	      检查page 是否存在，为 true 时 page 必须是已经发布的小程序存在的页面（否则报错）；为 false 时允许小程序未发布或者 page 不存在， 但page 有数量上限（60000个）请勿滥用
     * @param string    $data['env_version']	  要打开的小程序版本。正式版为 "release"，体验版为 "trial"，开发版为 "develop"
     * @param int       $data['width']            二维码的宽度，单位 px，最小 280px，最大 1280px
     * @param boolean   $data['auto_color']      自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调，默认 false
     * @param Object    $data['line_color']      auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
     * @param boolean   $data['is_hyaline']   是否需要透明底色，为 true 时，生成透明底色的小程序
     * @return array
     */
    public function getUnlimited($access_token, $data)
    {
        //POST https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=ACCESS_TOKEN
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit";

        $body = array_merge([
            'scene' => null,
            'page' => '',
            'check_path' => true,
            'env_version' => 'release',
            'width' => '',
            'auto_color' => false,
            'line_color' => null,
            'is_hyaline' => false
        ], $data);

        $params = compact('access_token');


        return $this->init_result((new HttpClient())->post($url, json_encode($body), $params));
    }
}
