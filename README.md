# Simple Frp API
简单、拥有最基础功能的 Frp API，可用于二次开发自己的网站。

## 使用方法
首先将项目 clone 到网站根目录：
```
git clone https://github.com/ZeroDream-CN/simple-frp-api frpapi/
```
进入到目录中，编辑 index.php，在文件顶部设置好数据库信息、Frp Token。

注意 Frp Token 要和 Frps 设置的 api_token 一致。

```php
<?php
//error_reporting(0);
Header("Content-type: text/plain");
$conn = mysqli_connect("数据库地址", "数据库账号", "数据库密码", "数据库名称") or die("Database error");
define("API_TOKEN", "这里填 Frp Token");
```

接着新建一个 MySQL 数据库，也可以用现有的，然后倒入 import.sql

data.sql 是可选的，它里面有三条示例数据供你参考。

最后，在 frps.ini 里面指定：
```ini
[common]
··· 省略内容 ···
api_enable = true
api_baseurl = http://你的网站/frpapi/
api_token = 你设置的Token
```
然后运行 Frps 即可。

## 开源协议
本项目使用 MIT 协议开源
