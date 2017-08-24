# ThinkPHP Blog

用 ThinkPHP 3.2 开发的演示程序，说明如何实现以下基本的功能。

- [x] 去掉地址里面的入口文件 index.php
- [x] 用 Docker 配置本地开发环境
- [x] 配置放入环境
- [x] 基本的 CRUD
- [x] 用 Laravel Mix 构建前端资源
- [x] 用户认证：用户是否是系统的合法用户，包括注册、登录、注销和忘记密码，等等
- [ ] 用户授权：用户是否有权做某项操作，比如是否可以访问某个页面/某项功能、是否可以读/写/删某项数据，等等
- [x] 多语言支持
- [ ] Web API
- [ ] AngularJS 1 客户端

## 本地开发环境

我们用 Docker 建立本地开发环境。按照 [Install Docker Engine](https://docs.docker.com/engine/installation/) 在你的开发电脑上安装 Docker。

安装了 Docker 之后，用 Composer 安装 PHP 包：

```
$ docker run --rm --interactive --tty --volume $PWD:/app composer install
```

从 .env.example 拷贝创建 .env，并根据你的当前环境进行设置。在 Mac 或者 Linux 下可用如下命令：

```
$ cp .env.example .env
```

运行如下命令构建 Docker 映像，并在后台启动容器：

```
$ docker-compose up -d
```

运行数据库迁移和预置数据：

```
$ docker exec -ti thinkblog_app_1 vendor/bin/phinx migrate -c _phinx/phinx.php
$ docker exec -ti thinkblog_app_1 vendor/bin/phinx seed:run -c _phinx/phinx.php
```

然后你就可以在浏览器中查看网站 http://localhost:8084/。
