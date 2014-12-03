CREATE TABLE IF NOT EXISTS `addons` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(1) NOT NULL,
  `install` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `server` (
  `id` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `rcon` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `admin` int(11) NOT NULL,
  `perms` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `addons`
ADD PRIMARY KEY (`id`);

ALTER TABLE `server`
ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
ADD PRIMARY KEY (`id`);

ALTER TABLE `addons`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `server`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;