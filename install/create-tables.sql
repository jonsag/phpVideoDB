CREATE TABLE IF NOT EXISTS videos (
        id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	path CHAR(255),
        fileName CHAR(255),
	directory CHAR(255),
        md5sum CHAR(50),
        duration DOUBLE(7,2), 
        height INT(4),
        width INT(4),
        fileSize INT(20),
        aspectRatio CHAR(10),
        videoCodec CHAR(10),
        audioCodec CHAR(10),
	videoBitrate INT(10),
	audioBitrate INT(10),
	overallBitRate INT(10),
	frameRate DOUBLE(5,2)
) CHARACTER SET UTF8;

CREATE TABLE IF NOT EXISTS actors (
       id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,   
       mf BOOLEAN,
       actorsName CHAR(50),
       aka CHAR(255),
       birthDate DATE,
       birthCity CHAR(100),
       birthCountry INT(3),      
       ethnicity INT(3),
       height INT(3),
       weight INT(3),
       hair INT(3),
       eyes INT(3),
       tattoos INT(3),
       body INT(3),
       breasts INT(3),
       legs INT(3),
       facePic CHAR(255),
       fullPic CHAR(255),
       nudePic CHAR(255),
       hcPic CHAR(255)
) CHARACTER SET UTF8;

CREATE TABLE IF NOT EXISTS ethnicity (
       id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
       properties CHAR(25)
) CHARACTER SET UTF8;

CREATE TABLE IF NOT EXISTS hair (
       id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
       properties CHAR(25)
) CHARACTER SET UTF8;

CREATE TABLE IF NOT EXISTS eyes (
       id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
       properties CHAR(25)
) CHARACTER SET UTF8;

CREATE TABLE IF NOT EXISTS tattoos (
       id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
       properties CHAR(25)
) CHARACTER SET UTF8;

CREATE TABLE IF NOT EXISTS body (
       id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
       properties CHAR(25)
) CHARACTER SET UTF8;

CREATE TABLE IF NOT EXISTS breasts (
       id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
       properties CHAR(25)
) CHARACTER SET UTF8;

CREATE TABLE IF NOT EXISTS  legs (
       id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
       properties CHAR(25)
) CHARACTER SET UTF8;

CREATE TABLE IF NOT EXISTS `countries` (
`id` int(11) NOT NULL auto_increment,
`country_code` varchar(2) NOT NULL default '',
`country_name` varchar(100) NOT NULL default '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=240 ;
