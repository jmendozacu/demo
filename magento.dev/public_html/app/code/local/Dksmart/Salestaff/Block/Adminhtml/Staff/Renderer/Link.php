<?php
class Dksmart_Salestaff_Block_Adminhtml_Staff_Renderer_Link
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /* Render Grid Column*/
    public function render(Varien_Object $row)
    {
        if($row->getFacebookUrl())
            return sprintf('
				<a href="%s">%s</a>',
                $row->getFacebookUrl(),
                $row->getFacebookUrl()
            );
    }
}