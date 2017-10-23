<?php
$installer = $this;

$installer->startSetup();

/**
 * create pdfinvoiceplus table
 */
$installer->run("
ALTER TABLE  {$this->getTable('salestaff_staff')} 
	ADD COLUMN  `avatar` varchar(255) NOT NULL default '';
");

$installer->endSetup();