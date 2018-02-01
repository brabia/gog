gogo.com
-------------

HOST FILE CONFIGURATION
-----------------------
192.168.33.10 gogo.com
192.168.33.10 api.gogo.com

NGINIX CONFIGURATION
-----------------------

```php
# gogo.com
server{
	listen 80;
	server_name gogo.com;
	root /home/vagrant/gogo.com/public_html/public/;
	index index.php index.html index.htm;
	
	location / {
        try_files $uri $uri/ /index.php?$args;
		include /etc/nginx/mime.types;
    }
	location ~ \.php$ {
		try_files $uri = 404;
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/var/run/php5-fpm.sock;
		fastcgi_index index.php;
		include fastcgi_params;
		include /etc/nginx/mime.types;
		fastcgi_read_timeout 200;
	}
}

# api.gogo.com
server{
	listen 80;
	server_name api.gogo.com;
	root /home/vagrant/gogo.com/public_html/api/;
	index index.php index.html index.htm;
	
	location / {
        try_files $uri $uri/ /index.php?$args;
    }
	location ~ \.php$ {
		try_files $uri = 404;
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/var/run/php5-fpm.sock;
		fastcgi_index index.php;
		include fastcgi_params;
		include /etc/nginx/mime.types;
		fastcgi_read_timeout 200;
	}
}
```

INSTALLATION:
-----------------------


DATABASE CONFIGURATION
-----------------------

```php
-- ----------------------------
-- Table structure for Carts
-- ----------------------------
DROP TABLE IF EXISTS `Carts`;
CREATE TABLE `Carts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CartID` text,
  `Products` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Products`;
CREATE TABLE `Products` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` text,
  `Price` float DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

```

Usage:
-----------------------


**NOTES:**

- gogo.com: public web site
- api.gogo.com: api end point
