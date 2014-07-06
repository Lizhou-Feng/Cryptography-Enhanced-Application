#!/bin/bash
# 产生一个100MB的全0文件
dd bs=1024 count=102400 if=/dev/zero of=example.txt 
