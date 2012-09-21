CREATE TABLE `member` (
  `id` varchar(40) NOT NULL default '',
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `logins` int(10) unsigned NOT NULL,
  `last_login` datetime NOT NULL,
  PRIMARY KEY  (`id`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
