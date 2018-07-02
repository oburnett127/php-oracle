drop table client cascade constraints;
drop table clientsession cascade constraints;
drop table student cascade constraints;
drop table prerequisite cascade constraints;
drop table courseoffering cascade constraints;
drop table coursedesc cascade constraints;
drop table coursestaken cascade constraints;
drop table semester cascade constraints;

create table client (
	clientid varchar2(8) primary key,
	password varchar2(12) not null,
	studentflag number(1) not null,
	adminflag number(1) not null
 );

create table clientsession (
	sessionid varchar2(32) primary key,
	clientid varchar2(8) unique not null,
	sessiondate date,
	foreign key (clientid) references client(clientid)
 );

create table coursedesc (
	coursenumber varchar2(9) primary key,
	coursetitle varchar2(64) not null,
	credithours number(1) not null
 );

create table semester (
	season varchar2(6),
	year number(4),
	enrolldeadline timestamp not null,
	primary key(season, year)
 );

create table student (
	studentid varchar2(8) primary key,
	clientid varchar2(8) not null,
	fname varchar2(12) not null,
	lname varchar2(16) not null,
	age number(3),
	streetaddress varchar2(35),
	city varchar2(20),
	state varchar2(2),
	zipcode number(9),
	status number(1) not null,
	graduateflag number(1) not null,
	foreign key (clientid) references client(clientid)
 );

create table prerequisite (
	coursenumber varchar2(9),
	prereqcourse varchar2(9),
	primary key (coursenumber, prereqcourse),
	foreign key (coursenumber) references coursedesc(coursenumber),
	foreign key (prereqcourse) references coursedesc(coursenumber)
 );

create table courseoffering (
	courseseqid number(5) primary key,
	coursenumber varchar2(9) not null,
	semseason varchar2(6) not null,
	semyear number(4) not null,
	time varchar2(15) not null,
	daysofweek varchar2(5) not null,
	startdate date not null,
	enddate date not null,
	maxnumseats number(3) not null,
	coursedeadline timestamp not null,
	foreign key (coursenumber) references coursedesc(coursenumber),
	foreign key (semseason, semyear) references semester(season, year)
 );

create table coursestaken (
	studentid varchar2(8),
	courseseqid number(5),
	grade varchar2(1),
	primary key (studentid, courseseqid),
	foreign key (studentid) references student(studentid),
	foreign key (courseseqid) references courseoffering(courseseqid)
 );

insert into client values ('student1', 'study', 1, 0);
insert into client values ('student2', 'study', 1, 0);
insert into client values ('student3', 'study', 1, 0);
insert into client values ('student4', 'study', 1, 0);
insert into client values ('student5', 'study', 1, 0);
insert into client values ('student6', 'study', 1, 0);
insert into client values ('student7', 'study', 1, 0);
insert into client values ('student8', 'study', 1, 0);
insert into client values ('student9', 'study', 1, 0);
insert into client values ('stu10', 'study', 1, 0);
insert into client values ('stu11', 'study', 1, 0);
insert into client values ('stu12', 'study', 1, 0);
insert into client values ('expert1', 'server', 0, 1);
insert into client values ('stuadm1', 'java', 1, 1);

