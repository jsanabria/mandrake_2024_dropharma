#!/bin/sh

export SSHPASS=Terrarium365** 
sshpass -e sftp -oBatchMode=no -b - decodbnt@204.11.59.230 << ! 
	cd /home2/decodbnt/decocont.decodibo.com 
	put /home4/drophqsc/public_html/dropharma/maker/db_drophqsc.sql.gz  
	bye 
!
