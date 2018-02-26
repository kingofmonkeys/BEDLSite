use bedlstats;

insert into teams values (1,"Brewer's 1 (Dave)",1);
insert into teams values (2,"Brewer's 2 (Chuck)",1);
insert into teams values (3,"Hawley's Pub (Beno)",1);
insert into teams values (4,"Brewer's 3 (Ho)",2);
insert into teams values (5,"Angle 1 (Darby)",2);
insert into teams values (6,"Angle 2 (Thornie)",2);
insert into teams values (7,"Brewer's 4 (Donna)",3);
insert into teams values (8,"Brewer's 5 (Wayne)",3);
insert into teams values (9,"Brewer's 6 (John)",3);
insert into teams values (10,"Brewer's 7 (Fred)",3);


insert into weeks values (1,'2012-03-08');
insert into weeks values (2,'2012-03-15');
insert into weeks values (3,'2012-03-22');
insert into weeks values (4,'2012-03-29');
insert into weeks values (5,'2012-04-05');
insert into weeks values (6,'2012-04-12');
insert into weeks values (7,'2012-04-19');
insert into weeks values (8,'2012-04-26');
insert into weeks values (9,'2012-05-03');
insert into weeks values (10,'2012-05-10');
insert into weeks values (11,'2012-05-17');
insert into weeks values (12,'2012-05-24');
insert into weeks values (13,'2012-05-31');
insert into weeks values (14,'2012-06-07');
insert into weeks values (15,'2012-06-14');
insert into weeks values (16,'2012-06-21');
insert into weeks values (17,'2012-06-28');
insert into weeks values (18,'2012-07-05');
insert into weeks values (19,'2012-07-12');
insert into weeks values (20,'2012-07-19');


insert into schedule (week, hometeamid, visitingteamid) values (1,1,2);
insert into schedule (week, hometeamid, visitingteamid) values (1,3,4);
insert into schedule (week, hometeamid, visitingteamid) values (1,5,6);
insert into schedule (week, hometeamid, visitingteamid) values (1,7,8);
insert into schedule (week, hometeamid, visitingteamid) values (1,9,10);

insert into schedule (week, hometeamid, visitingteamid) values (2,3,1);
insert into schedule (week, hometeamid, visitingteamid) values (2,6,2);
insert into schedule (week, hometeamid, visitingteamid) values (2,4,5);
insert into schedule (week, hometeamid, visitingteamid) values (2,8,9);
insert into schedule (week, hometeamid, visitingteamid) values (2,10,7);

insert into schedule (week, hometeamid, visitingteamid) values (3,5,1);
insert into schedule (week, hometeamid, visitingteamid) values (3,2,3);
insert into schedule (week, hometeamid, visitingteamid) values (3,6,4);
insert into schedule (week, hometeamid, visitingteamid) values (3,7,9);
insert into schedule (week, hometeamid, visitingteamid) values (3,10,8);

insert into schedule (week, hometeamid, visitingteamid) values (4,1,8);
insert into schedule (week, hometeamid, visitingteamid) values (4,4,2);
insert into schedule (week, hometeamid, visitingteamid) values (4,3,10);
insert into schedule (week, hometeamid, visitingteamid) values (4,5,9);
insert into schedule (week, hometeamid, visitingteamid) values (4,7,6);

insert into schedule (week, hometeamid, visitingteamid) values (5,1,4);
insert into schedule (week, hometeamid, visitingteamid) values (5,3,2);
insert into schedule (week, hometeamid, visitingteamid) values (5,6,5);
insert into schedule (week, hometeamid, visitingteamid) values (5,9,8);
insert into schedule (week, hometeamid, visitingteamid) values (5,7,10);

insert into schedule (week, hometeamid, visitingteamid) values (6,2,1);
insert into schedule (week, hometeamid, visitingteamid) values (6,6,3);
insert into schedule (week, hometeamid, visitingteamid) values (6,5,4);
insert into schedule (week, hometeamid, visitingteamid) values (6,9,7);
insert into schedule (week, hometeamid, visitingteamid) values (6,8,10);

insert into schedule (week, hometeamid, visitingteamid) values (7,3,1);
insert into schedule (week, hometeamid, visitingteamid) values (7,5,2);
insert into schedule (week, hometeamid, visitingteamid) values (7,4,6);
insert into schedule (week, hometeamid, visitingteamid) values (7,8,7);
insert into schedule (week, hometeamid, visitingteamid) values (7,10,9);