insert into student values ('ab000001', 'student1', 'Alvin', 'Barnett', 27, '5025 N. Meridian', 'Edmond', 'OK', 73078, 0, 0);
insert into student values ('zt000002', 'student2', 'Zachary', 'Thomas', 28, '6023 N. Western', 'Oklahoma City', 'OK', 73287, 0, 0);
insert into student values ('ze000003', 'student3', 'Zachary', 'Evans', 29, '7175 N. Eastern', 'Moore', 'OK', 73588, 0, 0);
insert into student values ('ms000004', 'student4', 'Michael', 'Smith', 27, '7276 S. Bryant', 'Norman', 'OK', 73489, 0, 0);
insert into student values ('js000005', 'student5', 'John', 'Smith', 26, '7387 S. Wilshire', 'Mustang', 'OK', 73531, 0, 0);
insert into student values ('ws000006', 'student6', 'William', 'Stevens', 24, '6578 N. Kelly', 'El Reno', 'OK', 73679, 0, 0);
insert into student values ('ar000007', 'student7', 'Amanda', 'Robinson', 25, '5687 N. Broadway', 'Edmond', 'OK', 73689, 0, 0);
insert into student values ('bp000008', 'student8', 'Britney', 'Peterson', 28, '8787 W. 3rd Street', 'Oklahoma City', 'OK', 73078, 0, 0);
insert into student values ('rj000009', 'student9', 'Robert', 'Johnson', 29, '5025 E. 10th Street', 'Moore', 'OK', 73287, 0, 0);
insert into student values ('lh000010', 'stu10', 'Leah', 'Hill', 28, '4896 N. Portland', 'Norman', 'OK', 73588, 0, 0);
insert into student values ('sa000011', 'stu11', 'Sandy', 'Adams', 27, '8689 W. Memorial', 'Mustang', 'OK', 73489, 0, 0);
insert into student values ('dp000012', 'stu12', 'Diane', 'Peterson', 26, '7024 N. MacArthur', 'El Reno', 'OK', 73531, 0, 0);

insert into coursedesc values ('MATH 1513', 'College Algebra', 3);
insert into coursedesc values ('MATH 1592', 'Plane Trigonometry', 3);
insert into coursedesc values ('MATH 2313', 'Calculus I', 3);
insert into coursedesc values ('MATH 2323', 'Calculus II', 3);
insert into coursedesc values ('MATH 5024', 'Advanced Calculus', 4);
insert into coursedesc values ('MATH 5134', 'Mathematics Research', 4);
insert into coursedesc values ('STAT 3103', 'Statistical Methods', 3);
insert into coursedesc values ('CMSC 1512', 'Beginning Programming', 2);
insert into coursedesc values ('CMSC 1613', 'Programming I', 3);
insert into coursedesc values ('CMSC 2123', 'Discrete Structures', 3);

insert into semester values('Spring', 2012, to_timestamp('2012-01-01 12:00:00', 'YYYY-MM-DD HH:MI:SS'));
insert into semester values('Fall', 2011, to_timestamp('2011-08-08 12:00:00', 'YYYY-MM-DD HH:MI:SS'));
insert into semester values('Summer', 2011, to_timestamp('2011-06-06 12:00:00', 'YYYY-MM-DD HH:MI:SS'));
insert into semester values('Spring', 2011, to_timestamp('2011-01-01 12:00:00', 'YYYY-MM-DD HH:MI:SS'));

insert into prerequisite values ('MATH 2313', 'MATH 1513');
insert into prerequisite values ('MATH 2313', 'MATH 1592');
insert into prerequisite values ('MATH 2323', 'MATH 1513');
insert into prerequisite values ('MATH 2323', 'MATH 1592');
insert into prerequisite values ('MATH 2323', 'MATH 2313');
insert into prerequisite values ('STAT 3103', 'MATH 1513');
insert into prerequisite values ('MATH 5024', 'MATH 1513');
insert into prerequisite values ('MATH 5024', 'MATH 1592');
insert into prerequisite values ('MATH 5024', 'MATH 2313');
insert into prerequisite values ('MATH 5024', 'MATH 2323');
insert into prerequisite values ('MATH 5134', 'MATH 1513');
insert into prerequisite values ('MATH 5134', 'MATH 1592');
insert into prerequisite values ('MATH 5134', 'MATH 2313');
insert into prerequisite values ('MATH 5134', 'MATH 2323');
insert into prerequisite values ('MATH 5134', 'STAT 3103');
insert into prerequisite values ('CMSC 1512', 'MATH 1513');
insert into prerequisite values ('CMSC 1613', 'MATH 1513');
insert into prerequisite values ('CMSC 1613', 'CMSC 1512');
insert into prerequisite values ('CMSC 2123', 'MATH 1513');
insert into prerequisite values ('CMSC 2123', 'CMSC 1512');
insert into prerequisite values ('CMSC 2123', 'CMSC 1613');

