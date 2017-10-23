<?php

class Dksmart_Salestaff_Block_Grid extends Mage_Core_Block_Template {
    //construct function
    public function __construct() {
        parent::__construct();
        $collection = $this->getStaffCollection();
        $this->setCollection($collection);
    }
    //prepare layout
    public function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'salestaff.pager')->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        return $this;
    }

    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

    public function getStaffCollection() {
        $collection = Mage::getModel('salestaff/staff')->getCollection();
        return $collection;
    }

    public function getSexLabel($staff) {
        if ($staff->getId()) {
            if ($staff->getSex() == 1)
                return Mage::helper('salestaff')->__('Male');
        }
        return Mage::helper('salestaff')->__('Female');
    }

    public function getStatusLabel($staff) {
        if ($staff->getId()) {
            if ($staff->getStatus() == 1)
                return Mage::helper('salestaff')->__('Enabled');
        }
        return Mage::helper('salestaff')->__('Disabled');
    }

}
