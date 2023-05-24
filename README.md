# 123云盘直链解析API

#### 介绍
> 123网盘直链带密码解析 下载地址解析API

#### 支持的功能
- 123盘直链带密码
- 带密码的文件分享链接但不支持分享的文件夹
- 生成直链或直接下载
- 缓存解析结果(一分钟) 提高解析速度,降低解析频率
- 自定义UserAgent


#### 说明

url:123盘外链链接

type:是否直接下载 值：down

pwd:外链密码

内部调用方法
```php
include('D123pan.clsss.php');
$dp = new d123pan;
//$dp->cache_time=60;//设置缓存时间
$res=$dp->getUrl($url,$pwd);
```
# 使用示例	
 直接下载：

- 不带提取码:

http://tool.bitefu.net/123pan/?url=https://www.123pan.com/s/poqA-CFWG3.html&type=down

http://tool.bitefu.net/123pan/?url=poqA-CFWG3&type=down
- 带提取码:

http://tool.bitefu.net/123pan/?url=https://www.123pan.com/s/poqA-WFWG3.html&type=down&pwd=6cUF

http://tool.bitefu.net/123pan/?url=poqA-WFWG3&type=down&pwd=6cUF

输出直链：

- 不带提取码:

http://tool.bitefu.net/123pan/?url=https://www.123pan.com/s/poqA-WFWG3.html

http://tool.bitefu.net/123pan/?url=poqA-WFWG3

- 带提取码:

http://tool.bitefu.net/123pan/?url=https://www.123pan.com/s/poqA-WFWG3.html&pwd=6cUF

http://tool.bitefu.net/123pan/?url=poqA-WFWG3&pwd=6cUF

#### 简网址

- 不带提取码:http://tool.bitefu.net/123pan/?d=poqA-CFWG3

- 带提取码:http://tool.bitefu.net/123pan/?d=poqA-WFWG3_6cUF


#### 捐助我

捐助列表:http://tool.bitefu.net/pay/

<a href="http://tool.bitefu.net/pay/alipayred.jpg" target="_blank"><img height="300" src="https://oscimg.oschina.net/oscnet/69e93259cb3fa4f12a2c4e96dfc65984fb9.jpg" width="200" /></a> <a href="http://tool.bitefu.net/pay/wxpay.png" target="_blank"><img height="274" src="https://oscimg.oschina.net/oscnet/1118495c283edf13318ed999c1ad23c1614.jpg" width="200" /></a> <a href="http://tool.bitefu.net/pay/alipay.jpg" target="_blank"><img height="272" src="https://oscimg.oschina.net/oscnet/1adfcc1bdf40d6cf35bcc80f94678d52e98.jpg" width="200" /></a>
