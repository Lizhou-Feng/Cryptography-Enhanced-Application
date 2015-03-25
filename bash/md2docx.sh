#!/bin/bash

IFS=$'\n'
pandoc=$(which pandoc)

if [[ ! -x "${pandoc}" ]];then
  echo "pandoc binary is required to run this script"
  exit 1
fi

input_dir="${1:-/tmp/write-ups}"
input_file="README.md"

if [[ ! -d "${input_dir}" ]];then
  echo "batch convert markdown files to .docx"
  echo "usage: $0 <path to README.md>"
  echo "       $0 /tmp/write-ups" 
  exit 1
fi

docs=($(find ${input_dir} -iname "${input_file}"))

for doc in ${docs[@]};do
  echo "converting ${doc}"
  pushd $(dirname "$doc") >/dev/null
  "$pandoc" "$input_file" -o "${input_file}.docx"
  popd > /dev/null
done

