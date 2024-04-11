create table Player (
    playerID int primary key, 
    firstName char(20) not null, 
    lastName char(20) not null, 
    jerseyNumber int not null 
);

create table PlayerStatistics (
    firstName char(20) not null, 
    lastName char(20) not null, 
    jerseyNumber int not null,
    position char(2), 
    score int,
    height int, 
    weights int, 
    points int default 0, 
    rebound int default 0, 
    assist int default 0,
    primary key (firstName, lastName, jerseyNumber)
);


create table Team (
    teamID int primary key, 
    tName char(20) not null, 
    city char(15) not null, 
    states char(15) not null
);

create table TeamInfo (
    tName char(20) not null, 
    city char(15) not null, 
    states char(15) not null,
    foundedIn int,
    coach char(30),
    generalManage char(30),
    primary key (tName, city, states)
);

create table Conference (
    conferenceName char(4) primary key
);

create table Division (
    divisionName char(10) primary key
);



create table Arena (
    arenaName char(20) primary key, 
    streetAddress char(50), 
    postalCode int,
    numberOfSeats int,
    teamID int not null
);

create table ArenaLocation (
    postalCode int primary key, 
    city char(15), 
    states char(15)
);



create table TeamSignPlayer (
    teamID int, 
    playerID int, 
    since int, 
    primary key (playerID, teamID), 
    foreign key (playerID) references Player, 
    foreign key (teamID) references Team
);


create table Match (
    homeTeamID int, 
    awayTeamID int, 
    matchDate date, 
    homeScore int, 
    awayScore int,
    primary key (homeTeamID, awayTeamID, matchDate), 
    foreign key (homeTeamID) references Team (teamID), 
    foreign key (awayTeamID) references Team (teamID)
);



create table TeamWinTitle (
    titleName char(40), 
    titleYear int, 
    teamID int, 
    primary key (titleName, titleYear),
    foreign key (teamID) references Team
);



create table TeamConference (
    teamID int primary key, 
    conferenceName char(4) not null, 
    foreign key (teamID) references Team, 
    foreign key (conferenceName) references Conference
);


create table TeamDivision (
    teamID int primary key,
    divisionName char(10) not null, 
    foreign key (teamID) references Team,
    foreign key (divisionName) references Division 
);


create table LeagueRepresentative (
    rID int primary key, 
    rName char(30) not null
);

create table Monitor (
    teamID int, 
    playerID int, 
    rID int not null, 
    primary key (teamID, playerID),
    foreign key (teamID, playerID) references TeamSignPlayer,
    foreign key (rID) references LeagueRepresentative
);

create table Sponsorship (
    teamID int, 
    sponsorID int, 
    sporsorName char(30) not null, 
    amount int default 10000, 
    primary key (teamID, sponsorID), 
    foreign key (teamID) references Team
);

create table OrganizationSponsor (
    teamID int,
    sponsorID int, 
    CEO char(30), 
    industry char(30),
    primary key (teamID, sponsorID),
    foreign key (teamID, sponsorID) references Sponsorship
);

create table IndividualSponsor (
    teamID int,
    sponsorID int, 
    occupation char(30), 
    primary key (teamID, sponsorID),
    foreign key (teamID, sponsorID) references Sponsorship
);







insert into Conference values ('EAST');
insert into Conference values ('WEST');
insert into Conference values ('MIDDLE');
insert into Conference values ('NORTH');
insert into Conference values ('SOUTH');

insert into Division values ('Atlantic');
insert into Division values ('Central');
insert into Division values ('Southeast');
insert into Division values ('Northwest');
insert into Division values ('Pacific');
insert into Division values ('Southwest');

insert into LeagueRepresentative values (1, 'Jenny');
insert into LeagueRepresentative values (2, 'John');
insert into LeagueRepresentative values (3, 'Johnson');
insert into LeagueRepresentative values (4, 'Josh');
insert into LeagueRepresentative values (5, 'Jackson');


insert into Player values (1, 'Stephen', 'Curry', 30);
insert into Player values (2, 'Jaylen', 'Brown', 7);
insert into Player values (3, 'Lebron', 'James', 23);
insert into Player values (4, 'Jimmy', 'Butler', 22);
insert into Player values (5, 'Eric', 'Gordon', 10);

insert into PlayerStatistics values ('Stephen', 'Curry', 30, 'PG', 90, 188, 84, 0, 0, 0);
insert into PlayerStatistics values ('Jaylen', 'Brown', 7, 'SG', 88, 198, 101, 0, 0, 0);
insert into PlayerStatistics values ('Lebron', 'James', 23, 'SF', 91, 206, 113, 0, 0, 0);
insert into PlayerStatistics values ('Jimmy', 'Butler', 22, 'SF', 86, 201, 104, 0, 0, 0);
insert into PlayerStatistics values ('Eric', 'Gordon', 10, 'SG', 80, 191, 98, 0, 0, 0);




insert into Team values (1, 'Golden State Warriors', 'San Francisco', 'California');
insert into Team values (2, 'Boston Celtics', 'Boston', 'Massachusetts');
insert into Team values (3, 'Los Angeles Lakers', 'Los Angeles', 'California');
insert into Team values (4, 'Miami Heat', 'Miami', 'Florida');
insert into Team values (5, 'Houston Rockets', 'Houston', 'Texas');

