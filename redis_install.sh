#!/bin/bash
# create by sunguide
# date 2015-03-15
# desc install redis

basedir=`pwd`
homepath=/usr/local/redis
[ -d $homepath ] && {
echo "redis already installed "
exit
} || {
  echo "begin init redis dir"
  for i in bin etc data log ;do
    mkdir -pv $homepath/$i
  done
}
[ -f redis-stable.tar.gz ] || {
echo "begin to download redis package"
wget http://download.redis.io/releases/redis-stable.tar.gz
echo " download redis package completed"
}
echo "begin to make source code"
tar xzf redis-stable.tar.gz
cd redis-stable
make
echo "make source code completed"
cd src
cp -r redis-benchmark redis-check-aof redis-check-dump redis-sentinel redis-cli redis-server $homepath/bin
[ -f $basedir/redis.conf ] && {
  echo "start redis-server"
  for conf in redis.conf redis_salve.conf ;do
  cp $basedir/$conf $homepath/etc/
  $homepath/bin/redis-server $homepath/etc/$conf
  done
} || {
 echo "not found $basedir/redis.conf"
}