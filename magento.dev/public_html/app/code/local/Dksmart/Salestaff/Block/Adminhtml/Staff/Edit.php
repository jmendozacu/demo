
<?php

class Dksmart_Salestaff_Block_Adminhtml_Staff_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'salestaff';
        $this->_controller = 'adminhtml_staff';

        $this->_updateButton('save', 'label', Mage::helper('salestaff')->__('Save Staff'));
        $this->_updateButton('delete', 'label', Mage::helper('salestaff')->__('Delete Staff'));


        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('salestaff_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'salestaff_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'salestaff_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
}