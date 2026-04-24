#!/bin/bash

############ MANDRAKE ############
# Database credentials
user="drophqsc_drake"
password="Tomj@vas001"
db_name="drophqsc_mandrake"
# Other options
backup_path="/home4/drophqsc/public_html/dropharma/db"
backup_path2="/home4/drophqsc/public_html/dropharma/maker"
date=$(date +"%Y%m%d_%H%M%S")
#date=$(date +"%d-%b-%Y")
# Set default file permissions
# umask 766
# Dump database into SQL file
mysqldump --user=$user --password=$password $db_name > $backup_path/$db_name-$date.sql
# Comprime el Backup 
gzip -9 $backup_path/$db_name-$date.sql
# Copio el archivo comprimido al directorio maker como db_drophqsc.sql.gz
cp $backup_path/$db_name-$date.sql.gz $backup_path2/db_drophqsc_mandrake.sql.gz

############ MEDRIKA ############
user="drophqsc_drake"
password="Tomj@vas001"
db_name="drophqsc_medrika"
# Other options
backup_path="/home4/drophqsc/public_html/dropharma/db"
backup_path2="/home4/drophqsc/public_html/dropharma/maker"
date=$(date +"%Y%m%d_%H%M%S")
#date=$(date +"%d-%b-%Y")
# Set default file permissions
# umask 766
# Dump database into SQL file
mysqldump --user=$user --password=$password $db_name > $backup_path/$db_name-$date.sql
# Comprime el Backup 
gzip -9 $backup_path/$db_name-$date.sql
# Copio el archivo comprimido al directorio maker como db_drophqsc.sql.gz
cp $backup_path/$db_name-$date.sql.gz $backup_path2/db_drophqsc_medrika.sql.gz


# Delete files older than 30 days
find $backup_path/* -mtime +5 -exec rm {} \;
