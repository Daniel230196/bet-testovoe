#!/usr/bin/env bash
if [ "$EUID" -ne 0 ]
  then echo "Please run as root"
  exit
fi

export IP=127.0.0.1
export ETC_HOSTS="/etc/hosts"
export HOST_NAME="tournaments.loc"
export HOST_REGEX="\(\s\+\)${HOST_NAME}\s*$"
export HOST_LINE="$(grep -e "${HOST_REGEX}" ${ETC_HOSTS})"

if [ -n "${HOST_LINE}" ]; then
    echo "${HOST_NAME} already exists : ${HOST_LINE}"
else
    echo "Adding ${HOST_NAME} to your ${ETC_HOSTS}";
    echo -e "${IP}\t${HOST_NAME}" >> ${ETC_HOSTS}
    echo -e "${HOST_NAME} was added succesfully \n ${HOST_LINE}";
fi

docker compose down --remove-orphans
docker compose up --build -d
docker compose logs -f