#!/bin/bash

# kill all subprocesses when receviced CTRL-C signal
trap "kill 0" SIGINT

export LC_MESSAGES=C

APPNAME="autossh_ng"
VERSION=1.0
LAST_UPDATED="2014-03-29"
DEBUG=0
RUN_FOR_FIRST_TIME=1
CURRENT_BASH_PID=$$
OLD_IFS="$IFS"

# 调试状态输出
function dbgprint () {
    if [[ ${DEBUG} -eq 1 ]];then
        echo "$*" >&2
    fi
}

# Display an error message.
function error () {
    echo "error: $*" >&2
}

# 判断SSH Tunnel是否实际可用
function isConnected() {
local ret_code=$(curl -I -s -x $LPROXY --connect-timeout $TIMEOUT $TARGET -w %{http_code} | tail -n1)
echo -n $ret_code
}

function usage() {
if [ -n "$*" ]; then
    error "$*"
fi
cat <<USAGE
$APPNAME $VERSION on $LAST_UPDATED

Usage: $0 [OPTION ...]
$0 -c config_file [-D] [-h] 

Options:
-c  SSH Tunnel连接配置参数文件
-D  是否开启调试输出模式
-h  输出程序版本信息和本页帮助信息
USAGE
}

# parse cmd line args 
ARGS=$(getopt c:Dh "$@")
GETOPT_STATUS=$?

if [[ $GETOPT_STATUS -ne 0 ]]; then
    error "internal error; getopt exited with status $GETOPT_STATUS"
    exit 1
fi

eval set -- "$ARGS"
 
while :; do
    case "$1" in
        -c) config_file="$2"; shift ;;
        -D) DEBUG=1 ;;
        -h) SHOWHELP="yes" ;;
        --) shift; break ;;
        *) error "internal error; getopt permitted \"$1\" unexpectedly"
           exit 1
           ;;
    esac
    shift
done

if [ "$SHOWHELP" ]; then
    usage
    exit 0
fi


if [ ! -e "$config_file" ];then
    usage "config_file $config_file not found, abort"
    exit 1
else
    source "$config_file"
fi

ssh_cmd="ssh $param ${laddr}:${lport} -p $rport ${rusr}@${rhost} -v"
ESTABLISHED="Entering interactive session"
ERR_PATTERN=(
"Broken pipe"
"Exit status -1"
"Could not resolve hostname"
"Connection refused"
"Operation timed out"
"Connection timed out"
"Network is down"
"Network is unreachable"
"Connection reset by peer"
"Connection closed"
"No route to host"
)

LPROXY="socks5://$laddr:$lport" # 
TARGET="http://www.baidu.com"
TIMEOUT=5
RETRY_DELAY=10

pids=()

# kill all other zombie shell pid
function kill_zombie_bash() {
bash_pid=($(ps aux | grep "$config_file" 2>/dev/null | grep -v grep | awk -F " " '{print $2}'))
for _pid in ${bash_pid[@]};do
    if [[ -n $CURRENT_BASH_PID ]] && [[ "$CURRENT_BASH_PID" != "$_pid" ]];then
        dbgprint "kill ${_pid}"
        kill -9 "$_pid" 2>/dev/null
    else
        dbgprint "skip ${_pid}"
    fi
done
}

# kill all other ssh tunnel process
function kill_zombie_ssh() {
local ssh_pid=$(ps aux | grep "$ssh_cmd" 2>/dev/null | grep -v grep | awk -F " " '{print $2}')
dbgprint "${ssh_pid[@]}"
kill -9 "${ssh_pid[@]}" 2>/dev/null
}

# kill all other tail monitor process
function kill_zombie_tail() {
local tailpid=$(ps aux | grep "tail -f $tmp_log" 2>/dev/null | grep -v grep | awk -F " " '{print $2}')
dbgprint "${tailpid[@]}"
kill -9 "${tailpid[@]}" 2>/dev/null
}

function start_ssh() {

# 杀死所有之前无用的ssh和tail进程
kill_zombie_ssh
kill_zombie_tail
kill_zombie_bash

# start ssh tunnel
exec $ssh_cmd >$tmp_log 2>&1 &

# monitor ssh tunnel connection
tail -f "$tmp_log" | while read -r line;
do
    local is_abnormal=0
    #    printf '[haha] %s\n' "$line"
    local tailpid=$(ps aux | grep "tail -f $tmp_log" 2>/dev/null | grep -v grep | awk -F " " '{print $2}')
    if [[ "$line" =~ $ESTABLISHED ]];then
        dbgprint "SSH Tunnel Established"
    fi
 
    IFS=$'\n'
    for pattern in ${ERR_PATTERN[@]};do
        if [[ "$line" =~ $pattern ]];then
            dbgprint "$pattern"
            is_abnormal=1
            break
        fi
    done
    IFS="$OLD_IFS"

    if [[ $is_abnormal -eq 1 ]];then
        sleep ${TIMEOUT}
        start_ssh
    fi
done
}

function is_daemon_running() {
local daemon_ps=($(ps -ef | grep -v grep | grep -c "$config_file"))
echo $daemon_ps
}

function make_ssh_tunnel() {
local ret_code=$(isConnected)
dbgprint $ret_code
if [[ "$ret_code" = "200" ]];then
    if [[ $RUN_FOR_FIRST_TIME -eq 1 ]];then
        if [[ $(is_daemon_running) -eq 0 ]];then
            start_ssh 
        fi
    else
        RUN_FOR_FIRST_TIME=0
    fi
    exit 0
else
    dbgprint "starting ssh tunnel connection ..."
    start_ssh 
fi
}

dbgprint $CURRENT_BASH_PID
while true;do
    make_ssh_tunnel
    sleep $RETRY_DELAY
done



