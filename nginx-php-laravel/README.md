### 常用命令
* `docker start` 容器名（容器ID也可以）
* `docker stop` 容器名（容器ID也可以）
* `docker run` 命令加 -d 参数，docker 会将容器放到后台运行
* `docker ps` 正在运行的容器
* `docker logs` --tail 10 -tf 容器名    查看容器的日志文件,加-t是加上时间戳，f是跟踪某个容器的最新日志而不必读整个日志文件
* `docker top` 容器名 查看容器内部运行的进程
* `docker exec -d` 容器名 touch /etc/new_config_file  通过后台命令创建一个空文件
* `docker run --restart=always --name` 容器名 -d ubuntu /bin/sh -c "while true;do echo hello world; sleep 1; done" 无论退出代码是什么，docker都会自动重启容器，可以设置 --restart=on-failure:5 自动重启的次数
* `docker inspect` 容器名   对容器进行详细的检查，可以加 --format='{(.State.Running)}' 来获取指定的信息
* `docker rm` 容器ID  删除容器，注，运行中的容器无法删除
* `docker rm $(docker ps -aq)` 删除所有容器
* `docker rmi $(docker images -aq)` 删除所有镜像
* `docker images` 列出镜像
* `docker pull` 镜像名:标签 拉镜像
* `docker search`  查找docker Hub 上公共的可用镜像 
* `docker build -t='AT/web_server:v1'`  命令后面可以直接加上github仓库的要目录下存在的Dockerfile文件。 命令是编写Dockerfile 之后使用的。-t选项为新镜像设置了仓库和名称:标签
* `docker login`  登陆到Docker Hub，个人认证信息将会保存到$HOME/.dockercfg, 
* `docker commit -m="comment " --author="AT" ` 容器ID 镜像的用户名/仓库名:标签 不推荐这种方法，推荐dockerfile
* `docker history` 镜像ID 深入探求镜像是如何构建出来的
* `docker port` 镜像ID 端口    查看映射情况的容器的ID和容器的端口号，假设查询80端口对应的映射的端口
* `run` 运行一个容器，  -p 8080:80  将容器内的80端口映射到docker宿主机的某一特定端口，将容器的80端口绑定到宿主机的8080端口，另 127.0.0.1:80:80 是将容器的80端口绑定到宿主机这个IP的80端口上，-P 是将容器内的80端口对本地的宿主机公开
* http://docs.docker.com/reference/builder/ 查看更多的命令
* `docker push` 镜像名 将镜像推送到 Docker Hub
* `docker rmi` 镜像名  删除镜像
* `docker attach` 容器ID   进入容器
* ############################################################
* `docker network create --subnet=172.171.0.0/16 docker-at` 选取172.172.0.0网段
* `docker build` 就可以加 -ip指定容器ip 172.171.0.10 了

**删除所有容器和镜像的命令**

```
docker rm `docker ps -a |awk '{print $1}' | grep [0-9a-z]` 删除停止的容器
docker rmi $(docker images | awk '/^<none>/ { print $3 }')
```


### Dockerfile语法

* `MAINTAINER`  标识镜像的作者和联系方式
* `EXPOSE` 可以指定多个EXPOSE向外部公开多个端口，可以帮助多个容器链接
* `FROM`  指令指定一个已经存在的镜像
* `\#`号代表注释
* `RUN` 运行命令,会在shell 里使用命令包装器 /bin/sh -c 来执行。如果是在一个不支持shell 的平台上运行或者不希望在shell 中运行，也可以 使用exec 格式 的RUN指令
* `ENV REFRESHED_AT` 环境变量 这个环境亦是用来表明镜像模板最后的更新时间
* `VOLUME` 容器添加卷。一个卷是可以 存在于一个或多个容器内的特定的目录，对卷的修改是立刻生效的，对卷的修改不会对更新镜像产品影响，例:VOLUME["/opt/project","/data"]
* `ADD` 将构建环境 下的文件 和目录复制到镜像 中。例 ADD nginx.conf /conf/nginx.conf 也可以是取url 的地址文件，如果是压缩包，ADD命令会自动解压、
* `USER` 指定镜像用那个USER 去运行
* `COPY` 是复制本地文件，而不会去做文件提取（解压包不会自动解压） 例：COPY conf.d/ /etc/apache2/  将本地conf.d目录中的文件复制到/etc/apache2/目录中

