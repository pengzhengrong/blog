create  table `think_blog` (
  `id` int(11) unsigned not null  auto_increment,
  `cat_id` int(11) unsigned not null default 0,
  `title` varchar(50) not null default '',
  `click` smallint(6) unsigned not null  default 0,
  `created` int(11) not null default 0,
  `sort` smallint(6) not null default 0,
  `status` tinyint(1) not null default 0,
  `content` text ,
  `update_time` int(11) not null default 0,
  primary key(`id`),
  key(`title`) ,
  key(`cat_id`)
)engine=myisam default charset=utf8 auto_increment=1;

create table `think_cate_blog`(
   `cat_id` int(11) unsigned not null default 0,
  `blog_id` int(11) unsigned not null default 0,
  key `cat_id`(`cat_id`),
  key `blog_id`(`blog_id`)
)engine=myisam default charset=utf8;

create table `think_category`(
  `id` int(11) unsigned not null auto_increment,
  `title` varchar(50) not null default '',
  `sort` smallint(6) not null default 0,
  `pid`  int(11) not null default 0,
  `level` smallint(2) not null default 0,
  `multi` tinyint(1) not null default 0,
  `status` tinyint(1) not null default 0,
  primary key(`id`),
  key(`title`)
)engine=myisam default charset=utf8 auto_increment=1;

-- alter table `think_category` add multi   tinyint(1) not null default 0;
-- alter table `think_category` add status   tinyint(1) not null default 0;

create table `think_attr`(
  `id` int(11) unsigned not null auto_increment,
  `title` varchar(30) not null default '',
  `color` varchar(10) not null default '',
  `sort` tinyint(1) not null default 0,
  primary key(`id`)
)engine=myisam default charset=utf8 auto_increment=1;

create table `think_blog_attr`(
  `blog_id` int(11) not null default 0,
  `attr_id` int(11) not null default 0,
  `attr_count` int(11) not null default 0,
  `status` tinyint(1) not null default 0,
    KEY `group_id` (`blog_id`),
    KEY `attr_id` (`attr_id`)
)engine=myisam default charset=utf8;
-- alter table `think_blog_attr` delete index user_id;
-- alter table `think_blog_attr` add `attr_count` int(11) not null default 0;
-- alter table `think_blog_attr` add `status` tinyint(1) not null default 0;

create table `think_user`(
  `id` int(11) not null  auto_increment,
  `username` varchar(20) not null default '',
  `password` varchar(100) not null default '',
  `login_ip` varchar(20) not null default '',
  `login_time` int(11) not null default 0,
  `lock` tinyint(1) not null default 0,
  primary key(`id`),
  key(`username`),
  UNIQUE(`username`)
)engine=myisam default charset=utf8 auto_increment=1;
insert into `think_user` values(0,'admin','123456','127.0.0.1',0,0);


create table `think_navigation`(
  `id` int(11) not null  auto_increment,
  `en_name` varchar(20) not null default '',
  `zn_name` varchar(20) not null default '',
  `leavel` tinyint(1) not null default 0,
  `pid` int(11) unsigned not null default 0,
  `url` varchar(100) not null default '',
  `color` varchar(20) not null default '',
  `sort` int(6) not null default 0,
  primary key(`id`)
)engine=myisam default charset=utf8 auto_increment=1;

insert into `think_user` values(1,'NODE','节点管理',1,1,'/Rbac/node','green',1);
insert into `think_user` values(2,'ACCESS','权限管理',1,1,'/Rbac/access','green',2);
insert into `think_user` values(3,'ROLE','角色管理',1,1,'/Rbac/role','green',3);
insert into `think_user` values(1,'USER','用户管理',1,1,'/User/index','green',4);

create table `think_blog_comment`(
  `id` int(11)  unsigned auto_increment primary key ,
  `blog_id` int(11) unsigned not null default 0,
  `username` varchar(20) not null default '',
  `content`  varchar(500) not null default '',
  `pid` int(11) unsigned not null default 0,
  `status` tinyint(1) not null default 0,
  `created` int(11) not null default 0,
  `top_num` int(6) not null default 0,
  `base_num` int(6) not null default 0,
  `extra` varchar(200) not null default '',
  key(`blog_id`)
)engine=myisam default charset=utf8 auto_increment=1;

create table `think_commenter`(
  `id` int(11) unsigned auto_increment primary key,
  `username` varchar(20) not null default '',
  `password` varchar(100) not null default '',
  `login_time` int(11) not null default 0,
  `login_ip` varchar(20) not null default '',
  `lock` tinyint(1) not null default 0,
  key(`username`),
  UNIQUE(`username`)
  )engine=myisam default charset=utf8 auto_increment=1;
-- alter table think_commenter add index unique(`username`);
create table `think_comment_count`(
  `comment_id` int(11) unsigned not null default 0,
  `comment` tinyint(1) not null default 0 comment'0 null ,1 perfect , 2 good ,3 just so so',
  `count` int(11) not null default 0,
  key(`comment_id`)
)engine=myisam default charset=utf8 ;

CREATE TABLE think_session (
       session_id varchar(255) NOT NULL,
       session_expire int(11) NOT NULL,
       session_data blob,
       UNIQUE KEY `session_id` (`session_id`)
     )engine=myisam default charset=utf8 ;
