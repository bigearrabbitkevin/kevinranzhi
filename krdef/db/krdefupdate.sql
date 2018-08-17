-- first time install
INSERT INTO `sys_entry` (`name`, `abbr`, `code`, `buildin`, `integration`, `open`, `key`, `ip`, `logo`, `login`, `control`, `size`, `position`, `visible`, `order`) VALUES
('TOOLS', 'KR', 'krdef', 1, 1, 'iframe', 'gscw6szg9rcue3r9ulm379dnm5d8y82c', '*', 'theme/default/images/ips/app-krdef.png', '../krdef', 'simple', 'max', 'default', 1, 200);
INSERT INTO `sys_entry` (`name`, `abbr`, `code`, `buildin`, `integration`, `open`, `key`, `ip`, `logo`, `login`, `control`, `size`, `position`, `visible`, `order`) VALUES
('BOOK', 'BK', 'krbook', 1, 1, 'iframe', 's20friv4uzu8gx6zqwrmx5zuizfbvi8f', '*', 'theme/default/images/ips/app-krbook.png', '../krbook', 'simple', 'max', 'default', 1, 210);
-- run this after uninstall
DELETE FROM `sys_entry` WHERE `code` = 'krdef';

DELETE FROM `sys_entry` WHERE `code` = 'krbook';
