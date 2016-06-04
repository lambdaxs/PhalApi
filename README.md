##个人业余微博项目
首先感谢开源的PhalApi框架。[官网 - www.phalapi.net](http://www.phalapi.net)  
本项目是基于新浪微博的功能模块开发完成的。这份PHP代码可以部署在服务器上，专注提供json格式的api给iOS、andriod或web前端客户端使用。

给开发人员看的文档：[本项目的文档](https://www.xsdota.com/PhalApi/Public/weibo_v1/all.php)

 api使用示例：   
 如登录接口：https://www.xsdota.com/weibo/v1/user/login.json
 如下图，接口要求两个必须参数userName和password，且接口请求为POST方式。
 ![apic](https://www.xsdota.com/media/CFB3AA86-7E47-40F2-B0F1-CBC41A49F79E.png)
 所以以AFNetworking3.0的代码为例子：
 ![apic](https://www.xsdota.com/media/14650367332519.jpg)
服务端返回的响应格式大部分为text/html,使用AFN请提前设计响应格式。
XCode控制台输出的请求结果如下：（如果是Mac电脑的话建议使用Paw这款软件，其他系统可以使用Postman。对于api测试的效率比工程控制台调试高很多。）
![apic](https://www.xsdota.com/media/14650369949605.jpg)

所有返回json格式都是ret、data、msg三段式结构。ret为请求码：200表示请求成功，完成预期流程。其他数字表示错误，并在msg字段中给出错误信息。data中就是服务端返回的信息主体。
 上图中msg转义为中文：非法请求：userName.len应该大于或等于6, 但现在userName.len = 5。开发人员无须担心接口发生错误后不知所措，msg字段会给出详细的错误信息，实现开发调试的自动化。
 

  



