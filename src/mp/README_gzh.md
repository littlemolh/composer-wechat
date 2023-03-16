
公众号 wechat littlemo  
===============

[![Total Downloads](https://poser.pugx.org/littlemo/wechat/downloads)](https://packagist.org/packages/littlemo/wechat)
[![Latest Stable Version](https://poser.pugx.org/littlemo/wechat/v/stable)](https://packagist.org/packages/littlemo/wechat)
[![Latest Unstable Version](https://poser.pugx.org/littlemo/wechat/v/unstable)](https://packagist.org/packages/littlemo/wechat)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](http://www.php.net/)
[![License](https://poser.pugx.org/littlemo/wechat/license)](https://packagist.org/packages/littlemo/wechat)

### 介绍
公众号相关工具

### 安装教程

composer.json
```json
{
    "require": {
        "littlemo/wechat": "~1.5.0"
    }
}
```
### 使用教程

#### ticket

> 获得jsapi_ticket


##### 示例代码


```php
use littlemo\wechat\Jsapi;

$Jsapi = new Jsapi();

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

参数
|     参数     |  类型  | 是否必填 | 说明                 |
| :----------: | :----: | :------: | :------------------- |
| access_token | string |    Y     | 全局唯一接口调用凭据 |

返回结果
```json
{
  "errcode":0,
  "errmsg":"ok",
  "ticket":"bxLdikRXVbTPdHSM05e5u5sUoXNKd8-41ZO3MhKoyN5OfkWITDGgnr2fwJ0m9E8NYzWKVZvdVtaUgWvsdshFKA",
  "expires_in":7200
}
```

>jsapi_ticket的有效期为7200秒，通过access_token来获取。由于获取jsapi_ticket的api调用次数非常有限，频繁刷新jsapi_ticket会导致api调用受限，影响自身业务，开发者必须在自己的服务全局缓存jsapi_ticket 。

> [官方文档](https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/JS-SDK.html#62)


#### signature

> JS-SDK使用权限签名算法

##### 示例代码


```php
use littlemo\wechat\Jsapi;

$Jsapi = new Jsapi($appid);

$result = $Jsapi->signature($jsapi_ticket, $noncestr, $timestamp, $url, $appid )


```

参数
|     参数     |  类型  | 是否必填 | 说明                               |
| :----------: | :----: | :------: | :--------------------------------- |
|    appid     | string |    Y     | 小程序的appid                      |
| jsapi_ticket | string |    Y     | 有效的ticket                       |
|   noncestr   | string |    N     | 随机字符串                         |
|  timestamp   | string |    N     | 时间戳                             |
|     url      | string |    N     | 当前网页的URL，不包含#及其后面部分 |

返回结果

```string
Wm3WZYTPz0wzccnW
```

> [官方文档](https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/JS-SDK.html#62)

#### access_token

> 通过code换取网页授权access_token

##### 示例代码


```php
use littlemo\wechat\WebAuth;

$WebAuth = new WebAuth($appid, $appkey);

$result = $WebAuth->access_token($code, $grant_type);
if ($result) {
    echo '换取 token 成功';
    $token = $WebAuth->getMessage();
} else {
    echo "换取 token 失败";
    $errorMsg = $WebAuth->getErrorMsg();
}

//查询完整的回调消息
$intactMsg = $WebAuth->getIntactMsg();


```

参数
|    参数    |  类型  | 是否必填 |        默认        | 说明                                               |
| :--------: | :----: | :------: | :----------------: | :------------------------------------------------- |
|   appid    | string |    Y     |                    | 小程序的appid                                      |
|   appkey   | string |    Y     |                    | 小程序唯一凭证密钥，即 AppSecret，获取方式同 appid |
|    code    | string |    Y     |                    | 网页授权获得code                                   |
| grant_type | string |    N     | authorization_code | 当前网页的URL，不包含#及其后面部分                 |

返回结果

```json
{
  "access_token":"ACCESS_TOKEN",
  "expires_in":7200,
  "refresh_token":"REFRESH_TOKEN",
  "openid":"OPENID",
  "scope":"SCOPE" 
}
```


> [官方文档](https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html#1)
> [网页授权获得code](https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html#0) 

#### refresh_token

> 刷新access_token（如果需要）

##### 示例代码


```php
use littlemo\wechat\WebAuth;

$WebAuth = new WebAuth($appid);

$result = $WebAuth->refresh_token($refresh_token, $grant_type);
if ($result) {
    echo '刷新 token 成功';
    $token = $WebAuth->getMessage();
} else {
    echo "刷新 token 失败";
    $errorMsg = $WebAuth->getErrorMsg();
}

//查询完整的回调消息
$intactMsg = $WebAuth->getIntactMsg();


```

参数
|     参数      |  类型  | 是否必填 |     默认      | 说明                                          |
| :-----------: | :----: | :------: | :-----------: | :-------------------------------------------- |
|     appid     | string |    Y     |               | 小程序的appid                                 |
| refresh_token | string |    Y     |               | 填写通过access_token获取到的refresh_token参数 |
|  grant_type   | string |    N     | refresh_token | 填写为refresh_token                           |

返回结果

```json
{ 
  "access_token":"ACCESS_TOKEN",
  "expires_in":7200,
  "refresh_token":"REFRESH_TOKEN",
  "openid":"OPENID",
  "scope":"SCOPE" 
}
```


> [官方文档](https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html#2)

#### userinfo

> 拉取用户信息(需scope为 snsapi_userinfo)

##### 示例代码


```php
use littlemo\wechat\WebAuth;

$WebAuth = new WebAuth();

$result = $WebAuth->userinfo($access_token, $openid, $lang);
if ($result) {
    echo '拉取用户信息成功';
    $token = $WebAuth->getMessage();
} else {
    echo "拉取用户信息失败";
    $errorMsg = $WebAuth->getErrorMsg();
}

//查询完整的回调消息
$intactMsg = $WebAuth->getIntactMsg();


```

参数
|     参数     |  类型  | 是否必填 | 默认  | 说明                                                                  |
| :----------: | :----: | :------: | :---: | :-------------------------------------------------------------------- |
| access_token | string |    Y     |       | 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同 |
|    openid    | string |    Y     |       | 用户的唯一标识                                                        |
|     lang     | string |    N     | zh_CN | 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语                 |

返回结果

```json
{   
  "openid": "OPENID",
  "nickname": NICKNAME,
  "sex": 1,
  "province":"PROVINCE",
  "city":"CITY",
  "country":"COUNTRY",
  "headimgurl":"https://thirdwx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
  "privilege":[ "PRIVILEGE1" "PRIVILEGE2"     ],
  "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
}
```


> [官方文档](https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html#3)


### 参与贡献

1.  littlemo


### 特技

- 统一、精简