### docker-compose.yml 语法说明
* `image` 指定为镜像名称或镜像ID。如果镜像不存在，Compose将尝试从互联网拉取这个镜像
* `build` 指定Dockerfile所在文件夹的路径。Compose将会利用他自动构建这个镜像，然后使用这个镜像
* `command` 覆盖容器启动后默认执行的命令
* `links` 链接到其他服务容器，使用服务名称(同时作为别名)或服务别名（SERVICE:ALIAS）都可以
* `external_links` 链接到docker-compose.yml外部的容器，甚至并非是Compose管理的容器。参数格式和links类似
* `ports` 暴露端口信息。宿主机器端口：容器端口（HOST:CONTAINER）格式或者仅仅指定容器的端口（宿主机器将会随机分配端口）都可以(注意：当使用 HOST:CONTAINER 格式来映射端口时，如果你使用的容器端口小于 60 你可能会得到错误得结果，因为 YAML 将会解析 xx:yy 这种数字格式为 60 进制。所以建议采用字符串格式。)
* `expose` 暴露端口，与posts不同的是expose只可以暴露端口而不能映射到主机，只供外部服务连接使用；仅可以指定内部端口为参数
* `volumes` 设置卷挂载的路径。可以设置宿主机路径:容器路径（host:container）或加上访问模式（host:container:ro）ro就是readonly的意思，只读模式
* `volunes_from` 挂载另一个服务或容器的所有数据卷
* `environment` 设置环境变量。可以属于数组或字典两种格式。如果只给定变量的名称则会自动加载它在Compose主机上的值，可以用来防止泄露不必要的数据
* `env_file`  从文件中获取环境变量，可以为单独的文件路径或列表。如果通过docker-compose -f FILE指定了模板文件，则env_file中路径会基于模板文件路径。如果有变量名称与environment指令冲突，则以后者为准(环境变量文件中每一行都必须有注释，支持#开头的注释行)
* `extends` 基于已有的服务进行服务扩展。例如我们已经有了一个webapp服务，模板文件为common.yml。编写一个新的 development.yml 文件，使用 common.yml 中的 webapp 服务进行扩展。后者会自动继承common.yml中的webapp服务及相关的环境变量
* `net` 设置网络模式。使用和docker client 的 --net 参数一样的值
* `pid` 和宿主机系统共享进程命名空间，打开该选项的容器可以相互通过进程id来访问和操作
* `dns` 配置DNS服务器。可以是一个值，也可以是一个列表
* `cap_add，cap_drop` 添加或放弃容器的Linux能力（Capability）
* `dns_search` 配置DNS搜索域。可以是一个值也可以是一个列表
* 注意：使用compose对Docker容器进行编排管理时，需要编写docker-compose.yml文件，初次编写时，容易遇到一些比较低级的问题，导致执行docker-compose up时先解析yml文件的错误。比较常见的是yml对缩进的严格要求。yml文件还行后的缩进，不允许使用tab键字符，只能使用空格，而空格的数量也有要求，一般两个空格。

### 后台启动服务容器 

```
docker-compose up -d
```

### 启动所有已经存在的服务容器 

```
docker-compose start
```

### 停止所有已经处于运行状态的容器 

```
docker-compose stop
```

### 重启所有已经存在的容器 

```
docker-compose restart
```

### 启动 / 停止 / 重启 指定 (例如 php) 服务容器 

```
docker-compose start/stop/restart php
```

### 禁止docker启动时，容器自动启动

```
docker update --restart=no $(docker ps -a -q)
```

### 删除所有 (停止状态的) 服务容器 

```
docker-compose rm
```

### 强制删除所有服务容器 

```
docker-compose rm -f
```

### 验证 Compose 文件格式是否正确 

```
docker-compose config
```

### 关闭所有正在运行容器

```
docker ps | awk  '{print $1}' | xargs docker stop
或
docker stop $(docker ps -a -q)
```

### 删除所有容器应用

```
docker ps -a | awk  '{print $1}' | xargs docker rm
或
docker rm $(docker ps -a -q)
```

### 删除所有数据卷

```
docker volume rm $(docker volume ls -q)
```

### 删除所有network

```
docker network rm $(docker network ls -q)
```

### 删除所有镜像
```
docker rmi $(docker images -q)
```

### 清场五连

```
docker stop $(docker ps -a -q)
docker rm $(docker ps -a -q)
docker volume rm $(docker volume ls -q) 
docker network rm $(docker network ls -q)
docker rmi $(docker images -q)
```

