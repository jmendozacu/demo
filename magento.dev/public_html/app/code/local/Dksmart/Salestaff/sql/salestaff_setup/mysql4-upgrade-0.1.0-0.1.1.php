<?php
$installer = $this;

$installer->startSetup();

/**
 * create pdfinvoiceplus table
 */
$installer->run("
ALTER TABLE  {$this->getTable('salestaff_staff')} 
    ADD COLUMN  `totals_sales` decimal(11,2) NOT NULL default '0',
	ADD COLUMN	`items_qty` int(11) NOT NULL default '0';
");

$installer->endSetup();