<?php
class Dksmart_Salestaff_Block_Adminhtml_Staff_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Basetut_Salestaff_Block_Adminhtml_Salestaff_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array(
                'id' => $this->getRequest()->getParam('id'),
            )),
            'method' => 'post',
        ));
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getSalestaffData()) {
            $data = Mage::getSingleton('adminhtml/session')->getSalestaffData();
            Mage::getSingleton('adminhtml/session')->setSalestaffData(null);
        } elseif (Mage::registry('salestaff_data')) {
            $data = Mage::registry('salestaff_data')->getData();
        }
        $fieldset = $form->addFieldset('salestaff_form', array(
            'legend'=>Mage::helper('salestaff')->__('Staff information')
        ));

        /*Edit truong kieu text*/
        $fieldset->addField('name', 'text', array(
            'label'        => Mage::helper('salestaff')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'name',
        ));

        $fieldset->addField('email', 'text', array(
            'label'        => Mage::helper('salestaff')->__('Email'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'email',
        ));
        $fieldset->addField('facebook_url', 'text', array(
            'label'        => Mage::helper('salestaff')->__('Facebook'),
            'name'        => 'facebook_url',
        ));
        $fieldset->addField('avatar', 'file', array(
            'name' => 'avatar',
            'label'     => Mage::helper('salestaff')->__('Upload Images'),
            'class'     => 'required-entry',
            'required'  => true,
        ));
        /*Edit truong kieu date*/
        $fieldset->addField('birthday', 'date', array(
            'label'        => Mage::helper('salestaff')->__('Birthday'),
            'name'        => 'birthday',
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            'image'  => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'/adminhtml/default/default/images/grid-cal.gif',
        ));
        /*Edit truong kieu select*/
        $fieldset->addField('sex', 'select', array(
            'label' => Mage::helper('salestaff')->__('Sex'),
            'name' => 'sex',
            'onclick' => "",
            'onchange' => "",
            'values' => array('-1'=>'Please Select..','1' => 'Male','2' => 'Female'),
            'disabled' => false,
            'readonly' => false,
            'tabindex' => 1
        ));
        /*View truong kieu note*/
        if($this->getRequest()->getParam('id')){
            $fieldset->addField('items_qty', 'note', array(
                'label'	=> Mage::helper('salestaff')->__('Item qty'),
                'name'	=> 'items_qty',
                'text'	=> $data['items_qty']
            ));
        }

        $form->setValues($data);
        return parent::_prepareForm();
    }
}