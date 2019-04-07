<h1 align="center"> laravel-wechat-reply </h1>

<p align="center"> Wechat reply handing for Laravel 5.5 and up.</p>

## 环境要求

- PHP >= 7.0

## 安装

```shell
$ composer require achais/laravel-wechat-reply -vvv
```

## 使用

```php
use Achais\LaravelWechatReply\Models\WeixinRule;
use Achais\LaravelWechatReply\Models\WeixinReply;
use Achais\LaravelWechatReply\Models\WeixinKeyword;
use Achais\LaravelWechatReply\WechatReply;

// 回复规则
$rules = WeixinRule::all();
$rule = WeixinRule::create(['rule_name' => '规则一', 'reply_mode' => 'random']); // 随机回复一个匹配的消息
$rule = WeixinRule::create(['rule_name' => '规则二', 'reply_mode' => 'all']); // 回复所有匹配的消息
$rule = WeixinRule::findOrCreate('规则三', 'random');

$ruleOne = WeixinRule::findById(1); // 根据 ID 查找规则
$ruleOne = WeixinRule::findByName('规则一'); // 根据 $ruleName 查找规则, 因为规则中名称唯一所以提供查找

// 关键词
$keywordOne = WeixinKeyword::create(['keyword' => '关键词一', 'full_match' => true], $ruleOne); // 全匹配, 关联 "规则一"
$keywordOne = WeixinKeyword::findById(1);

$keywordTwo = WeixinKeyword::create(['keyword' => '关键词二', 'full_match' => false], $ruleOne); // 半匹配, 关联 "规则一"
$keywordTwo = WeixinKeyword::findById(2); // 根据 ID 查找关键词

// 回复消息, 更多`消息类型`有待补充...
$reply = WeixinReply::create(['type' => 'text', 'content' => '你好'], $ruleOne); // 文字回复, 关联 "规则一"
$reply = WeixinReply::create(['type' => 'text', 'content' => '嗨!'], $ruleOne); // 文字回复, 关联 "规则一"
$reply = WeixinReply::create(['type' => 'image', 'content' => '永久素材 MEDIA_ID'], $ruleOne); // 图片回复, 关联 "规则一"
$replyOne = WeixinReply::findById(1); // 根据 ID 查找回复消息

// 关键词自动匹配回复消息
$keyword = '二';
// 你会从 "你好", "嗨!", "图片消息" 中随机收到一个
$replies = WechatReply::query($keyword); // 返回 WechatReply 对象集合

// 删除回复、关键词
WeixinKeyword::deleteById(1);
WeixinReply::deleteById(1);

// 删除规则 (规则删除后关联的回复和关键词也自动删除了)
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
