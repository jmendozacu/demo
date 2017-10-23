<?php
class Websgle_Blog_IndexController extends Mage_Core_Controller_Front_Action {
    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }
    public function postAction()
    {
        $post = Mage::getModel('websgle_blog/post')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->setOrder('entity_id', 'DESC')
            ->setPageSize(100);
        $arr_posts = array();
        foreach ($post as $ob)
        {
            $arr_posts[] = $ob
                ->toArray(array());
        }
        return $this->getResponse()
            ->setHeader('Content-type','application/json')
            ->setHeader('Access-Control-Allow-Origin','*')
            ->setBody(json_encode($arr_posts));
    }
}