insert into schedule (week, hometeamid, visitingteamid) values (8,1,9);
insert into schedule (week, hometeamid, visitingteamid) values (8,7,2);
insert into schedule (week, hometeamid, visitingteamid) values (8,3,5);
insert into schedule (week, hometeamid, visitingteamid) values (8,6,8);
insert into schedule (week, hometeamid, visitingteamid) values (8,10,4);




insert into schedule (week, hometeamid, visitingteamid) values (9,6,1);
insert into schedule (week, hometeamid, visitingteamid) values (9,2,8);
insert into schedule (week, hometeamid, visitingteamid) values (9,4,3);
insert into schedule (week, hometeamid, visitingteamid) values (9,9,7);
insert into schedule (week, hometeamid, visitingteamid) values (9,5,10);

insert into schedule (week, hometeamid, visitingteamid) values (10,2,1);
insert into schedule (week, hometeamid, visitingteamid) values (10,3,7);
insert into schedule (week, hometeamid, visitingteamid) values (10,4,9);
insert into schedule (week, hometeamid, visitingteamid) values (10,5,6);
insert into schedule (week, hometeamid, visitingteamid) values (10,8,10);

insert into schedule (week, hometeamid, visitingteamid) values (11,1,5);
insert into schedule (week, hometeamid, visitingteamid) values (11,3,2);
insert into schedule (week, hometeamid, visitingteamid) values (11,6,4);
insert into schedule (week, hometeamid, visitingteamid) values (11,9,8);
insert into schedule (week, hometeamid, visitingteamid) values (11,10,7);

insert into schedule (week, hometeamid, visitingteamid) values (12,1,3);
insert into schedule (week, hometeamid, visitingteamid) values (12,2,4);
insert into schedule (week, hometeamid, visitingteamid) values (12,5,8);
insert into schedule (week, hometeamid, visitingteamid) values (12,7,9);
insert into schedule (week, hometeamid, visitingteamid) values (12,6,10);

insert into schedule (week, hometeamid, visitingteamid) values (13,10,1);
insert into schedule (week, hometeamid, visitingteamid) values (13,2,9);
insert into schedule (week, hometeamid, visitingteamid) values (13,3,6);
insert into schedule (week, hometeamid, visitingteamid) values (13,5,4);
insert into schedule (week, hometeamid, visitingteamid) values (13,7,8);

insert into schedule (week, hometeamid, visitingteamid) values (14,1,2);
insert into schedule (week, hometeamid, visitingteamid) values (14,3,9);
insert into schedule (week, hometeamid, visitingteamid) values (14,4,7);
insert into schedule (week, hometeamid, visitingteamid) values (14,6,5);
insert into schedule (week, hometeamid, visitingteamid) values (14,10,8);

insert into schedule (week, hometeamid, visitingteamid) values (15,6,1);
insert into schedule (week, hometeamid, visitingteamid) values (15,2,3);
insert into schedule (week, hometeamid, visitingteamid) values (15,8,4);
insert into schedule (week, hometeamid, visitingteamid) values (15,5,7);
insert into schedule (week, hometeamid, visitingteamid) values (15,9,10);

insert into schedule (week, hometeamid, visitingteamid) values (16,3,1);
insert into schedule (week, hometeamid, visitingteamid) values (16,5,2);
insert into schedule (week, hometeamid, visitingteamid) values (16,4,6);
insert into schedule (week, hometeamid, visitingteamid) values (16,8,9);
insert into schedule (week, hometeamid, visitingteamid) values (16,7,10);

insert into schedule (week, hometeamid, visitingteamid) values (17,1,7);
insert into schedule (week, hometeamid, visitingteamid) values (17,3,8);
insert into schedule (week, hometeamid, visitingteamid) values (17,4,5);
insert into schedule (week, hometeamid, visitingteamid) values (17,6,9);
insert into schedule (week, hometeamid, visitingteamid) values (17,10,2);

insert into schedule (week, hometeamid, visitingteamid) values (18,2,1);
insert into schedule (week, hometeamid, visitingteamid) values (18,5,3);
insert into schedule (week, hometeamid, visitingteamid) values (18,6,4);
insert into schedule (week, hometeamid, visitingteamid) values (18,9,7);
insert into schedule (week, hometeamid, visitingteamid) values (18,8,10);

