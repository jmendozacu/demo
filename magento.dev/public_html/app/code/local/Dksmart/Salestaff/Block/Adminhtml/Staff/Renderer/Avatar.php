<?php
class Dksmart_Salestaff_Block_Adminhtml_Staff_Renderer_Avatar
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /* Render Grid Column*/
    public function render(Varien_Object $row)
    {
        if($row->getAvatar())
            return sprintf('
				<a href="%s">%s</a>',
                Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'/salestaff/'.$row->getAvatar(),
                '<img width="150" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'/salestaff/'.$row->getAvatar().'" />'
            );
    }
}