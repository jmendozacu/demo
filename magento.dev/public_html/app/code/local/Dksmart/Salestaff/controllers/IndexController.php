<?php
class Dksmart_Salestaff_IndexController extends Mage_Core_Controller_Front_Action {
    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }
    public function staffAction()
    {
        $staff = Mage::getModel('salestaff/staff')
            ->getCollection()
//            ->addAttributeToSelect('*')
            ->setOrder('staff_id', 'DESC')
            ->setPageSize(100);
        $arr_staffs = array();
        foreach ($staff as $ob)
        {
            $arr_staffs[] = $ob
                ->toArray(array());
        }

        return $this->getResponse()
            ->setHeader('Content-type','application/json')
            ->setHeader('Access-Control-Allow-Origin','*')
            ->setBody(json_encode($arr_staffs));
    }
    
    public function customerAction()
    {
        $customer = Mage::getModel('customer/customer')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->setOrder('entity_id', 'DESC')
            ->setPageSize(100);
        $arr_customers = array();
        foreach ($customer as $ob)
        {
            $groupId = $ob->getGroupId();
            $defaultBilling = $ob->getDefaultBilling();
            $defaultBillingAddress = Mage::getModel('customer/address')->load($defaultBilling);
            $defaultShipping = $ob->getDefaultShipping();
            $defaultShippingAddress = Mage::getModel('customer/address')->load($defaultShipping); //address as object

//returns group code (general, retailer, etc)
            $arr_customers[] = array(
                'id' => $ob->getEntityId(),
                'name' => $ob->getName(),
                'email' => $ob->getEmail(),
                'groupCode' => Mage::getModel('customer/group')->load($groupId)->getCustomerGroupCode(),
                'defaultBillingAddress' => $defaultBillingAddress->getData(),//return as array
                'defaultShippingAddress' => $defaultShippingAddress->getData(), //return as array

        );
        }

        $data_customers = array(
            "data" => $arr_customers,
        );
        return $this->getResponse()
            ->setHeader('Content-type','application/json')
            ->setHeader('Access-Control-Allow-Origin','*')
            ->setBody(json_encode($data_customers));
    }
    public function productAction()
    {
        $product = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->setOrder('entity_id', 'ASC')
            ->setPageSize(100);
        $arr_products = array();

        foreach ($product as $ob)
        {

            $arr_products[] = array(
                'id' => $ob->getID(),
                'name' => $ob->getName(),
                'sku' => $ob->getSKU(),
                'description' => $ob->getDescription(),
                'price' => $ob->getPrice(),
                'size' => $ob->getResource()->getAttribute('size')->getFrontend()->getValue($ob),
                'color' => $ob->getResource()->getAttribute('color')->getFrontend()->getValue($ob),
                'image' => Mage::getModel('catalog/product_media_config')
                    ->getMediaUrl( $ob->getImage() )
                );

        }
        $data_products = array(
            "data" => $arr_products,
        );
        return $this->getResponse()
            ->setHeader('Content-type','application/json')
            ->setHeader('Access-Control-Allow-Origin','*')
            ->setBody(json_encode($data_products));
    }
    public function paymentAction()
    {
        $allAvailablePaymentMethods = Mage::getModel('payment/config')->getActiveMethods();
//            ->addAttributeToSelect('*')
        $arr_payment = array();
        foreach ($allAvailablePaymentMethods as $ob)
        {
            $arr_payment[] = $ob
                ->toArray(array());
        }

        return $this->getResponse()
            ->setHeader('Content-type','application/json')
            ->setHeader('Access-Control-Allow-Origin','*')
            ->setBody(json_encode($arr_payment));
    }

}