insert into courseoffering values (13000, 'MATH 1513', 'Spring', 2011, '9:30 - 10:45AM', 'MW', to_date('2011-01-05', 'YYYY-MM-DD'), to_date('2011-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-01-05 02:00:00', 'YYYY-MM-DD HH:MI:SS'));
insert into courseoffering values (13001, 'MATH 1592', 'Spring', 2011, '10:00  11:15AM', 'TR', to_date('2011-01-05', 'YYYY-MM-DD'), to_date('2011-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-01-05 02:00:00', 'YYYY-MM-DD HH:MI:SS'));
insert into courseoffering values (13002, 'CMSC 2123', 'Spring', 2011,  '9:30 - 10:45AM', 'MW', to_date('2011-01-05', 'YYYY-MM-DD'), to_date('2011-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-01-05 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (13003, 'MATH 2323', 'Spring', 2011, '2:45 - 4:00PM', 'TR', to_date('2011-01-05', 'YYYY-MM-DD'), to_date('2011-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-01-06 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (13004, 'STAT 3103', 'Spring', 2011, '2:45 - 4:00PM', 'MW', to_date('2011-01-05', 'YYYY-MM-DD'), to_date('2011-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-01-05 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (13005, 'MATH 5024', 'Spring', 2011, '5:45 - 7:00PM', 'TR', to_date('2011-01-05', 'YYYY-MM-DD'), to_date('2011-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-01-06 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (13006, 'MATH 5134', 'Spring', 2011, '5:45 - 7:00PM', 'MW', to_date('2011-01-05', 'YYYY-MM-DD'), to_date('2011-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-01-05 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (14000, 'MATH 1513', 'Summer', 2011, '9:30 - 10:45AM', 'TR', to_date('2011-06-05', 'YYYY-MM-DD'), to_date('2011-07-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-06-05 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (14001, 'MATH 1592', 'Summer', 2011, '10:00 - 11:15AM', 'MW', to_date('2011-06-05', 'YYYY-MM-DD'), to_date('2011-07-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-06-06 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (14002, 'CMSC 2123', 'Summer', 2011,  '9:30 - 10:45AM', 'TR', to_date('2011-06-05', 'YYYY-MM-DD'), to_date('2011-07-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-06-05 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (14003, 'MATH 2323', 'Summer', 2011, '2:45 - 4:00PM', 'MW', to_date('2011-06-05', 'YYYY-MM-DD'), to_date('2011-07-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-06-06 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (14004, 'STAT 3103', 'Summer', 2011, '2:45 - 4:00PM', 'TR', to_date('2011-06-05', 'YYYY-MM-DD'), to_date('2011-07-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-06-06 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (14005, 'MATH 5024', 'Summer', 2011, '5:45 - 7:00PM', 'MW', to_date('2011-06-05', 'YYYY-MM-DD'), to_date('2011-07-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-06-05 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (14006, 'MATH 5134', 'Summer', 2011, '5:45 - 7:00PM', 'TR', to_date('2011-06-05', 'YYYY-MM-DD'), to_date('2011-07-07', 'YYYY-MM-DD'), 5, to_timestamp('2011-06-05 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (15000, 'MATH 1513', 'Fall', 2011, '9:30 - 10:45AM', 'MW', to_date('2011-08-22', 'YYYY-MM-DD'), to_date('2011-12-15', 'YYYY-MM-DD'), 5, to_timestamp('2011-08-22 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (15001, 'MATH 1592', 'Fall', 2011, '10:00 - 11:15AM', 'TR', to_date('2011-08-22', 'YYYY-MM-DD'), to_date('2011-12-15', 'YYYY-MM-DD'), 5, to_timestamp('2011-08-23 12:00:00', 'YYYY-MM-DD HH:MI:SS'));
insert into courseoffering values (15002, 'CMSC 2123', 'Fall', 2011,  '9:30 - 10:45AM', 'MW', to_date('2011-08-22', 'YYYY-MM-DD'), to_date('2011-12-15', 'YYYY-MM-DD'), 5, to_timestamp('2011-08-23 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (15003, 'MATH 2323', 'Fall', 2011, '2:45 - 4:00PM', 'TR', to_date('2011-08-22', 'YYYY-MM-DD'), to_date('2011-12-15', 'YYYY-MM-DD'), 5, to_timestamp('2011-08-22 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (15004, 'STAT 3103', 'Fall', 2011, '2:45 - 4:00PM', 'MW', to_date('2011-08-22', 'YYYY-MM-DD'), to_date('2011-12-15', 'YYYY-MM-DD'), 5, to_timestamp('2011-08-22 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (15005, 'MATH 5024', 'Fall', 2011, '5:45 - 7:00PM', 'TR', to_date('2011-08-22', 'YYYY-MM-DD'), to_date('2011-12-15', 'YYYY-MM-DD'), 5, to_timestamp('2011-08-23 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (15006, 'MATH 5134', 'Fall', 2011, '5:45 - 7:00PM', 'MW', to_date('2011-08-22', 'YYYY-MM-DD'), to_date('2011-12-15', 'YYYY-MM-DD'), 5, to_timestamp('2011-01-22 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (16000, 'MATH 1513', 'Spring', 2012, '9:30 - 10:45AM', 'TR', to_date('2012-01-10', 'YYYY-MM-DD'), to_date('2012-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2012-01-13 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (16001, 'MATH 1592', 'Spring', 2012, '10:00 - 11:15AM', 'MW', to_date('2012-01-10', 'YYYY-MM-DD'), to_date('2012-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2012-01-13 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (16002, 'CMSC 2123', 'Spring', 2012,  '9:30 - 10:45AM', 'TR', to_date('2012-01-10', 'YYYY-MM-DD'), to_date('2012-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2012-01-12 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (16003, 'MATH 2323', 'Spring', 2012, '2:45 - 4:00PM', 'MW', to_date('2012-01-10', 'YYYY-MM-DD'), to_date('2012-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2012-01-12 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (16004, 'STAT 3103', 'Spring', 2012, '2:45 - 4:00PM',	'TR', to_date('2012-01-10', 'YYYY-MM-DD'), to_date('2012-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2012-01-12 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (16005, 'MATH 5024', 'Spring', 2012, '5:45 - 7:00PM', 'MW', to_date('2012-01-10', 'YYYY-MM-DD'), to_date('2012-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2012-01-12 12:00:00', 'YYYY-MM-DD HH:MI:SS')); 
insert into courseoffering values (16006, 'MATH 5134', 'Spring', 2012, '5:45 - 7:00PM', 'TR', to_date('2012-01-10', 'YYYY-MM-DD'), to_date('2012-05-07', 'YYYY-MM-DD'), 5, to_timestamp('2012-01-12 12:00:00', 'YYYY-MM-DD HH:MI:SS'));

insert into coursestaken values('ab000001', 13000, 'A');
insert into coursestaken values('zt000002', 13000, 'B');
insert into coursestaken values('ze000003', 13000, 'C');
insert into coursestaken values('ms000004', 13000, 'D');
insert into coursestaken values('js000005', 13000, 'F');
insert into coursestaken values('ws000006', 13001, 'A');
insert into coursestaken values('ar000007', 13001, 'B');
insert into coursestaken values('bp000008', 13001, 'C');
insert into coursestaken values('rj000009', 13001, 'F');
insert into coursestaken values('lh000010', 13001, 'A');
insert into coursestaken values('sa000011', 13002, 'B');
insert into coursestaken values('dp000012', 13002, 'C');

commit;
