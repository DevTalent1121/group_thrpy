CREATE TABLE IF NOT EXISTS `work` (
  `id` varchar(3) CHARACTER SET utf8 NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `work` (`id`, `name`) VALUES
('CCC', 'Corpus Christi Catholic work'),
('HSE', 'Holy Spirit Episcopal work'),
('OLG', 'Our Lady of Guadalupe Catholic work'),
('PLS', 'Pilgrim Lutheran work'),
('SAG', 'Saint Augustine Catholic work'),
('SAN', 'Saint Anne Catholic work'),
('SCC', 'Saint Christopher Catholic work'),
('TWC', 'The Woodlands Christian Academy');