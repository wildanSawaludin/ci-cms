<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$query =
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('forum_topics')  . " (
`id` INT NOT NULL AUTO_INCREMENT ,
`tid` VARCHAR( 20 ) NOT NULL ,
`title` VARCHAR( 255 ) NOT NULL ,
`description` TEXT NOT NULL ,
`email` VARCHAR( 200 ) NOT NULL ,
`username` VARCHAR( 100 ) NOT NULL ,
`date` INT( 11 ) NOT NULL ,
`gid` VARCHAR( 20 ) NOT NULL ,
`last_date` INT( 11 ) NOT NULL ,
`messages` INT( 11 ) NOT NULL ,
`last_username` VARCHAR( 100 ) NOT NULL ,
`last_mid` VARCHAR( 50 ) NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX (
`tid`, `title`
)
);";

$this->db->query($query);

$query =
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('forum_messages')  . " (
`id` INT NOT NULL  AUTO_INCREMENT,
`mid` VARCHAR(20) NOT NULL ,
`pid` VARCHAR(20) NOT NULL ,
`tid` VARCHAR(20) NOT NULL ,
`date` INT(11) NOT NULL,
`email` VARCHAR( 200 ) NOT NULL ,
`username` VARCHAR( 100 ) NOT NULL ,
`title` VARCHAR( 255 ) NOT NULL ,
`message` TEXT NOT NULL ,
`last_date` INT( 11 ) NOT NULL ,
`replies` INT( 11 ) NOT NULL ,
`last_username` VARCHAR( 100 ) NOT NULL ,
`last_mid` VARCHAR( 50 ) NOT NULL ,
`hits` INT( 11 ) NOT NULL ,
`notify` CHAR(1) NOT NULL DEFAULT 'N',
PRIMARY KEY ( `id` ) ,
KEY `username` (`username`),
KEY `mid` (`mid`),
KEY `pid` (`pid`),
KEY `tid` (`tid`)
);";

$this->db->query($query);

$query =
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('forum_admins')  . " (
`id` INT NOT NULL  AUTO_INCREMENT,
`tid` VARCHAR(20) NOT NULL ,
`date` INT(11) NOT NULL,
`email` VARCHAR( 200 ) NOT NULL ,
`username` VARCHAR( 100 ) NOT NULL ,
`level` INT(1) NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `tid`, `username` )
);";

$this->db->query($query);

$query = 
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('forum_settings')  . " (
`id` INT NOT NULL  AUTO_INCREMENT,
`name` VARCHAR( 100 ) NOT NULL ,
`value` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `name` )
);";

$this->db->query($query);

$query = "INSERT INTO " . $this->db->dbprefix('form_settings') . " (`name`, `value`) VALUES ('style', 'blue')"; 
$this->db->query($query);

