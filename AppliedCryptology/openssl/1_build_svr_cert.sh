#!/bin/bash

# 制作服务器私钥（务必在本地备份保存好）
openssl genrsa -out server.key 2048
# 制作CSR文件（供认证CA签署证书时使用）
# 国家、省要与上面CA证书一致，否则签署时必然要失败。
# Common Name 此时相当重要，请输入你需要SSL支持的域名，如 localhost（域名只能一个），否则浏览器提示证书错误。
openssl req -new -key server.key -out server.csr
