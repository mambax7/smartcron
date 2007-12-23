CREATE TABLE `smartcron_job` (
  `jobid` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `description` TEXT NOT NULL default '',
  `status` int(5) NOT NULL default 0,
  `job_type` varchar(255) NOT NULL default '',
  `threshold` int(11) NOT NULL default 0,
  `breakpoint` int(11) NOT NULL default 0,
  `file` varchar(255) NOT NULL default '',
  `weight` int(11) NOT NULL default 0,
  `execute_interval` int(11) NOT NULL default 0,
  `next_time` int(11) NOT NULL default 0,
  `data` TEXT NOT NULL default '',
  PRIMARY KEY  (`jobid`)
) TYPE=MyISAM COMMENT='SmartCron by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;

CREATE TABLE `smartcron_iteration` (
  `iterationid` int(11) NOT NULL auto_increment,
  `jobid` int(11) NOT NULL default 0,
  `start_date` int(11) NOT NULL default 0,
  `end_date` int(11) NOT NULL default 0,
  `log` LONGTEXT NOT NULL default '',
  PRIMARY KEY  (`iterationid`)
) TYPE=MyISAM COMMENT='SmartCron by The SmartFactory <www.smartfactory.ca>' AUTO_INCREMENT=1 ;

CREATE TABLE `smartcron_meta` (
  `metakey` varchar(50) NOT NULL default '',
  `metavalue` varchar(255) NOT NULL default '',
  PRIMARY KEY (`metakey`)
) TYPE=MyISAM COMMENT='SmartCron by The SmartFactory <www.smartfactory.ca>' ;

INSERT INTO `smartcron_meta` VALUES ('version',0);
