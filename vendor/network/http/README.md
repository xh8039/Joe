# 轻HTTP请求库使用文档

> 觉得本项目不错的话可以帮忙点一下星星Star哦

## 简介

轻HTTP请求库是一个简单轻量的PHP HTTP客户端,用于发送各种HTTP请求。它支持GET、POST、HEAD、DELETE、PUT、PATCH等方法,可以轻松发送JSON、XML等格式的数据。

```php
$client = new Client();
$response = $client->get('http://www.example.com');
echo $response; // 输出响应体
```

该库的主要特性:

- 支持主流的HTTP方法：GET、POST、HEAD、DELETE、PUT、PATCH 等。可以发送各种请求，获取不同的响应。
- 支持URL参数、请求体、请求头、Cookie等设置。可以定制灵活的请求参数。
- 发送请求体支持JSON、XML、文本等格式。支持的请求数据类型丰富。
- 简单易用,代码量小巧轻量，使用方法灵活。接口简单明了,学习成本低。
- 基于PHP原生curl扩展，性能高效稳定。利用curl实现,性能优秀。

## 安装

### 通过 Composer 安装

#### 1. 安装 Composer

```bash
curl -sS https://getcomposer.org/installer | php
```

#### 2. 运行安装命令

```bash
composer require network/http:dev-master
```

#### 3. 启用 Composer 自动加载

```php
require 'vendor/autoload.php';
```

启用后,可直接使用 `$client = new Client();`

## 基本使用

```php
use network\http\Client;

$client = new Client();

$client->param('name', '易航'); // 设置请求参数

$client->header('User-Agent', 'Mozilla/5.0'); // 设置请求头

$response = $client->get('http://www.bri6.cn'); // 发送GET请求

echo $response; // 输出响应体
```

请求参数用于构造请求URL的参数,请求头用于定制客户端信息，发起GET请求后获取响应，并输出响应体。

详情见 [基本使用页面](readme/基本使用.md)。

## 助手函数

详情见 [助手函数页面](readme/助手函数.md)。

## 获取响应信息

详情见 [获取响应页面](readme/获取响应.md)。

## 显示响应体

详情见 [显示响应体页面](readme/显示响应体.md)。

## 错误与异常

详情见 [错误与异常页面](readme/错误与异常.md)。

## 其他

另外,如果需要对请求库进行定制开发，可以继承Client类并重写send()方法：

```php
namespace network\http;

class CustomClient extends Client
{
    public function send($url, $params, $headers)
    {
        // 定制发送请求的逻辑
        // 调用parent::send($url, $params, $headers)发送请求
    }
}
```

然后通过 `new CustomClient()` 使用定制的客户端。

希望这个HTTP客户端库和使用文档能为您提供帮助！如果有任何问题请提Issue或Pull Request。

我会持续更新文档，完整记录轻HTTP请求库的所有功能和用法。如果文档的任何部分不够详尽，请提Issue告知我。

希望这个简洁实用的轻HTTP请求库和配套文档能为广大PHP开发者提供更多便捷！
