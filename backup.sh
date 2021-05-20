#!/bin/bash
cd "`dirname $0`"

# Several times a day
mysqldump xlnthu |gzip > backup/database/database-`date +%Y%m%d-%H%M`.sql.gz
