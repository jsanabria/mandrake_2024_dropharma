#!/bin/bash

sftp -oPort=22 decodbnt@204.11.59.230:/home2/decodbnt/decocont.decodibo.com <<EOF
put /home4/drophqsc/public_html/dropharma/maker/db_drophqsc.sql.gz  
exit
EOF
