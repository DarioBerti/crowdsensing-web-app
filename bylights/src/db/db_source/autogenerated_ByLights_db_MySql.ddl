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

create database schema;
use schema;


-- Tables Section
-- _____________ 

create table ADMIN (
     admin_id -- Index attribute not implemented -- not null,
     username varchar(1) not null,
     name varchar(1) not null,
     surname varchar(1) not null,
     email varchar(1) not null,
     password varchar(1) not null,
     id_profile_img -- Index attribute not implemented --,
     constraint ID_ADMIN_ID primary key (admin_id));

create table BADGE (
     badgeID -- Index attribute not implemented -- not null,
     nameBadge varchar(1) not null,
     description varchar(1) not null,
     constraint ID_BADGE_ID primary key (badgeID));

create table PATH (
     pathId -- Index attribute not implemented -- not null,
     Rec_user_id -- Index attribute not implemented -- not null,
     userId varchar(1) not null,
     brightness float(1) not null,
     user_id -- Index attribute not implemented --,
     admin_id -- Index attribute not implemented --,
     Ana_admin_id -- Index attribute not implemented --,
     constraint SID_PATH_ID unique (Rec_user_id, pathId),
     constraint ID_PATH_ID primary key (pathId));

create table PROFILE_IMAGE (
     id_profile_img -- Index attribute not implemented -- not null,
     constraint ID_PROFILE_IMAGE_ID primary key (id_profile_img));

create table SESSION (
     session_id -- Index attribute not implemented -- not null,
     session_user_id -- Index attribute not implemented -- not null,
     session_admin_id -- Index attribute not implemented -- not null,
     data varchar(1) not null,
     created_at date not null,
     last_activity date not null,
     expires_at date not null,
     user_id -- Index attribute not implemented --,
     admin_id -- Index attribute not implemented --,
     constraint ID_SESSION_ID primary key (session_id));

create table USER (
     user_id -- Index attribute not implemented -- not null,
     username varchar(1) not null,
     name varchar(1) not null,
     surname varchar(1) not null,
     email varchar(1) not null,
     password varchar(1) not null,
     dateRegistration date not null,
     BadgesAcquired int not null,
     id_profile_img -- Index attribute not implemented --,
     constraint ID_USER_ID primary key (user_id));

create table USER_BADGE (
     user_earner -- Index attribute not implemented -- not null,
     badge_acquired -- Index attribute not implemented -- not null,
     user_id -- Index attribute not implemented -- not null,
     badgeID -- Index attribute not implemented -- not null,
     constraint ID_USER_BADGE_ID primary key (user_earner),
     constraint SID_USER_BADGE_ID unique (badge_acquired));


-- Constraints Section
-- ___________________ 

alter table ADMIN add constraint FKadmin_img_FK
     foreign key (id_profile_img)
     references PROFILE_IMAGE (id_profile_img);

alter table PATH add constraint FKviews_FK
     foreign key (user_id)
     references USER (user_id);

alter table PATH add constraint FKrecords
     foreign key (Rec_user_id)
     references USER (user_id);

alter table PATH add constraint FKdeletes_FK
     foreign key (admin_id)
     references ADMIN (admin_id);

alter table PATH add constraint FKanalyze_FK
     foreign key (Ana_admin_id)
     references ADMIN (admin_id);

alter table SESSION add constraint FKuser_session_FK
     foreign key (user_id)
     references USER (user_id);

alter table SESSION add constraint FKadmin_session_FK
     foreign key (admin_id)
     references ADMIN (admin_id);

alter table USER add constraint FKuser_img_FK
     foreign key (id_profile_img)
     references PROFILE_IMAGE (id_profile_img);

alter table USER_BADGE add constraint FKuser_earns_FK
     foreign key (user_id)
     references USER (user_id);

alter table USER_BADGE add constraint FKbadge_aquired_FK
     foreign key (badgeID)
     references BADGE (badgeID);


-- Index Section
-- _____________ 

create unique index ID_ADMIN_IND
     on ADMIN (admin_id);

create index FKadmin_img_IND
     on ADMIN (id_profile_img);

create unique index ID_BADGE_IND
     on BADGE (badgeID);

create unique index SID_PATH_IND
     on PATH (Rec_user_id, pathId);

create unique index ID_PATH_IND
     on PATH (pathId);

create index FKviews_IND
     on PATH (user_id);

create index FKdeletes_IND
     on PATH (admin_id);

create index FKanalyze_IND
     on PATH (Ana_admin_id);

create unique index ID_PROFILE_IMAGE_IND
     on PROFILE_IMAGE (id_profile_img);

create unique index ID_SESSION_IND
     on SESSION (session_id);

create index FKuser_session_IND
     on SESSION (user_id);

create index FKadmin_session_IND
     on SESSION (admin_id);

create unique index ID_USER_IND
     on USER (user_id);

create index FKuser_img_IND
     on USER (id_profile_img);

create unique index ID_USER_BADGE_IND
     on USER_BADGE (user_earner);

create unique index SID_USER_BADGE_IND
     on USER_BADGE (badge_acquired);

create index FKuser_earns_IND
     on USER_BADGE (user_id);

create index FKbadge_aquired_IND
     on USER_BADGE (badgeID);

