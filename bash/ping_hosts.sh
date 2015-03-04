#!/bin/bash

TIMEOUT=3
COUNT=5
STATS_PATTERN="packet loss"
if [[ $(uname) = "Linux" ]];then
  OK_PATTERN=" 0% packet loss"
else
OK_PATTERN=" 0.0% packet loss"
fi

watch_ips=(
"www.baidu.com"
"weibo.com"
"twitter.com"
"facebook.com"
"www.cuc.edu.cn"
)

task_count=${#watch_ips[@]}
task_pid=1

# 错误提示信息输出
function error() {
echo -e "\033[31m $* \033[0m"
}

# 成功信息输出
function ok() {
echo -e "\033[32m $* \033[0m"
}

# 警告信息输出
function warn() {
echo -e "\033[33m $* \033[0m"
}

function benchmark() {
ip="$1"
task_pid=$2
tmpdir="$3"
tmpfile="$tmpdir/$task_pid"
if [[ $(uname) = "Linux" ]];then
  ping -W $TIMEOUT -c $COUNT "$ip" 1>>"${tmpfile}.tmp" 2>/dev/null
else
  ping -t $TIMEOUT -c $COUNT "$ip" 1>>"${tmpfile}.tmp" 2>/dev/null
fi
ret_code=$?
ret_msg=$(grep "$STATS_PATTERN" "${tmpfile}.tmp")
if [[ $ret_code -eq 0 ]];then
  if [[ $(echo -n $ret_msg | grep -c "$OK_PATTERN") -eq 1 ]];then
    ok "$ip is alive with $ret_msg"
  else
    warn "$ip is working with $ret_msg" 
  fi
else
  error "$ip is dead with ret_code $ret_code and $ret_msg"
fi
mv "${tmpfile}.tmp" "${tmpfile}.done"
}

function init() {
[[ -d ${TMPDIR} ]] || TMPDIR="/tmp/"
DIR="${TMPDIR}$RANDOM"
mkdir "$DIR"
ret=$?
while [ $ret -ne 0 ];do
  DIR="${TMPDIR}$RANDOM"
  mkdir "$DIR"
  ret=$?
done
echo -n "$DIR"
}

tmpdir=$(init)

#echo $tmpdir

for ip in ${watch_ips[@]};do
  benchmark $ip $task_pid $tmpdir &
  task_pid=$((task_pid+1))
done

echo "benchmarking $task_count hosts ... "
while [[ $(ls -l "${tmpdir}" 2>/dev/null | grep -c ".done") -ne $task_count ]];do
#  echo "completed $(ls -l "${tmpdir}" 2>/dev/null | grep -c "*.done") hosts"
  sleep 1
done
echo "done"

if [[ -d "${tmpdir}" && "${tmpdir}" != "/" ]];then
  rm "${tmpdir}"/*
  rmdir "${tmpdir}"
fi
