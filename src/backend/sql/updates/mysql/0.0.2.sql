ALTER TABLE `#__aftms_courses` CHANGE `created` `created_time` DATETIME;
ALTER TABLE `#__aftms_courses` CHANGE `modified` `modified_time` DATETIME;

ALTER TABLE `#__aftms_courses` ADD COLUMN `campusid` INT(11) NOT NULL;

ALTER TABLE `#__aftms_course_groups` CHANGE `created` `created_time` DATETIME;
ALTER TABLE `#__aftms_course_groups` CHANGE `modified` `modified_time` DATETIME;

ALTER TABLE `#__aftms_course_groups` ADD `simple_lvl` TINYINT(3) NOT NULL DEFAULT '1' AFTER `typeid` COMMENT '1=beginner; 2=intermediate; 3=advanced';

ALTER TABLE `#__aftms_classrooms` CHANGE `created` `created_time` DATETIME;
ALTER TABLE `#__aftms_classrooms` CHANGE `modified` `modified_time` DATETIME;

ALTER TABLE `#__aftms_campuses` CHANGE `created` `created_time` DATETIME;
ALTER TABLE `#__aftms_campuses` CHANGE `modified` `modified_time` DATETIME;

