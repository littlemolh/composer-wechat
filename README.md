
Wechat littlemo 
===============

[![Total Downloads](https://poser.pugx.org/littlemo/wechat/downloads)](https://packagist.org/packages/littlemo/wechat)
[![Latest Stable Version](https://poser.pugx.org/littlemo/wechat/v/stable)](https://packagist.org/packages/littlemo/wechat)
[![Latest Unstable Version](https://poser.pugx.org/littlemo/wechat/v/unstable)](https://packagist.org/packages/littlemo/wechat)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](http://www.php.net/)
[![License](https://poser.pugx.org/littlemo/wechat/license)](https://packagist.org/packages/littlemo/wechat)

## 介绍
微信常用工具库

## 安装

composer.json
```json
{
    "require": {
        "littlemo/wechat": "1.8.*"
    }
}
```

```shell
composer require littlemo/wechat:"1.8.*"
```
## 使用

#### 公共


> 公共部分被所有方法继承，实例化任意类均可调用


```php
use littlemo\wechat\Class;

$Class = new Class($appid , $secret, $mchid , $key , $certPath, $keyPath );

```
实例化参数
|   参数   |  类型  | 是否必填 | 说明                                                                          |
| :------: | :----: | :------: | :---------------------------------------------------------------------------- |
|  appid   | string |    N     | 应用ID(公众号appid/小程序appid/开放平台/第三方平台 appid)                     |
|  secret  | string |    N     | appid对应的唯一凭证密钥(公众号secret/小程序secret/开放平台/第三方平台 secret) |
|  mchid   | string |    N     | 微信支付商户号                                                                |
|   key    | string |    N     | 微信支付商户号对应的支付密钥                                                  |
| certPath | string |    N     | 微信支付商户号api证书cert文件路径                                             |
| keyPath  | string |    N     | 微信支付商户号api证书key文件路径                                              |

#### token

> 获取全局Access token（支持：公众号、小程序）  


##### 示例代码


```php
use littlemo\wechat\Class;
use littlemo\wechat\core\LWechatException;

$Class = new Class($appid, $appkey);

$result = $Class->token();

if ($result) {
    echo '获取Access token成功';
    $token = $Class->getMessage();
} else {
    echo "获取Access token失败";
    $errorMsg = $Class->getErrorMsg();
}

try{
    $data = $Class->token()
}catch(LWechatException $e){
    print_r('错误代码：'.$e->getCode());
    print_r('错误提示：'.$e->getMessage());
    print_r('完整的内容：'.json_encode($e->getData()));
};

//查询完整的回调消息
$intactMsg = $Class->getIntactMsg();


```

**返回示例**
```json
{
    "access_token":"ACCESS_TOKEN",
    "expires_in":7200
}
```

> [官方文档](https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Get_access_token.html)


### [公众号](https://github.com/littlemolh/composer-wechat/tree/main/src/gzh)

### 小程序

> 整理中...

### 开放平台

> 整理中...

### 微信支付

> 整理中...

## 参与贡献

1.  littlemo


## 特技

- 统一、精简
