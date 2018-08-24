drop database if exists tutor_scheduler;
create database tutor_scheduler;
use tutor_scheduler;

create table user
(
    user_id int primary key auto_increment not null,
    username varchar(40) not null unique,
    type char(1) not null, /* S=student, T=tutor, A=admin */
    date_created date not null,
    email varchar(100) not null,
    index email_index (email(20)),
    password varchar(40) not null,
    firstname varchar(40) not null,
    surname varchar(40) not null,
    index name_index (surname, firstname),
    phone varchar(20),
    street_address varchar(100),
    other_address varchar(100),
    town varchar(40),
    state char(2),
    zipcode char(10),
    country_id int
);

create table country
(
    country_id int primary key not null,
    name char(21) not null,
    index name_index (name)
);

create table session
(
    session_id int primary key auto_increment not null,
    title varchar(100) not null,
    scheduled_start_time datetime not null,
    length_minutes int not null,
    teacher int not null,
    foreign key (teacher) references user(user_id) on delete cascade,
    index teacher_index(teacher),
    student int not null,
    foreign key (student) references user(user_id) on delete cascade,
    index student_index(student),
    student_login_time datetime,
    student_logout_time datetime,
    teacher_login_time datetime,
    teacher_logout_time datetime,
    actual_start_time datetime,
    reminder1_sent char(1) default 'n',
    reminder2_sent char(1) default 'n'
);


insert into country values(1, 'Afghanistan');
insert into country values(2, 'Albania');
insert into country values(3, 'Algeria');
insert into country values(4, 'Angola');
insert into country values(5, 'Argentina');
insert into country values(6, 'Australia');
insert into country values(7, 'Austria');
insert into country values(8, 'Bahrain');
insert into country values(9, 'Bangledesh');
insert into country values(10, 'Belgium');
insert into country values(11, 'Bolivia');
insert into country values(12, 'Botswana');
insert into country values(13, 'Brazil');
insert into country values(14, 'Bulgaria');
insert into country values(15, 'Burma');
insert into country values(16, 'Cambodia');
insert into country values(17, 'Canada');
insert into country values(18, 'Chile');
insert into country values(19, 'China');
insert into country values(20, 'Colombia');
insert into country values(21, 'Congo');
insert into country values(22, 'Cuba');
insert into country values(23, 'Cyprus');
insert into country values(24, 'Czech Republic');
insert into country values(25, 'Denmark');
insert into country values(26, 'Ecuador');
insert into country values(27, 'Egypt');
insert into country values(28, 'Ethiopia');
insert into country values(29, 'Finland');
insert into country values(30, 'France');
insert into country values(31, 'Germany');
insert into country values(32, 'Ghana');
insert into country values(33, 'Greece');
insert into country values(34, 'Greenland');
insert into country values(35, 'Guyana');
insert into country values(36, 'Holland');
insert into country values(37, 'Hong Kong');
insert into country values(38, 'Hungary');
insert into country values(39, 'Iceland');
insert into country values(40, 'India');
insert into country values(41, 'Indonesia');
insert into country values(42, 'Iran');
insert into country values(43, 'Iraq');
insert into country values(44, 'Ireland');
insert into country values(45, 'Israel');
insert into country values(46, 'Italy');
insert into country values(47, 'Japan');
insert into country values(48, 'Jordan');
insert into country values(49, 'Kenya');
insert into country values(50, 'Korea');
insert into country values(51, 'Kuwait');
insert into country values(52, 'Latvia');
insert into country values(53, 'Lebanon');
insert into country values(54, 'Libya');
insert into country values(55, 'Lichtenstein');
insert into country values(56, 'Lithuania');
insert into country values(57, 'Luxembourg');
insert into country values(58, 'Madagascar');
insert into country values(59, 'Malaysia');
insert into country values(60, 'Mauritius');
insert into country values(61, 'Mexico');
insert into country values(62, 'Monaco');
insert into country values(63, 'Mongolia');
insert into country values(64, 'Morocco');
insert into country values(65, 'Mozambique');
insert into country values(66, 'Nepal');
insert into country values(67, 'New Zealand');
insert into country values(68, 'Nicaragua');
insert into country values(69, 'Norway');
insert into country values(70, 'Oman');
insert into country values(71, 'Pakistan');
insert into country values(72, 'Panama');
insert into country values(73, 'Paraguay');
insert into country values(74, 'Peru');
insert into country values(75, 'Phillipines');
insert into country values(76, 'Poland');
insert into country values(77, 'Portugal');
insert into country values(78, 'Qatar');
insert into country values(79, 'Romania');
insert into country values(80, 'Russia');
insert into country values(81, 'Rwanda');
insert into country values(82, 'Saudi Arabia');
insert into country values(83, 'Sierra Leone');
insert into country values(84, 'Singapore');
insert into country values(85, 'Somalia');
insert into country values(86, 'South Africa');
insert into country values(87, 'Spain');
insert into country values(88, 'Sri Lanka');
insert into country values(89, 'Sudan');
insert into country values(90, 'Sweden');
insert into country values(91, 'Switzerland');
insert into country values(92, 'Syria');
insert into country values(93, 'Taiwan');
insert into country values(94, 'Tanzania');
insert into country values(95, 'Thailand');
insert into country values(96, 'Turkey');
insert into country values(97, 'United Kindgom');
insert into country values(101, 'USA');
insert into country values(102, 'Uganda');
insert into country values(103, 'Ukraine');
insert into country values(104, 'United Arab Emirates');
insert into country values(105, 'Uruguay');
insert into country values(106, 'Venezuela');
insert into country values(107, 'Vietnam');
insert into country values(108, 'Yemen');
insert into country values(110, 'Zaire');
insert into country values(111, 'Zambia');
insert into country values(112, 'Zimbabwe');

insert into country values(113, 'Slovenia');
insert into country values(114, 'Macedonia');
insert into country values(115, 'Bosnia');
insert into country values(116, 'Belarus');
insert into country values(117, 'Slovakia');
insert into country values(118, 'Moldova');
insert into country values(119, 'Brunei');
insert into country values(120, 'Croatia');
insert into country values(121, 'Georgia');
insert into country values(122, 'Estonia');
insert into country values(123, 'Malta');
insert into country values(124, 'Solomon Islands');
insert into country values(125, 'El Salvador');
insert into country values(126, 'Belize');
insert into country values(127, 'Guatemala');
insert into country values(128, 'Honduras');
insert into country values(129, 'Costa Rica');
insert into country values(130, 'Puerto Rico');
insert into country values(131, 'Barbados');
insert into country values(132, 'Bermuda');
insert into country values(133, 'Bahamas');
insert into country values(134, 'Turks and Caicos');
insert into country values(135, 'Cayman Islands');
insert into country values(136, 'Haiti');
insert into country values(137, 'Dominican Republic');
insert into country values(138, 'Liberia');
insert into country values(139, 'Trinidad and Tobago');
