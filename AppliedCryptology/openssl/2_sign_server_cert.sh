#!/bin/bash

# 签署服务器证书（如果是授权CA签署，则该步骤交由认证CA签署）
openssl ca -in server.csr -out demoCA/certs/server.crt
