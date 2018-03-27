**Rest API with JWT Authentication** 

Delete unnecessary keys every hour:

Add event:

CREATE DEFINER=`root`@`localhost` EVENT `1` ON SCHEDULE EVERY 1 HOUR STARTS '2018-03-27 13:41:00' ON COMPLETION NOT PRESERVE ENABLE DO DELETE
  FROM `rkey`
 WHERE `rkey`.`id` NOT IN(
     SELECT `rkey` 
       FROM `lockkey`)
       AND  `rkey`.`id` NOT IN (SELECT `rkey` 
       FROM `employeekey`)
       
And enable scheduler in mysql:

SET global event_scheduler = ON;




To use mosquitto:

`sudo apt-get install php-pear`

`sudo apt-get install php7.0-dev`

`sudo apt-get install libmosquitto-dev`

`sudo pecl install Mosquitto-alpha`

Add extension=mosquitto.so under Dynamic Extensions of the file /etc/php/7.0/cli/php.ini

Documentation is available here:  
https://api-test.opendoors.od.ua:1013/doc
 