insert into TeamInfo values ('Golden State Warriors', 1946, 'San Francisco', 'California', 'Steve Kerr', 'Bob Myers');
insert into TeamInfo values ('Boston Celtics', 1946, 'Boston', 'Massachusetts', 'Ime Udoka', 'Brad Stevens');
insert into TeamInfo values ('Los Angeles Lakers', 1947, 'Los Angeles', 'California', 'Darvin Ham', 'Rob Pelinka');
insert into TeamInfo values ('Miami Heat', 1988, 'Miami', 'Florida', 'Erik Spoelstra', 'Andy Elisburg');
insert into TeamInfo values ('Houston Rockets', 1967, 'Houston', 'Texas', 'Stephen Silas', 'Rafael Stone');



insert into Match values (1, 2, '2022-01-01', 100, 90);
insert into Match values (2, 3, '2022-01-02', 88, 96);
insert into Match values (3, 4, '2022-01-03', 92, 94);
insert into Match values (4, 5, '2022-01-04', 124, 103);
insert into Match values (5, 1, '2022-01-05', 82, 76);

insert into TeamSignPlayer values (1, 1, 2009);
insert into TeamSignPlayer values (2, 2, 2016);
insert into TeamSignPlayer values (3, 3, 2018);
insert into TeamSignPlayer values (4, 4, 2019);
insert into TeamSignPlayer values (5, 5, 2016);

insert into Arena values ('Chase Center', '1 Warriors Way', 94158, 18064, 1);
insert into Arena values ('FTX Arena', '601 Biscayne Boulevard', 33132, 19600, 4);
insert into Arena values ('TD Garden', '100 Legends Way', 02114, 19156, 2);
insert into Arena values ('Crypto.com Arena', '1111 South Figueroa Street', 90015, 19079, 3);
insert into Arena values ('Toyota Center', '1510 Polk Street', 77003, 18104, 5);

insert into ArenaLocation values (94158, 'San Francisco', 'California');
insert into ArenaLocation values (33132, 'Miami', 'Florida');
insert into ArenaLocation values (02114, 'Boston', 'Massachusetts');
insert into ArenaLocation values (90015, 'Los Angeles', 'California');
insert into ArenaLocation values (77003, 'Houston', 'Texas');

insert into TeamWinTitle values ('NBA Championship', 2022, 1);
insert into TeamWinTitle values ('West Conference Title', 2022, 1);
insert into TeamWinTitle values ('East Conference Title', 2022, 2);
insert into TeamWinTitle values ('Pacific Division Title', 2019, 1);
insert into TeamWinTitle values ('NBA Championship', 2020, 3);

insert into TeamConference values (1, 'WEST');
insert into TeamConference values (2, 'EAST');
insert into TeamConference values (3, 'NORTH');
insert into TeamConference values (4, 'SOUTH');
insert into TeamConference values (5, 'MIDDLE');

insert into TeamDivision values (1, 'Pacific');
insert into TeamDivision values (2, 'Atlantic');
insert into TeamDivision values (3, 'Pacific');
insert into TeamDivision values (4, 'Southeast');
insert into TeamDivision values (5, 'Southwest');

insert into Monitor values (1, 1, 1);
insert into Monitor values (2, 2, 1);
insert into Monitor values (3, 3, 2);
insert into Monitor values (4, 4, 2);
insert into Monitor values (5, 5, 2);

insert into Sponsorship values (1, 1, 'Rakuten Group Inc.', 500000);
insert into Sponsorship values (2, 2, 'Vistaprint', 500000);
insert into Sponsorship values (3, 3, 'Bibigo', 500000);
insert into Sponsorship values (4, 4, 'Gabrielle Union', 100000);
insert into Sponsorship values (5, 5, 'Travis Scott', 100000);
insert into Sponsorship values (1, 6, 'Travis Scot', 100000);
insert into Sponsorship values (2, 7, 'Travis Sct', 100000);
insert into Sponsorship values (3, 8, 'Travott', 100000);
insert into Sponsorship values (4, 9, 'Tis Scott', 100000);
insert into Sponsorship values (5, 10, 'Tcott', 100000);

insert into OrganizationSponsor values (1, 1, 'Hiroshi Mikitani', 'E-commerce');
insert into OrganizationSponsor values (2, 2, 'Robert Keane', 'E-commerce');
insert into OrganizationSponsor values (3, 3, 'Choi Eun-seok', 'Food Service');
insert into OrganizationSponsor values (4, 4, 'Elon Musk', 'Automotive');
insert into OrganizationSponsor values (5, 5, 'Jeff Bezos', 'E-commerce');

insert into IndividualSponsor values (1, 6, 'Engineer');
insert into IndividualSponsor values (2, 7, 'Producer');
insert into IndividualSponsor values (3, 8, 'Artist');
insert into IndividualSponsor values (4, 9, 'Actor');
insert into IndividualSponsor values (5, 10, 'Professor');

