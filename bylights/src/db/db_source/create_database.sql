-- *********************************************
-- * SQL MySQL generation                      
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.2              
-- * Generator date: Sep 14 2021              
-- * Generation date: Fri Oct 25 15:17:24 2024 
-- * LUN file: C:\Users\dario\2023-2024\studio\tirocinio-web\ByLights-db.lun 
-- * Schema: schema/2-1-1 
-- ********************************************* 


-- Database Section
-- ________________ 

create database ByLights;
use ByLights;


-- Tables Section
-- _____________ 

create table ADMIN (
     admin_id int not null auto_increment,
     username varchar(50) not null,
     name varchar(50) not null,
     surname varchar(50) not null,
     email varchar(50) not null,
     password varchar(64) not null,
     id_profile_img int,
     constraint ID_ADMIN_ID primary key (admin_id));

create table BADGE (
     badge_id int not null auto_increment,
     nameBadge varchar(50) not null,
     description varchar(50) not null,
     constraint ID_BADGE_ID primary key (badge_id));

create table PATH (
     path_id int not null auto_increment,
     path_user_id int not null,
     brightness float(30) not null,
     view_user_id int not null,
     del_admin_id int not null,
     Ana_admin_id int,
     Rec_user_id int not null,
     constraint ID_PATH_ID primary key (path_id));

create table PROFILE_IMAGE (
     id_profile_img int not null auto_increment,
     constraint ID_PROFILE_IMAGE_ID primary key (id_profile_img));

create table SESSION (
     session_id int not null auto_increment,
     session_user_id int null,
     session_admin_id int null,
     data datetime not null,
     created_at datetime not null,
     last_activity datetime not null,
     expires_at datetime not null,
     
     CONSTRAINT ID_SESSION_ID PRIMARY KEY (session_id),
     CONSTRAINT CHK_SESSION_XOR CHECK (
        (session_user_id IS NULL AND session_admin_id IS NOT NULL) OR 
        (session_user_id IS NOT NULL AND session_admin_id IS NULL)
     )
);

create table USER (
     user_id int not null auto_increment,
     username varchar(50) not null,
     name varchar(50) not null,
     surname varchar(50) not null,
     email varchar(50) not null,
     password varchar(64) not null,
     dateRegistration date not null,
     BadgesAcquired int not null,
     id_profile_img int not null,
     constraint ID_USER_ID primary key (user_id));

create table USER_BADGE (
     user_id_earner int not null,
     badge_id_acquired int not null,
     constraint ID_USER_BADGE_ID primary key (user_id_earner, badge_id_acquired),
	 constraint FK_USER foreign key (user_id_earner) references USER(user_id),
     constraint FK_BADGE foreign key (badge_id_acquired) references BADGE(badge_id)
);


-- Constraints Section
-- ___________________ 

alter table ADMIN add constraint FKadmin_img_FK
     foreign key (id_profile_img)
     references PROFILE_IMAGE (id_profile_img);

alter table PATH add constraint FKviews_FK
     foreign key (view_user_id)
     references USER (user_id);

alter table PATH add constraint FKrecords
     foreign key (Rec_user_id)
     references USER (user_id);

alter table PATH add constraint FKdeletes_FK
     foreign key (del_admin_id)
     references ADMIN (admin_id);

alter table PATH add constraint FKanalyze_FK
     foreign key (Ana_admin_id)
     references ADMIN (admin_id);

alter table USER add constraint FKuser_img_FK
     foreign key (id_profile_img)
     references PROFILE_IMAGE (id_profile_img);

alter table PATH add constraint FK_USERID
	foreign key(path_user_id)
    references USER (user_id);
    
alter table SESSION add CONSTRAINT FK_SESSION_USER 
	FOREIGN KEY (session_user_id) 
	references USER(user_id);
    
alter table SESSION add CONSTRAINT FK_SESSION_ADMIN
	FOREIGN KEY (session_admin_id)
    references ADMIN(admin_id);


-- Index Section
-- _____________ 

create unique index ID_ADMIN_IND
     on ADMIN (admin_id);

create index FKadmin_img_IND
     on ADMIN (id_profile_img);

create unique index ID_BADGE_IND
     on BADGE (badge_id);

create unique index ID_PATH_IND
     on PATH (path_id);

create index FKviews_IND
     on PATH (view_user_id);

create index FKdeletes_IND
     on PATH (del_admin_id);

create index FKanalyze_IND
     on PATH (Ana_admin_id);

create unique index ID_PROFILE_IMAGE_IND
     on PROFILE_IMAGE (id_profile_img);

create unique index ID_SESSION_IND
     on SESSION (session_id);

create index FKuser_session_IND
     on SESSION (session_user_id);

create index FKadmin_session_IND
     on SESSION (session_admin_id);

create unique index ID_USER_IND
     on USER (user_id);

create index FKuser_img_IND
     on USER (id_profile_img);

create unique index ID_USER_BADGE_IND
     on USER_BADGE (user_id_earner);

create unique index SID_USER_BADGE_IND
     on USER_BADGE (badge_id_acquired);


