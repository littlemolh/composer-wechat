<?php

// +----------------------------------------------------------------------
// | Little Mo - Tool [ WE CAN DO IT JUST TIDY UP IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 http://ggui.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: littlemo <25362583@qq.com>
// +----------------------------------------------------------------------

namespace littlemo\wechat\pay\v3\partner;

use littlemo\wechat\core\LWechatException;

/**
 * 分账
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2023-03-13
 * @version 2023-03-13
 */
class  Profitsharing extends \littlemo\wechat\pay\v3\Base
{

    protected function post(string $chain, array $json, array $headers = []): array
    {
        $result = parent::post($chain, $json, $headers);
        if (isset($result['code']) && !empty($result['code'])) {
            throw new LWechatException($result['message'], $result['code'], $result);
        }
        return $result;
    }


    /**
     * 请求分账
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_1.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @param string $transaction_id 微信订单号
     * @param string $out_order_no   商户分账单号
     * @param array $receivers       分账接收方列表
     * @param bool $unfreeze_unsplit 是否解冻剩余未分资金
     * @return array
     */
    public function orders(string $transaction_id, string $out_order_no, array $receivers, bool $unfreeze_unsplit = false): array
    {

        $chain = 'v3/profitsharing/orders';

        $body = [
            'sub_mchid' => $this->subMchid,
            'appid' => $this->appid,
            'sub_appid' => $this->subAppid
        ];

        $body['transaction_id'] = $transaction_id;
        $body['out_order_no'] = $out_order_no;
        foreach ($receivers as &$val) {
            $val['amount'] = (int)$val['amount'];
            $val['name'] = static::encrypt($val['amount']);
        }
        $body['receivers'] = $receivers;
        $body['unfreeze_unsplit'] = $unfreeze_unsplit;



        return $this->post($chain, $body, ['Wechatpay-Serial' => static::$platformCertificateSerial]);
    }

    /**
     * 查询分账结果
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_2.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @param string $transaction_id    微信支付订单号
     * @param string $out_order_no      商户分账单号
     * @return array
     */
    public function getOrders(string $transaction_id, string $out_order_no): array
    {

        $chain = 'v3/profitsharing/orders/{out_order_no}';
        $query = [
            'sub_mchid' => $this->subMchid,
        ];
        $query['transaction_id'] = $transaction_id;

        return $this->get($chain, $query, compact('out_order_no'));
    }


    /**
     * 请求分账回退 通过 商户分账单号
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_3.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @param string $out_order_no  商户分账单号
     * @param string $out_return_no 商户回退单号
     * @param string $return_mchid  回退商户号
     * @param int $amount           回退金额
     * @param string $description   回退描述
     * @return array
     */
    public function returnOrdersByOutOrderNo(string $out_order_no, string $out_return_no, string $return_mchid, int $amount, string $description): array
    {
        $chain = 'v3/profitsharing/return-orders';
        $body = array_merge([
            'sub_mchid' => $this->subMchid,
        ], compact('out_order_no', 'out_return_no', 'return_mchid', 'amount', 'description'));

        return $this->post($chain, $body);
    }

    /**
     * 请求分账回退 通过 微信分账单号
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_3.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @param string $order_id      微信分账单号
     * @param string $out_return_no 商户回退单号
     * @param string $return_mchid  回退商户号
     * @param int $amount           回退金额
     * @param string $description   回退描述
     * @return array
     */
    public function returnOrdersByOrderId(string $order_id, string $out_return_no, string $return_mchid, int $amount, string $description): array
    {
        $chain = 'v3/profitsharing/return-orders';
        $body = array_merge([
            'sub_mchid' => $this->subMchid,
        ], compact('order_id', 'out_return_no', 'return_mchid', 'amount', 'description'));

        return $this->post($chain, $body);
    }

    /**
     * 查询分账回退结果
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_4.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @param string $out_return_no      商户回退单号
     * @param string $out_order_no       商户分账单号
     * @return array
     */
    public function getReturnOrders(string $out_return_no, string $out_order_no): array
    {

        $chain = 'v3/profitsharing/return-orders/{out_return_no}';
        $query = [
            'sub_mchid' => $this->subMchid,
        ];
        $query['out_order_no'] = $out_order_no;

        return $this->get($chain, $query, compact('out_return_no'));
    }

    /**
     * 解冻剩余资金
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_5.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @param string $transaction_id    微信订单号
     * @param string $out_order_no      商户分账单号
     * @param string $description       分账描述
     * @return array
     */
    public function unfreeze(string $transaction_id, string $out_order_no, string $description): array
    {

        $chain = 'v3/profitsharing/orders/unfreeze';
        $body = array_merge([
            'sub_mchid' => $this->subMchid,
        ], compact('transaction_id', 'out_order_no', 'description'));

        return $this->post($chain, $body);
    }

    /**
     * 查询剩余待分金额
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_6.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @param string $transaction_id    微信支付订单号
     * @return array
     */
    public function amounts(string $transaction_id): array
    {

        $chain = 'v3/profitsharing/transactions/{transaction_id}/amounts';
        $query = [];

        return $this->get($chain, $query, compact('transaction_id'));
    }

    /**
     * 查询最大分账比例
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_7.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @return array
     */
    public function configs(): array
    {

        $chain = 'v3/profitsharing/merchant-configs/{sub_mchid}';
        $query = [];
        return $this->get($chain, $query, ['sub_mchid' => $this->subMchid]);
    }

    /**
     * 添加分账接收方
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_8.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @param string $type              分账接收方类型
     * @param string $account           分账接收方账号
     * @param string $relation_type     与分账方的关系类型
     * @param string $name              分账个人接收方姓名
     * @param string $custom_relation   自定义的分账关系
     * @return array
     */
    public function add(string $type, string $account, string $relation_type, string $name, string $custom_relation = ''): array
    {

        $chain = 'v3/profitsharing/receivers/add';
        $body = array_merge([
            'sub_mchid' => $this->subMchid,
            'appid' => $this->appid,
            'sub_appid' => $this->subAppid
        ], compact('type', 'account', 'relation_type'));
        if (!empty($name)) {
            $body['name'] = static::encrypt($name);
        }
        if (!empty($custom_relation)) {
            $body['custom_relation'] = $custom_relation;
        }
        return $this->post($chain, $body, ['Wechatpay-Serial' => static::$platformCertificateSerial]);
    }

    /**
     * 删除分账接收方
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_9.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @param string $type      分账接收方类型
     * @param string $account   分账接收方账号
     * @return array
     */
    public function delete(string $type, string $account): array
    {

        $chain = 'v3/profitsharing/receivers/delete';
        $body = array_merge([
            'sub_mchid' => $this->subMchid,
            'appid' => $this->appid,
            'sub_appid' => $this->subAppid
        ], compact('type', 'account'));

        return $this->post($chain, $body);
    }


    /**
     * 申请分账账单
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter8_1_11.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     * @param string $bill_date 账单日期 格式yyyy-MM-DD仅支持三个月内的账单下载申请。
     * @param string $tar_type  压缩类型 不填则默认是数据流 枚举值：GZIP：返回格式为.gzip的压缩包账单
     * @return array
     */
    public function bills(string $bill_date, string $tar_type): array
    {

        $chain = 'v3/profitsharing/bills';
        $query = array_merge([
            'sub_mchid' => $this->subMchid
        ], compact('bill_date', 'tar_type'));
        return $this->get($chain, $query, ['sub_mchid' => $this->subMchid]);
    }
}
