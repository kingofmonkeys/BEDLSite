use bedlstats;

CREATE TABLE users (
  ID mediumint(9) NOT NULL auto_increment,
  username varchar(60) NOT NULL,
  password varchar(60) NOT NULL,
  firstname varchar(60) Not null,
  lastname varchar(60),
  playerid int,
  PRIMARY KEY  (ID)
) ENGINE = INNODB AUTO_INCREMENT=2;

create table roles (
 roleid varchar(10) not null,
roledescription varchar(200),
primary key(roleid)) ENGINE = INNODB;

create table rolemapping(
roleid varchar(10),
userid mediumint(9),
primary key(roleid,userid)) ENGINE = INNODB;

CREATE TABLE weeks (
  week int NOT NULL,
  date date,
  PRIMARY KEY  (week)
) ENGINE = INNODB;


CREATE TABLE teams (
  teamid int NOT NULL,
  teamname varchar(50) NOT NULL,
  division varchar(2) NOT NULL,
  PRIMARY KEY  (teamid)
) ENGINE = INNODB;

CREATE TABLE schedule (
 ID mediumint(9) NOT NULL auto_increment,
  week int NOT NULL,
  hometeamid int NOT NULL,
  visitingteamid int Not null,  
  PRIMARY KEY  (ID)
) ENGINE = INNODB;

ALTER TABLE schedule
   ADD FOREIGN KEY
   (hometeamid)
   REFERENCES teams(teamid);
   

CREATE TABLE teamstats (
teamid INT NOT NULL ,
week INT NOT NULL ,
wins INT( 3 ) NOT NULL ,
losses INT (3) not null,
PRIMARY KEY ( teamid ,week ) 
) ENGINE = INNODB;

CREATE TABLE players (
player_id INT NOT NULL AUTO_INCREMENT ,
first_name VARCHAR( 50 ) NOT NULL ,
last_name VARCHAR( 50 ) NULL ,
division varchar(2) NOT NULL,
team_id VARCHAR( 100 ) NULL ,
PRIMARY KEY ( player_id ) 
) ENGINE = INNODB;

CREATE TABLE player_stats (
player_id INT NOT NULL ,
week_number INT( 2 ) NOT NULL ,
personal_points INT( 4 ) NOT NULL ,
PRIMARY KEY ( player_id , week_number ) 
) ENGINE = INNODB; 

create table notes (
userId mediumint(9) NOT NULL ,
week_number INT( 2 ) NOT NULL ,
notes longtext
)ENGINE = INNODB; 

create table player_shots (
player_id INT NOT NULL ,
week_number INT( 2 ) NOT NULL ,
shotId mediumint(9),
shotvalue VARCHAR( 50 )
)engine = innodb;

create table shots(
ID mediumint(9) NOT NULL auto_increment,
shotname VARCHAR( 50 ),
PRIMARY KEY ( ID ) 
)engine = innodb;