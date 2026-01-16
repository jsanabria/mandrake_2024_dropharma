#!/bin/bash
# Database credentials
user="decodbnt_windeco"
password="p?ec=T_2eFd9"
db_name="decodbnt_windeco"
# Other options
backup_path="/home2/decodbnt/decodibo.decodibo.com/db"
date=$(date +"%Y%m%d_%H%M%S")
#date=$(date +"%d-%b-%Y")
# Set default file permissions
# umask 766
# Dump database into SQL file
mysqldump --user=$user --password=$password $db_name > $backup_path/$db_name-$date.sql
# Comprime el Backup 
gzip -9 $backup_path/$db_name-$date.sql
# Delete files older than 30 days
find $backup_path/* -mtime +5 -exec rm {} \;