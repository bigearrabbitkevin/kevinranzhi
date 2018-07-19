-- first time install
INSERT INTO `sys_entry` (`name`, `abbr`, `code`, `buildin`, `integration`, `open`, `key`, `ip`, `logo`, `login`, `control`, `size`, `position`, `visible`, `order`) VALUES
('KEVIN', 'KV', 'kvr', 1, 1, 'iframe', '11338326597d14a1f7c745853f4d50a8', '*', 'theme/default/images/ips/app-kvr.png', '/kvr', 'simple', 'max', 'default', 1, 190);
-- run this after uninstall
DELETE FROM `sys_entry` WHERE `code` = 'kvr';

-- 2018-7-12 add column group into zt_dept
ALTER TABLE `zt_dept` ADD `group` char(255) NOT NULL DEFAULT '';
