<?php
class Dksmart_Salestaff_Block_Adminhtml_Staff_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
    {

        parent::__construct();
        $this->setId('staffGrid');
        $this->setDefaultSort('staff_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Basetut_Salestaff_Block_Adminhtml_Salestaff_Grid
     */
    protected function _prepareCollection()
    {

		$collection = Mage::getModel('salestaff/staff')->getCollection();
		$this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Basetut_Salestaff_Block_Adminhtml_Salestaff_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('staff_id', array(
            'header'    => Mage::helper('salestaff')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'staff_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('salestaff')->__('Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));
		
		$this->addColumn('email', array(
			'header'    => Mage::helper('salestaff')->__('Email'),
			'align'     =>'left',
			'index'     => 'email',
			'renderer'	=>	'salestaff/adminhtml_staff_renderer_email'
		));
		$this->addColumn('facebook_url', array(
			'header'    => Mage::helper('salestaff')->__('Facebook Url'),
			'align'     =>'left',
			'index'     => 'facebook_url',
			'renderer'	=>	'salestaff/adminhtml_staff_renderer_link'
		));

		$this->addColumn('avatar', array(
			'header'    => Mage::helper('salestaff')->__('Avatar'),
			'align'     =>'left',
			'index'     => 'avatar',
			'sortable'      => false,
			'filter'      => false,
			'renderer'	=>	'salestaff/adminhtml_staff_renderer_avatar'
		));

        $this->addColumn('birthday', array(
            'header'    => Mage::helper('salestaff')->__('Birthday'),
            'width'     => '150px',
            'index'     => 'birthday',
			'type'		=> 'date'
        ));
        
        $this->addColumn('sex', array(
            'header'    => Mage::helper('salestaff')->__('Sex'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'sex',
            'type'        => 'options',
            'options'     => array(
                1 => 'Male',
                2 => 'Female',
            ),
        ));
		
		$this->addColumn('items_qty', array(
            'header'    => Mage::helper('salestaff')->__('Items Qty'),
            'align'     =>'left',
            'width'     => '100px',
            'index'     => 'items_qty',
			'type'		=> 'number'
        ));
		$currencyCode = Mage::app()->getStore()->getBaseCurrency()->getCode();
		$this->addColumn('totals_sales', array(
            'header'    => Mage::helper('salestaff')->__('Totals Sales'),
            'align'     =>'right',
            'width'     => '100px',
            'index'     => 'totals_sales',
			'type'		=> 'price',
			'currency_code'	=> $currencyCode
        ));
		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn('store_id', array(
				'header'    => Mage::helper('salestaff')->__('Store view'),
				'align'     =>'left',
				'index'     =>'store_id',
				'type'      =>'store',
				'width'     => '150px',
				'store_all' =>true,
				'store_view'=>true,
				'sortable'      => false,
				'filter_condition_callback'
					=> array($this, '_filterStoreCondition'),
		  ));
		}
        $this->addColumn('status', array(
            'header'    => Mage::helper('salestaff')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'        => 'options',
            'options'     => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));
		
$this->addExportType('*/*/exportCsv', Mage::helper('salestaff')->__('CSV'));
$this->addExportType('*/*/exportXml', Mage::helper('salestaff')->__('XML'));

        return parent::_prepareColumns();
    }
	
	/**
     * prepare mass action for this grid
     *
     * @return Basetut_Salestaff_Block_Adminhtml_Staff_Grid
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('staff_id');
        $this->getMassactionBlock()->setFormFieldName('staff');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('salestaff')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('salestaff')->__('Are you sure?')
        ));
		/*mass change status*/
		$statuses = array(
			1    => Mage::helper('salestaff')->__('Enabled'),
			2   => Mage::helper('salestaff')->__('Disabled')
		);
		array_unshift($statuses, array('label' => '', 'value' => ''));
		$this->getMassactionBlock()->addItem('status', array(
			'label' => Mage::helper('salestaff')->__('Change status'),
			'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
			'additional' => array(
				'visibility' => array(
					'name' => 'status',
					'type' => 'select',
					'class' => 'required-entry',
					'label' => Mage::helper('salestaff')->__('Status'),
					'values' => $statuses
			))
		));
		/*mass change sex*/
		$sexs = array(
			1    => Mage::helper('salestaff')->__('Male'),
			2   => Mage::helper('salestaff')->__('Female')
		);
		array_unshift($sexs, array('label' => '', 'value' => ''));
		$this->getMassactionBlock()->addItem('sex', array(
			'label' => Mage::helper('salestaff')->__('Change sex'),
			'url' => $this->getUrl('*/*/massSex', array('_current' => true)),
			'additional' => array(
				'visibility' => array(
					'name' => 'sex',
					'type' => 'select',
					'class' => 'required-entry',
					'label' => Mage::helper('salestaff')->__('Sex'),
					'values' => $sexs
				))
		));
        return $this;
    }
	
	protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addFieldToFilter('store_id', $value);
    }
	/**
	 * get url for each row in grid
	 *
	 * @return string
	 */
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}