insert into schedule (week, hometeamid, visitingteamid) values (19,4,1);
insert into schedule (week, hometeamid, visitingteamid) values (19,3,2);
insert into schedule (week, hometeamid, visitingteamid) values (19,5,6);
insert into schedule (week, hometeamid, visitingteamid) values (19,9,8);
insert into schedule (week, hometeamid, visitingteamid) values (19,7,10);

insert into schedule (week, hometeamid, visitingteamid) values (20,1,3);
insert into schedule (week, hometeamid, visitingteamid) values (20,6,2);
insert into schedule (week, hometeamid, visitingteamid) values (20,5,4);
insert into schedule (week, hometeamid, visitingteamid) values (20,8,7);
insert into schedule (week, hometeamid, visitingteamid) values (20,10,9);





insert into players (first_name,last_name,team_id,division) values ("Dave","Cogett",1,"1");
insert into players (first_name,last_name,team_id,division) values ("Mike","Lagana",1,"1");
insert into players (first_name,last_name,team_id,division) values ("John","Downes",1,"1");
insert into players (first_name,last_name,team_id,division) values ("Wayne","D",1,"1");
insert into players (first_name,last_name,team_id,division) values ("Joey","Klein",1,"1");
insert into players (first_name,last_name,team_id,division) values ("Steve","Gray",1,"1");

insert into players (first_name,last_name,team_id,division) values ("Chuck","D",2,"1");
insert into players (first_name,last_name,team_id,division) values ("Doug","F",2,"1");
insert into players (first_name,last_name,team_id,division) values ("Greg","B",2,"1");
insert into players (first_name,last_name,team_id,division) values ("Daryl","J",2,"1");
insert into players (first_name,last_name,team_id,division) values ("Dre","Hall",2,"1");
insert into players (first_name,last_name,team_id,division) values ("Grace","B",2,"1");


insert into players (first_name,last_name,team_id,division) values ("Beno","",3,"1");
insert into players (first_name,last_name,team_id,division) values ("Chris","K",3,"1");
insert into players (first_name,last_name,team_id,division) values ("Jim","F",3,"1");
insert into players (first_name,last_name,team_id,division) values ("Jeff","F",3,"1");
insert into players (first_name,last_name,team_id,division) values ("Kelli","",3,"1");
insert into players (first_name,last_name,team_id,division) values ("Tie Die","",3,"1");

insert into players (first_name,last_name,team_id,division) values ("Howard","B",4,"2");
insert into players (first_name,last_name,team_id,division) values ("Will","Short",4,"2");
insert into players (first_name,last_name,team_id,division) values ("Nick","C",4,"2");
insert into players (first_name,last_name,team_id,division) values ("Tim","Carr",4,"2");
insert into players (first_name,last_name,team_id,division) values ("Chris","",4,"2");

insert into players (first_name,last_name,team_id,division) values ("Mike","Darby",5,"2");
insert into players (first_name,last_name,team_id,division) values ("Mike","Jones",5,"2");
insert into players (first_name,last_name,team_id,division) values ("George","R",5,"2");
insert into players (first_name,last_name,team_id,division) values ("Steve","H",5,"2");
insert into players (first_name,last_name,team_id,division) values ("Marla","J",5,"2");

insert into players (first_name,last_name,team_id,division) values ("Thornie","P",6,"2");
insert into players (first_name,last_name,team_id,division) values ("Lenny","J",6,"2");
insert into players (first_name,last_name,team_id,division) values ("Danny","P",6,"2");
insert into players (first_name,last_name,team_id,division) values ("Bill","B",6,"2");
insert into players (first_name,last_name,team_id,division) values ("Jim","W",6,"2");
insert into players (first_name,last_name,team_id,division) values ("Clay","P",6,"2");

insert into players (first_name,last_name,team_id,division) values ("Mike","Lantz",7,"1");
insert into players (first_name,last_name,team_id,division) values ("Donna","L",7,"3");
insert into players (first_name,last_name,team_id,division) values ("Tina","G",7,"3");
insert into players (first_name,last_name,team_id,division) values ("Mel","F",7,"3");

