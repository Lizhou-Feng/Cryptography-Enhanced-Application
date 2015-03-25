#!/bin/bash
# CTF赛题收集
# https://github.com/ctfs/write-ups-2013
# https://github.com/ctfs/write-ups-2014
# https://github.com/ctfs/write-ups-2015
# 当前脚本已针对上述3个题库的检索筛选功能测试通过
# tested on : Yosemtie / Ubuntu 10.04

offline_dirs=() # 有题解，并且有附件的赛题
output_dir="/tmp/" # 整理好的赛题输出目录
input_dir="write-ups/" # 待检索目录


# bash数组分隔符重新定义为换行符
IFS=$'\n'

# 遍历出所有的赛题目录
dirs=($(find "${input_dir}" -type d ! -path "*/.git/*" -exec sh -c '(ls -p "{}"|grep />/dev/null)||echo "{}"' \;))

# 查找每个赛题目录
for dir in ${dirs[@]};do
  # 1. 筛选掉README.md中包含TODO字样的目录
  notodo=$(grep -i 'TODO' "${dir}/README.md" -c 2>/dev/null)
  # 2. 筛选README.md中包含write字样
  writeup=$(grep -i 'write' "${dir}/README.md" -c 2>/dev/null)
  if [[ $notodo -eq 0 && $writeup -gt 0 ]];then
    # 3. 检查该目录下文件数量是否大于2
    offline=$(find "${dir}" -type f | wc -l | tr -d ' ')
    if [[ $offline -gt 1 ]];then
      offline_dirs+=(${dir})
      rsync -qavzR "${dir}" "${output_dir}"
    fi
  fi
done

echo "赛题总数：${#dirs[@]}"
echo "有题解并有附件的赛题总数：${#offline_dirs[@]}"

