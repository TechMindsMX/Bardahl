ALTER TABLE `#__visforms` ADD COLUMN `emailrecipientincfilepath` tinyint(4) AFTER `emailreceiptincfield`;
ALTER TABLE `#__visforms` ADD COLUMN `access` int(11) NOT NULL default 0 AFTER `autopublish`;
ALTER TABLE `#__visforms` ADD COLUMN `required` varchar(10) NOT NULL default 'top' AFTER `language`;
ALTER TABLE `#__visforms` ADD COLUMN `exportsettings` text AFTER `required`;
ALTER TABLE `#__visforms` ADD COLUMN `emailfromname` text AFTER `emailfrom`;
ALTER TABLE `#__visforms` MODIFY COLUMN `hits` int(11) NOT NULL default 0;
ALTER TABLE `#__visforms` MODIFY COLUMN `created` datetime NOT NULL default '0000-00-00 00:00:00';
ALTER TABLE `#__visforms` MODIFY COLUMN `created_by` int(11) NOT NULL default 0;
ALTER TABLE `#__visforms` MODIFY COLUMN `checked_out` int(10) NOT NULL default 0;
ALTER TABLE `#__visfields` ADD COLUMN `asset_id` int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `#__visfields` ADD COLUMN `created` datetime NOT NULL default '0000-00-00 00:00:00' AFTER `checked_out_time`;
ALTER TABLE `#__visfields` ADD COLUMN `created_by` int(11) NOT NULL default 0 AFTER `created`;
ALTER TABLE `#__visfields` ADD COLUMN `includefieldonexport` tinyint(4) not null DEFAULT 1 AFTER `fillwith`;
ALTER TABLE `#__visfields` MODIFY COLUMN `checked_out` int(10) NOT NULL default 0;