insert into players (first_name,last_name,team_id,division) values ("Dana","W",8,"3");
insert into players (first_name,last_name,team_id,division) values ("Dan","N",8,"3");
insert into players (first_name,last_name,team_id,division) values ("Kim","",8,"3");
insert into players (first_name,last_name,team_id,division) values ("Wayne","G",8,"3");
insert into players (first_name,last_name,team_id,division) values ("Woody","D",8,"3");
insert into players (first_name,last_name,team_id,division) values ("Mike","Webster",8,"3");

insert into players (first_name,last_name,team_id,division) values ("John","Moore",9,"3");
insert into players (first_name,last_name,team_id,division) values ("Charlie","S",9,"3");
insert into players (first_name,last_name,team_id,division) values ("Melissa","M",9,"3");
insert into players (first_name,last_name,team_id,division) values ("Mikey","C",9,"3");
insert into players (first_name,last_name,team_id,division) values ("Teddy","W",9,"3");
insert into players (first_name,last_name,team_id,division) values ("Nick","",9,"3");

insert into players (first_name,last_name,team_id,division) values ("Fred","J",10,"3");
insert into players (first_name,last_name,team_id,division) values ("Leeroy","",10,"3");
insert into players (first_name,last_name,team_id,division) values ("Ted","",10,"3");
insert into players (first_name,last_name,team_id,division) values ("Cory","Johnson",10,"3");

insert into roles values("ADMIN","Admin role");
insert into roles values("CAPT", "Team Captain role");


insert into users (username,password, firstname, lastname, playerid) values ('Kingofmonkeys','df31bc8a7b0666cbcac99cde53afd80a','Jimmy','Conrad',null);

insert into users (username,password, firstname, lastname,playerid) values ('Dave','df31bc8a7b0666cbcac99cde53afd80a','Dave','Cogett',(select players.player_id from players where players.first_name='Dave' and players.last_name='Cogett'));

insert into users (username,password, firstname, lastname,playerid) values ('Ho','df31bc8a7b0666cbcac99cde53afd80a','Howard','B',(select players.player_id from players where players.first_name='Howard' and players.last_name='B'));

insert into users (username,password, firstname, lastname,playerid) values ('Beno','df31bc8a7b0666cbcac99cde53afd80a','Beno','',(select players.player_id from players where players.first_name='Beno'));

insert into users (username,password, firstname, lastname,playerid) values ('Darby','df31bc8a7b0666cbcac99cde53afd80a','Mike','Darby',(select players.player_id from players where players.first_name='Mike' and players.last_name='Darby'));


insert into users (username,password, firstname, lastname,playerid) values ('Thornie','df31bc8a7b0666cbcac99cde53afd80a','Thornie','P',(select players.player_id from players where players.first_name='Thornie' and players.last_name='P'));

insert into users (username,password, firstname, lastname,playerid) values ('Chuck','df31bc8a7b0666cbcac99cde53afd80a','Chuck','',(select players.player_id from players where players.first_name='Chuck'));

insert into users (username,password, firstname, lastname,playerid) values ('Dana','df31bc8a7b0666cbcac99cde53afd80a','Dana','W',(select players.player_id from players where players.first_name='Dana' and players.last_name='W'));

insert into users (username,password, firstname, lastname,playerid) values ('Fred','df31bc8a7b0666cbcac99cde53afd80a','Fred','J',(select players.player_id from players where players.first_name='Fred' and players.last_name='J'));

insert into users (username,password, firstname, lastname,playerid) values ('John','df31bc8a7b0666cbcac99cde53afd80a','John','M',(select players.player_id from players where players.first_name='John' and players.last_name='M'));

insert into users (username,password, firstname, lastname,playerid) values ('Donna','df31bc8a7b0666cbcac99cde53afd80a','Donna','L',(select players.player_id from players where players.first_name='Donna' and players.last_name='L'));

insert into rolemapping values("ADMIN",2);
insert into rolemapping values("CAPT",3);
insert into rolemapping values("CAPT",4);
insert into rolemapping values("CAPT",5);
insert into rolemapping values("CAPT",6);
insert into rolemapping values("CAPT",7);
insert into rolemapping values("CAPT",8);
insert into rolemapping values("ADMIN",9);
insert into rolemapping values("CAPT",10);
insert into rolemapping values("ADMIN",11);
insert into rolemapping values("CAPT",12);


insert into shots (shotname) values ('Ton 80');
insert into shots (shotname) values ('High In');
insert into shots (shotname) values ('High Out');
insert into shots (shotname) values ('High Mark Out');




