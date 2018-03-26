M 1.0
===============

M 1.0 基于Gini框架开发, 简化配置, 目的用于加深PHP理解和自我项目实践, 作为自我开发第一版本的框架, 感觉自己学习到了很多，之后也会继续完善，修改，重构此框架。

                        ps:感觉写文档好难,自己写的框架自己都不愿意写文档，真心佩服文档写的巨全面的大神。

[![Gini](https://poser.pugx.org/topthink/think/downloads)](https://packagist.org/packages/topthink/think)
[![Unpc](https://poser.pugx.org/topthink/think/v/stable)](https://packagist.org/packages/topthink/think)
[![Banana](https://poser.pugx.org/topthink/think/v/unstable)](https://packagist.org/packages/topthink/think)

M 1.0 传统单一入口, 以简化实际开发的繁琐操作和优化各部分功能是不变的宗旨, 之后的开发和重构也会基于这个理念进行。

## 目录结构

初始的目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─app                   项目目录
│
├─class                 控制器和主要运行方法目录（可以更改）
│
├─public                WEB目录（对外访问目录）
│  └─index.php          入口文件
│
├─raw                   配置文件
│  ├─database           数据库配置
│  └─redis              redis配置
│
├─view                  视图目录
│
├─ M                    框架主目录
│  ├─cgi.php            cgi入口
│  └─cli.php            cli入口
│
├─vendor                第三方类库目录（Composer依赖库）
│
├─composer.json         composer 定义文件
│
├─README.md             README 文件
~~~

### 目录和文件

*   目录不强制规范，驼峰和小写+下划线模式均支持；
*   类库、函数文件统一以`.php`为后缀；
*   类的文件名均以命名空间定义，并且命名空间的路径和类库文件所在路径一致；
*   类名和类文件名保持一致，统一采用驼峰法命名（首字母大写）；
