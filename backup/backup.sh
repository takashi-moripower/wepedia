#!/usr/local/bin/bash

BACKUP_DIR="/home/wenet/www/backup"
SQL_SERVER="mysql521.db.sakura.ne.jp"
SQL_USER="wenet"
SQL_PASS="9j30o-40dj3ycjtwa_r5bzzgrba4fmxf"
DATABASE="wenet_wepedia"

TODAY=`date '+%Y%m%d_%H%M%S'`
FILENAME=backup_${TODAY}.sql

cd ${BACKUP_DIR}

#SQLバックアップ処理
/usr/local/bin/mysqldump --default-character-set=binary -Q -h ${SQL_SERVER} -u${SQL_USER} -p${SQL_PASS} ${DATABASE} > ${FILENAME} 2> dump.error.txt

#15日以上古いバックアップファイルは削除
find ${BACKUP_DIR} -type f -name "backup_*.sql" -mtime +15 -exec rm -f {} \;;
