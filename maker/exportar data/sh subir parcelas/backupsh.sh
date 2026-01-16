#!/bin/bash
# Database credentials
user="root"
password="142536"
host="localhost"
db_name="hercules"
# Other options
backup_path="/var/www/html/backup/"
date=$(date +"%Y%m%d_%H%M%S")
#date=$(date +"%d-%b-%Y")
# Set default file permissions
# umask 766
# Dump database into SQL file
mysqldump --user=$user --password=$password --host=$host $db_name > $backup_path/$db_name-$date.sql
# Comprime el Backup 
gzip -9 $backup_path/$db_name-$date.sql
# Delete files older than 30 days
find $backup_path/*.gz -mtime +5 -exec rm {} \;
