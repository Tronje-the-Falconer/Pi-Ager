#!/bin/bash

mkloop()
{
  LOOP="$(losetup -f --show -P "${IMGFILE}")"
  if [ $? -ne 0 ]; then
    errexit "Unable to create loop device"
  fi
}

rmloop()
{
   losetup -d "${LOOP}"
}

fsckerr()
{
  rmloop
  errexit "Filesystem appears corrupted "$1" resize2fs"
}

errexit()
{
  echo ""
  echo -e "\e[91m$1\e[39m"
  echo ""
  exit 1
}

if [ $(id -u) -ne 0 ]; then
  errexit "$0 must be run as root user"
fi
PGMNAME="$(basename $0)"
for PID in $(pidof -x -o %PPID "${PGMNAME}"); do
  if [ ${PID} -ne $$ ]; then
    errexit "${PGMNAME} is already running"
  fi
done
gdisk -l "${DEVICE}" &> /dev/null
if [ $? -eq 127 ]; then
  echo ""
  echo "gdisk not installed. Installing gdisk"
  echo ""
  apt-get update
  apt-get install gdisk
fi
IMGFILE="$1"
if [ "${IMGFILE}" = "" ]; then
  errexit "Usage: $0 imagefile [Additional MB]"
fi
if [ ! -f "${IMGFILE}" ] || [ ! -s "${IMGFILE}" ]; then
  errexit "${IMGFILE} is missing or empty"
fi
mkloop
FS_TYPE=$(blkid "${LOOP}p2" | sed -n 's|^.*TYPE="\(\S\+\)".*|\1|p')
rmloop
if [ "${FS_TYPE}" = "f2fs" ]; then
  errexit "Cannot shrink F2FS filesystem"
fi
answer="$2"
if [[ ! "${answer}" =~ ^[0-9]+$ ]]; then
  errexit "You must specify additional MB."
fi
while [ "${answer:0:1}" = "0" ]; do
  answer="${answer:1}"
done
if [ ${#answer} -eq 0 ]; then
  answer=0
fi
ADDMB=${answer}
echo ""
INFO="$(sfdisk -d "${IMGFILE}")"
BOOTBEG=$(sed -n "s|^${IMGFILE}1.*start=\s*\([0-9]\+\).*$|\1|p" <<< "${INFO}")
BOOTEND=$((${BOOTBEG} + $(sed -n "s|^${IMGFILE}1.*size=\s*\([0-9]\+\).*$|\1|p" <<< "${INFO}") - 1))
ROOTBEG=$(sed -n "s|^${IMGFILE}2.*start=\s*\([0-9]\+\).*$|\1|p" <<< "${INFO}")
PARTUUID_1="$(sed -n "s|^${IMGFILE}1.*uuid=\(\S\+\).*$|\1|p" <<< "${INFO}")"
PARTUUID_2="$(sed -n "s|^${IMGFILE}2.*uuid=\(\S\+\).*$|\1|p" <<< "${INFO}")"
PTUUID="$(sed -n "s|^label-id: \(\S\+\).*$|\1|p" <<< "${INFO}")"
PTTYPE="$(sed -n "s|^label: \(\S\+\).*$|\1|p" <<< "${INFO}")"
if [[ "${PTTYPE}" != "dos" && "${PTTYPE}" != "gpt" ]]; then
  errexit "Unsupported partition table type: ${PTTYPE}"
fi
mkloop
e2fsck -f -p -v "${LOOP}p2"
if [ $? -gt 2 ]; then
  fsckerr "before"
fi
echo ""
resize2fs -f -M "${LOOP}p2"
resize2fs -f -M "${LOOP}p2"
resize2fs -f -M "${LOOP}p2"
e2fsck -f -n "${LOOP}p2"
if [ $? -ne 0 ]; then
  fsckerr "after"
fi
INFO="$(tune2fs -l "${LOOP}p2" 2>/dev/null)"
rmloop
NEWSIZE=$(sed -n 's|^Block count:\s*\(.*\)|\1|p' <<< "${INFO}")
BLKSIZE=$(sed -n 's|^Block size:\s*\(.*\)|\1|p' <<< "${INFO}")
NEWEND=$((${ROOTBEG} + (${NEWSIZE} * (${BLKSIZE} / 512)) + ((${ADDMB} * 1024 * 1024) / 512) - 1))
if [ "${PTTYPE}" = "gpt" ]; then
  ((NEWEND += 33))
fi
truncate -s $(((${NEWEND} + 1) * 512)) "${IMGFILE}"
if [ "${PTTYPE}" = "dos" ]; then
  sfdisk --delete "${IMGFILE}" 2 > /dev/null
  echo "${ROOTBEG},+" | sfdisk -N2 "${IMGFILE}" &> /dev/null
else
  sgdisk -Z "${IMGFILE}" &> /dev/null
  sgdisk -n 1:${BOOTBEG}:${BOOTEND} "${IMGFILE}" > /dev/null
  sgdisk -t 1:0700 "${IMGFILE}" > /dev/null
  sgdisk -n 2:${ROOTBEG}:0 "${IMGFILE}" > /dev/null
  sgdisk -t 2:8300 "${IMGFILE}" > /dev/null
  sgdisk -u 1:"${PARTUUID_1}" "${IMGFILE}" > /dev/null
  sgdisk -u 2:"${PARTUUID_2}" "${IMGFILE}" > /dev/null
  sgdisk -U "${PTUUID}" "${IMGFILE}" > /dev/null
  gdisk "${IMGFILE}" <<EOF > /dev/null
r
h
1
n
0c
n
n
w
y
EOF
fi
if [ ${ADDMB} -ne 0 ]; then
  echo ""
  mkloop
  e2fsck -f -n "${LOOP}p2"
  if [ $? -ne 0 ]; then
    fsckerr "before"
  fi
  echo ""
  resize2fs -f "${LOOP}p2"
  e2fsck -f -n "${LOOP}p2"
  if [ $? -ne 0 ]; then
    fsckerr "after"
  fi
  rmloop
fi
echo ""
