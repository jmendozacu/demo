<?php
class Dksmart_Salestaff_Model_Mysql4_Staff_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('salestaff/staff');
    }
}