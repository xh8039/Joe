# 轻HTTP请求库使用文档  

## 安装

使用Composer安装:

```shell
composer require network/http
```

## 基本使用

### 实例化Client

```php
$client = new network\http\Client();
```
  
### param()和header()方法

可以调用param()方法添加URL参数,header()方法添加请求头。它们既支持单个设置,也支持批量设置:

```php
// 单个设置
$client->param('name', '易航');  
$client->header('User-Agent', 'Mozilla/5.0');  

// 批量设置  
$client->param(['name' => '易航', 'age' => 25]);
$client->header(['User-Agent' => 'Mozilla/5.0', 'Content-Type' => 'application/json']);
```

### 发送GET请求

```php  
$response = $client->get('http://www.bri6.cn');
```
  
可以传入请求头和查询参数:

```php
$response = $client->get('http://www.bri6.cn', ['name' => '易航'], ['Accept' => 'application/json']);
```

### 发送POST请求

```php  
$response = $client->post('http://www.bri6.cn', ['name' => '易航']);
```
  
可以传入请求体、请求头:

```php
$response = $client->post('http://www.bri6.cn', ['name' => '易航'], ['Content-Type' => 'application/x-www-form-urlencoded']);
```

### 其他请求方法

- delete(): 发送DELETE请求
- put(): 发送PUT请求
- patch(): 发送PATCH请求

## 助手函数

轻HTTP请求库提供以下助手函数:

### post()

发送POST请求。用法:

```php
network\http\post($url, $params, $headers, $options)

- $url: 请求URL
- $params: 携带的参数,可以是数组或false
- $headers: 自定义请求头,数组格式
- $options: 配置信息,数组格式
返回response对象。
```

### get()

发送GET请求。用法:

```php
network\http\get($url, $params, $headers, $options)  
参数与post()方法相同。
```

### delete()

发送DELETE请求。用法:

```php
network\http\delete($url, $params, $headers, $options)
```

参数与post()方法相同。  

### put()

发送PUT请求。用法:

```php  
network\http\put($url, $params, $headers, $options)  
参数与post()方法相同。  
```

### patch()  

发送PATCH请求。用法:

```php  
network\http\patch($url, $params, $headers, $options)
参数与post()方法相同。
```

## 获取响应

使用响应对象获取响应信息:

```php
$response = $client->get('http://www.bri6.cn');
```

- `$response->code()`：获取响应状态码
- `$response->header('name')`: 获取指定响应头
- `$response->headers()`: 以数组形式获取所有响应头
- `$response->body()`: 获取原始响应体字符串
- `$response->toObject()`: 如果响应是JSON,转换为对象
- `$response->toArray()`: 如果响应是JSON,转换为数组

## 显示响应体

可以直接输出响应体:

```php
echo $response;
```

或者:

```php
echo $response->body();
```

## 错误与异常  

请求过程中发生的curl错误或解析响应时发生的错误将抛出异常,使用try/catch进行捕获。

我会持续更新文档,完整记录轻HTTP请求库的所有功能和用法。如果文档的任何部分不够详尽,请告知我。

希望这个简洁实用的轻HTTP请求库和配套文档能为广大`PHP`开发者提供更多便
