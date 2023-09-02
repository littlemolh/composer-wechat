
微信支付 wechat littlemo  
===============

[![Total Downloads](https://poser.pugx.org/littlemo/wechat/downloads)](https://packagist.org/packages/littlemo/wechat)
[![Latest Stable Version](https://poser.pugx.org/littlemo/wechat/v/stable)](https://packagist.org/packages/littlemo/wechat)
[![Latest Unstable Version](https://poser.pugx.org/littlemo/wechat/v/unstable)](https://packagist.org/packages/littlemo/wechat)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](http://www.php.net/)
[![License](https://poser.pugx.org/littlemo/wechat/license)](https://packagist.org/packages/littlemo/wechat)

### 介绍
微信支付相关工具


### 使用教程

```php
use littlemo\wechat\pay\v3\Config;
use littlemo\wechat\pay\v3\partner\Transactions;

// 配置支付参数
Config::create()
    ->mchid(Env::get('wechat_partner.mchid'), Env::get('wechat_pay.mchid'))
    ->cert(ROOT_PATH . Env::get('wechat_partner.apiclient_cert'), ROOT_PATH . Env::get('wechat_partner.apiclient_key'), ROOT_PATH . Env::get('wechat_partner.platform_cert'))
    ->appid(Env::get('wechat_partner_xcx.appid'), Env::get('wechat_xcx.appid'))
    ->build();

//设置下单参数
$transactions = new Transactions();
$transactions
    ->body([
        'description' => $order->exhibition->title . ' - ' . $order->report_stall,
        'out_trade_no' => $trade->out_trade_no,
        'notify_url' => $this->request->root(true) . '/api/order_pay/wechat_notify'
    ])->amount(['total' => bcmul($trade->amount, 100)]);

//JSAPI
$jsapiResult = $transactions->payer('sub_openid', $wechatUserInfo['openid'])->jsapi();
$result =  $transactions->jsapiSign($jsapiResult['prepay_id']);
//NATIVE
$result = $transactions->native();

//接受解析回调信息
$notifyData = $transactions->notify();

```



### 参与贡献

1.  littlemo


### 特技

- 统一、精简
