#!/bin/bash
# Database credentials
user="drophqsc_drake"
password="Tomj@vas001"
db_name="drophqsc_mandrake"
# Other options
backup_path="/home4/drophqsc/public_html/dropharma/db"
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