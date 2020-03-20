<h1 align="center"> laravel-wechat-reply </h1>

<p align="center"> 可视化配置微信公众号自动回复规则，自动匹配回复消息。即将支持：可视化页面、匹配自定义处理方法. 
</p>

![StyleCI build status](https://github.styleci.io/repos/179957764/shield) 

## 环境要求

- PHP >= 7.0

## 安装

```shell
$ composer require achais/laravel-wechat-reply -vvv
```

## 配置

在 config/app.php 中加入我们的 ServiceProvider
```php
'providers' => [
    // Application Service Providers...
    Achais\LaravelWechatReply\ServiceProvider::class,
],
```
> 如果你的 laravel > 5.0 其实可以跳过这一步

发布配置文件和静态文件
```shell script
php artisan vendor:publish --provider="Achais\LaravelWechatReply\ServiceProvider"
```


## 使用

### 自动回复编辑

浏览器访问 /wechat-reply 默认用户名密码是 admin admin  
你也可以通过在 .env 文件中添加  
```
WECHAT_REPLY_USER=your_username  
WECHAT_REPLY_PASSWORD=your_password  
```
来自定义登录用户名和密码

### 代码调用

```php
use Achais\LaravelWechatReply\Models\WeixinRule;
use Achais\LaravelWechatReply\Models\WeixinReply;
use Achais\LaravelWechatReply\Models\WeixinKeyword;
use Achais\LaravelWechatReply\WechatReply;

// ====== 规则 ======

// 查看全部规则
$rules = WeixinRule::all();

// 创建随机回复一个匹配消息的规则, 名称已存在会报错
$rule = WeixinRule::create(['rule_name' => '规则一', 'reply_mode' => 'random']);

// 创建回复全部匹配消息的规则, 名称已存在会报错
$rule = WeixinRule::create(['rule_name' => '规则二', 'reply_mode' => 'all']);

// 查找或创建规则
$rule = WeixinRule::findOrCreate('规则三', 'random');

// 根据 ID 查找规则
$ruleOne = WeixinRule::findById(1);

// 根据 名称 查找规则, 因为规则中名称唯一所以提供查找功能
$ruleOne = WeixinRule::findByName('规则一');


// ====== 关键词 ======

// 创建一个需要全匹配的关键词, 关联 "规则一"
$keywordOne = WeixinKeyword::create(['keyword' => '关键词一', 'full_match' => true], $ruleOne);

// 创建一个需要半匹配(模糊搜索)的关键词, 关联 "规则一"
$keywordTwo = WeixinKeyword::create(['keyword' => '关键词二', 'full_match' => false], $ruleOne);

// 根据 ID 查找关键词
$keywordTwo = WeixinKeyword::findById(2);


// ====== 回复消息, 更多`消息类型`有待补充 ======

// 创建文本内容的回复消息, 关联 "规则一"
$reply = WeixinReply::create(['type' => 'text', 'content' => '你好'], $ruleOne); // 文字回复, 关联 "规则一"

// 创建图片内容的回复消息, 关联 "规则一"
$reply = WeixinReply::create(['type' => 'image', 'content' => '永久素材 MEDIA_ID'], $ruleOne);

// 创建图文内容的回复消息, 关联 "规则一"
$reply = WeixinReply::create(['type' => 'news', 'content' => '图文内容'], $ruleOne);

// 根据 ID 查找回复消息
$replyOne = WeixinReply::findById(1);


// ====== 关键词匹配功能 ======

// 收到用户消息 "二"
$keyword = '二';

// 你会从 "你好", "图片消息", "图文消息" 中随机收到一个消息, 返回 WechatReply 对象集合
$replies = WechatReply::query($keyword);

/**
Collection {#893 ▼
  #items: array:1 [▼
    0 => WeixinReply {#891 ▶}
  ]
}
*/


// ====== 删除关联关系 ======

// 删除回复、关键词
WeixinKeyword::deleteById(1);
WeixinReply::deleteById(1);

// 删除规则 (关联的回复和关键词也自动删除)
WeixinRule::deleteById(1);
WeixinRule::deleteByName('规则二');
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/achais/laravel-wechat-reply/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/achais/laravel-wechat-reply/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
