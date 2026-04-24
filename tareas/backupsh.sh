#!/bin/bash

############ MANDRAKE ############
# Database credentials
user="dropharm_drake"
password="Tomj@vas001"
db_name="dropharm_mandrake"
# Other options
backup_path="/home2/dropharm/dropharmadm/backups"
backup_path2="/home2/dropharm/public_html/mandrake.dropharmadm.com.ve/tareas"
date=$(date +"%Y%m%d_%H%M%S")
#date=$(date +"%d-%b-%Y")
# Set default file permissions
# umask 766
# Dump database into SQL file
mysqldump --user=$user --password=$password $db_name > $backup_path/$db_name-$date.sql
# Comprime el Backup 
gzip -9 $backup_path/$db_name-$date.sql
# Copio el archivo comprimido al directorio tareas como db_drophqsc.sql.gz
cp $backup_path/$db_name-$date.sql.gz $backup_path2/db_drophqsc_mandrake.sql.gz

# Delete files older than 30 days
find $backup_path/* -mtime +5 -exec rm {} \;
