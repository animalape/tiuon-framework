<p align="center"><img src="https://www.tiuon.com/assets/img/favicon-icon-192x192.png"></p>
<p align="center">Tiuon框架1.0</p>

## 简介

Tiuon框架 是一个免费开源的，快速、简单的面向对象的 轻量级PHP开发框架 ，创立于2016年初，遵循Apache2开源协议发布，是为了敏捷WEB应用开发和简化企业应用开发而诞生的。
Tiuon框架从诞生以来一直秉承简洁实用的设计原则，在保持出色的性能和至简的代码的同时，也注重易用性。
并且拥有众多的原创功能和特性，在社区团队的积极参与下，在易用性、扩展性和性能方面不断优化和改进，已经成长为国内最领先和最具影响力的WEB应用开发框架，
众多的典型案例确保可以稳定用于商业以及门户级的开发。


## 安装配置
 - 系统要求：推荐lamp架构，php至少5.5.0以上版本(包括5.5.0)，推荐5.5.0以上最新版本，Apache或Nginx
 
**Apache配置样例：**
	
``` ApacheConf
<Virtualhost *>
    # 网站域名，（写入配置时请把中文注释去掉，下同）
	ServerName www.tiuon.com
	
	# DocumentRoot一定要/结尾
	DocumentRoot "D:/wwwroot/"
	
    # 以下内容无需修改
    
	DirectoryIndex index.html index.php
	RewriteEngine On
	RewriteRule .*/\..* - [F,L]
	
    RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-f [AND]
    RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-d
    RewriteRule ^/.* /index.php [PT,L]

    # 以下是一些文件的缓存设置，可修改或去掉
    <IfModule expires_module>
    	ExpiresActive On
    	ExpiresByType text/css "access plus 3 days"
    	ExpiresByType image/png "access plus 14 days"
    	ExpiresByType image/gif "access plus 14 days"
    	ExpiresByType image/jpeg "access plus 14 days"
    	ExpiresByType application/x-shockwave-flash "access plus 28 days"
	</IfModule>
</Virtualhost>
```

!!! 注意，请去掉中文注释 


**Nginx配置样例：**

``` Nginx
server {
    set         $www /home/usr/wwwroot;
    root        $www;
    index       index.html index.htm index.php;
    listen      80;
    charset     utf-8;
    server_name www.tiuon.com;
    server_name tiuon.com;

    # 以下基本不用怎么修改
    
    location ~* .(css|js)$ {
        if (-f $request_filename) {
            expires 3d;
            break;
        }
    }
    location ~* .(jpg|gif|png|jpeg|bmp)$ {
        if (-f $request_filename) {
            expires 15d;
            break;
        }
    }
    location ~* .(swf|zip|rar|gz|7z)$ {
        if (-f $request_filename) {
            expires 1m;
            break;
        }
    }

    # rewrite
    if (!-e $request_filename) {
        rewrite ^/.* /index.php last;
    }

    location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        
        # 以下解决用php输出js,css等文件导致出错的问题
        gzip on;
        gzip gzip_min_length 1100;
        gzip_buffers 4 8k;
        gzip_types text/plain application/x-javascript text/css image;
    }
}
```

!!! 其中 `fastcgi_params` 指 `/etc/nginx/fastcgi_params` 文件，某些系统可指定为 `/etc/nginx/fastcgi_php` 可设置 `include fastcgi_php;`

## 数据库
 - 支持 Mysql
 - 支持 Sqlite
 
## 商业友好的开源协议

Tiuon框架 遵循Apache2开源协议发布。Apache Licence是著名的非盈利开源组织Apache采用的协议。该协议和BSD类似，鼓励代码共享和尊重原作者的著作权，同样允许代码修改，再作为开源或商业软件发布
