<?php

namespace littlemo\wechat\mp;

use littlemo\utils\HttpClient;


/**
 * TODO 小程序发货信息管理服务
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2023-12-07
 * @version 2023-12-07
 */
class Shipping extends Base
{

    /**
     * 发货信息录入接口
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/order-shipping/order-shipping.html#%E4%B8%80%E3%80%81%E5%8F%91%E8%B4%A7%E4%BF%A1%E6%81%AF%E5%BD%95%E5%85%A5%E6%8E%A5%E5%8F%A3
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-12-07
     * @version 2023-12-07
     * @param string $access_token      接口调用凭证，该参数为 URL 参数，非 Body 参数。使用getAccessToken或者authorizer_access_token
     * @param array $order_key          订单，需要上传物流信息的订单
     * @param int $logistics_type       物流模式，发货方式枚举值：1、实体物流配送采用快递公司进行实体物流配送形式 2、同城配送 3、虚拟商品，虚拟商品，例如话费充值，点卡等，无实体配送形式 4、用户自提
     * @param int $delivery_mode        发货模式，发货模式枚举值：1、UNIFIED_DELIVERY（统一发货）2、SPLIT_DELIVERY（分拆发货） 示例值: UNIFIED_DELIVERY
     * @param array $shipping_list      物流信息列表，发货物流单列表，支持统一发货（单个物流单）和分拆发货（多个物流单）两种模式，多重性: [1, 10]
     * @param array $payer              支付者，支付者信息
     * @param string $upload_time       上传时间，用于标识请求的先后顺序 示例值: `2022-12-15T13:29:35.120+08:00`
     * @param bool $is_all_delivered    分拆发货模式时必填，用于标识分拆发货模式下是否已全部发货完成，只有全部发货完成的情况下才会向用户推送发货完成通知。示例值: true/false
     * @return array
     */
    public function uploadShippingInfo(string $access_token, array $order_key, int $logistics_type, int $delivery_mode, array $shipping_list, array $payer, string $upload_time = '', bool $is_all_delivered = false): array
    {
        // POST https://api.weixin.qq.com/wxa/sec/order/upload_shipping_info?access_token=ACCESS_TOKEN
        $url = "https://api.weixin.qq.com/wxa/sec/order/upload_shipping_info";
        if (!$upload_time) $upload_time = date("Y-m-d H:i:s");
        $body = compact('order_key', 'logistics_type', 'delivery_mode', 'upload_time', 'shipping_list', 'payer', 'is_all_delivered');
        $params = compact('access_token');
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }

    /**
     * 获取运力id列表get_delivery_list
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/express/business/express_search.html#%E8%8E%B7%E5%8F%96%E8%BF%90%E5%8A%9Bid%E5%88%97%E8%A1%A8get-delivery-list
     * @description 商户使用此接口获取所有运力id的列表
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-12-07
     * @version 2023-12-07
     * @param string $access_token
     * @return void
     */
    public function getDeliveryList(string $access_token)
    {
        // POST https://api.weixin.qq.com/cgi-bin/express/delivery/open_msg/get_delivery_list?access_token=XXX
        $url = "https://api.weixin.qq.com/cgi-bin/express/delivery/open_msg/get_delivery_list";
        $body = [];
        $params = compact('access_token');
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }
}
