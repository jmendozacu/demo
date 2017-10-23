<?php

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * tao bang salestaff_staff
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('salestaff_staff')};

CREATE TABLE {$this->getTable('salestaff_staff')} (
  `staff_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `facebook_url` varchar(255) NOT NULL default '',
  `birthday` date NULL,
  `sex` smallint(1) NOT NULL default '1',
  `status` smallint(6) NOT NULL default '1',
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();
