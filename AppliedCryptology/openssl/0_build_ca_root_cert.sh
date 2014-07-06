#!/bin/bash
# 读取或按需修改 /usr/lib/ssl/openssl.cnf
# 在当前工作目录下
mkdir demoCA
cd demoCA
mkdir private crl certs newcerts
# 以上是可选操作，取决于系统默认配置文件的配置，我的做法是取消所有子目录的设定，统一使用当前目录（省事）
# 以下是必须操作
echo '01' > serial # 在demoCA目录下新建serial文件并写入01。
touch index.txt    # 在demoCA目录下新建index.txt的空文件

# 制作CA根证书
openssl genrsa -out private/cakey.pem 2048  # 生成CA根证书的私钥
openssl req -new -x509 -key private/cakey.pem -out cacert.pem # 生成CA根证书


