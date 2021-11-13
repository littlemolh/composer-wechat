
公众号 wechat littlemo  
===============

[![Total Downloads](https://poser.pugx.org/littlemo/wechat/downloads)](https://packagist.org/packages/littlemo/wechat)
[![Latest Stable Version](https://poser.pugx.org/littlemo/wechat/v/stable)](https://packagist.org/packages/littlemo/wechat)
[![Latest Unstable Version](https://poser.pugx.org/littlemo/wechat/v/unstable)](https://packagist.org/packages/littlemo/wechat)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](http://www.php.net/)
[![License](https://poser.pugx.org/littlemo/wechat/license)](https://packagist.org/packages/littlemo/wechat)

### 介绍
QQ互联常用工具库

### 安装教程

composer.json
```json
{
    "require": {
        "littlemo/wechat": "~1.0.0"
    }
}
```
### 使用教程

#### ticket

> 获得jsapi_ticket


##### 示例代码


```php
use littlemo\wechat\Jsapi;

$Jsapi = new Jsapi($appid, $appkey);

$result = $Jsapi->ticket($access_token);
if ($result) {
    echo '获取ticket成功';
    $token = $Jsapi->getMessage();
} else {
    echo "获取ticket失败";
    $errorMsg = $Jsapi->getErrorMsg();
}

//查询完整的回调消息
$intactMsg = $Jsapi->getIntactMsg();


```



> [官方文档](https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/JS-SDK.html#62)


#### signature

> JS-SDK使用权限签名算法

##### 示例代码


```php
use littlemo\wechat\Jsapi;

$Jsapi = new Jsapi($appid, $appkey);

$result = $Jsapi->signature($jsapi_ticket , $noncestr ,  $timestamp , $url )


```

实例化参数
|     参数     |  类型  | 是否必填 | 说明                               |
| :----------: | :----: | :------: | :--------------------------------- |
| jsapi_ticket | string |    Y     | 有效的ticket                       |
|   noncestr   | string |    N     | 随机字符串                         |
|  timestamp   | string |    N     | 时间戳                             |
|     url      | string |    N     | 当前网页的URL，不包含#及其后面部分 |

返回结果

```json
{
    "noncestr" => "Wm3WZYTPz0wzccnW",
    "jsapi_ticket" => "sM4AOVdWfPE4DxkXGEs8VMCPGGVi4C3VM0P37wVUCFvkVAy_90u5h9nbSlYy3-Sl-HhTdfl2fzFy1AOcHKP7qg",
    "timestamp" => 1414587457,
    "url" => "http://mp.weixin.qq.com?params=value",
}

```


> [官方文档](https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/JS-SDK.html#62)



### 参与贡献

1.  littlemo


### 特技

- 统一、精简
