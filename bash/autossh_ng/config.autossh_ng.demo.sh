#!/bin/bash

# Customizable vars
# 远程SSH主机连接相关信息
rusr="your_ssh_host_name"
rhost="your_ssh_host_ip"
rport="your_ssh_host_port"
param="-o ConnectTimeout=10 -qTnN -4 -D" # leave this unmodified unless you know how to set

# 本地SSH Tunnel监听信息
lport="7070"
laddr="127.0.0.1"

# SSH连接过程所有调试数据写入临时文件，供脚本分析SSH连接状态用
tmp_log="/tmp/autossh.demo.log" # 多个SSH远程主机连接实例时请注意使用不同的临时文件名
