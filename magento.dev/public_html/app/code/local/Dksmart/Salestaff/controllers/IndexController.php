<?php
class Dksmart_Salestaff_IndexController extends Mage_Core_Controller_Front_Action {
    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }
    public function orderAction(){
        $productids = array(924, 921); //g
        $buyInfo = array( 'qty' => 1);
        $id=$this->getRequest()->getParam("id"); // get Customer Id
        $storeId = 1;
//        var_dump(;die();

        $customer = Mage::getModel('customer/customer')->load($id);
        $defaultBilling = $customer->getDefaultBilling();
        $defaultBillingAddress = Mage::getModel('customer/address')->load($defaultBilling)->getData();
//        var_dump($defaultBillingAddress);die();
        $defaultShipping = $customer->getDefaultShipping();
        $defaultShippingAddress = Mage::getModel('customer/address')->load($defaultShipping)->getData();
//        var_dump($defaultShippingAddress); die();

        $websiteId = $customer->getWebsiteId();
        $quote = Mage::getModel('sales/quote')->setStoreId($storeId);
        $quote = Mage::getModel('sales/quote')->setWebsiteId($websiteId);

// Assign Customer To Sales Order Quote
        $quote->assignCustomer($customer);

// Configure Notification
//        $quote->setSendCconfirmation(1);
        foreach ($productids as $id)
        {
            $product = Mage::getModel('catalog/product')->load($id);
            $quote->addProduct($product, new Varien_Object($buyInfo));
        }

// Set Sales Order Billing Address
        $billingAddress = $quote->getBillingAddress()->addData($defaultBillingAddress);
//        var_dump($billingAddress); die();
// Set Sales Order Shipping Address
        $shippingAddress = $quote->getShippingAddress()->addData($defaultShippingAddress);
//        var_dump($shippingAddress); die();

//        if ($shipprice == 0) {
//            $shipmethod = 'freeshipping_freeshipping';
//        }

// Collect Rates and Set Shipping & Payment Method
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate')
            ->setPaymentMethod('checkmo');
// Set Sales Order Payment
        $quote->getPayment()->importData(array('method' => 'checkmo'));
//        var_dump($quote);die();

// Collect Totals & Save Quote
        $quote->collectTotals()->save();

        try {
            // Create Order From Quote
            $service = Mage::getModel('sales/service_quote', $quote);
            $service->submitAll();
            $increment_id = $service->getOrder()->getRealOrderId();
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
        catch (Mage_Core_Exception $e) {
            echo $e->getMessage();
        }



// Resource Clean-Up
        $quote = $customer = $service = null;

// Finished
        return $increment_id;

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