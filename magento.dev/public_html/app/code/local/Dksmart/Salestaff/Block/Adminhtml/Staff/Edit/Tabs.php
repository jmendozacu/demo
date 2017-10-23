<?php
class Dksmart_Salestaff_Block_Adminhtml_Staff_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('salestaff_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('salestaff')->__('Staff Information'));
    }

    /**
     * prepare before render block to html
     *
     * @return Basetut_Salestaff_Block_Adminhtml_Salestaff_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('salestaff')->__('Staff Information'),
            'title'     => Mage::helper('salestaff')->__('Staff Information'),
            'content'   => $this->getLayout()
                ->createBlock('salestaff/adminhtml_staff_edit_tab_form')
                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}