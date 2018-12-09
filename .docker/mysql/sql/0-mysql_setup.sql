#
# Copy createdb.sql.example to createdb.sql
# then uncomment then set database name and username to create you need databases
#
# example: .env MYSQL_USER=appuser and need db name is myshop_db
#
#    CREATE DATABASE IF NOT EXISTS `myshop_db` ;
#    GRANT ALL ON `myshop_db`.* TO 'appuser'@'%' ;
#
#
# this sql script will auto run when the mysql container starts and the $DATA_PATH_HOST/mysql not found.
#
# if your $DATA_PATH_HOST/mysql exists and you do not want to delete it, you can run by manual execution:
#
#     docker-compose exec mysql bash
#     mysql -u root -p < /docker-entrypoint-initdb.d/createdb.sql
#

CREATE DATABASE IF NOT EXISTS `loadbee_tracking` COLLATE 'utf8_general_ci' ;
CREATE USER IF NOT EXISTS 'loadbee_tracking'@'%'; 
GRANT ALL ON `loadbee_tracking`.* TO 'loadbee_tracking'@'%' IDENTIFIED BY 'loadbee_tracking';

CREATE DATABASE IF NOT EXISTS `loadbee` COLLATE 'utf8_general_ci' ;
CREATE USER IF NOT EXISTS 'loadbee'@'%'; 
GRANT ALL ON `loadbee`.* TO 'loadbee'@'%' IDENTIFIED BY 'loadbee';

CREATE DATABASE IF NOT EXISTS `backend` COLLATE 'utf8_general_ci' ;
CREATE USER IF NOT EXISTS 'backend'@'%'; 
GRANT ALL ON `backend`.* TO 'backend'@'%' IDENTIFIED BY 'backend';

FLUSH PRIVILEGES ;
