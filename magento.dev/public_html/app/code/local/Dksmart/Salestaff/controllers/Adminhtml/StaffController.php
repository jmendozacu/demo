<?php

class Dksmart_Salestaff_Adminhtml_StaffController extends Mage_Adminhtml_Controller_Action
{
    /**
     * index action
     */
    public function indexAction()
    {
        $this->loadLayout()
            ->renderLayout();
    }
    /**
     * export grid staff to CSV type
     */
    public function exportCsvAction() {
        $fileName = 'salestaff.csv';
        $content = $this->getLayout()
            ->createBlock('salestaff/adminhtml_staff_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export grid staff to XML type
     */
    public function exportXmlAction() {
        $fileName = 'salestaff.xml';
        $content = $this->getLayout()
            ->createBlock('salestaff/adminhtml_staff_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * mass delete staff(s) action
     */
    public function massDeleteAction() {
        $staffIds = $this->getRequest()->getParam('staff');
        if (!is_array($staffIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select staff(s)'));
        } else {
            try {
                foreach ($staffIds as $staffId) {
                    $staff = Mage::getModel('salestaff/staff')->load($staffId);
                    $staff->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($staffIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass change status for staff(s) action
     */
    public function massStatusAction() {
        $staffIds = $this->getRequest()->getParam('staff');
        if (!is_array($staffIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select staff(s)'));
        } else {
            try {
                foreach ($staffIds as $staffId) {
                    Mage::getSingleton('salestaff/staff')
                        ->load($staffId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($staffIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass change status for staff(s) action
     */
    public function massSexAction() {
        $staffIds = $this->getRequest()->getParam('staff');
        if (!is_array($staffIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select staff(s)'));
        } else {
            try {
                foreach ($staffIds as $staffId) {
                    Mage::getSingleton('salestaff/staff')
                        ->load($staffId)
                        ->setSex($this->getRequest()->getParam('sex'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($staffIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    /* new action */

    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * edit action
     */
    public function editAction(){
        $salestaffId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('salestaff/staff')->load($salestaffId);

        if ($model->getId() || $salestaffId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('salestaff_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('salestaff/salestaff');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Staff Manager'),
                Mage::helper('adminhtml')->__('Staff Manager')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('salestaff/adminhtml_staff_edit'))
                ->_addLeft($this->getLayout()->createBlock('salestaff/adminhtml_staff_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('salestaff')->__('Staff does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    /*
* save staff
*/
    public function saveAction(){
        $data = $this->getRequest()->getPost();
        if ($data) {
            if ($_FILES['avatar']['name'] != '') {
                $path = Mage::getBaseDir('media') . DS . 'salestaff';
                if (!file_exists($path)) {
                    die('123');
                    mkdir($path, 0777, true);
                }
                $data['avatar'] = $this->uploadImgAction($_FILES['avatar']['name'], 'avatar', $path);
            }
            try {
                $model = Mage::getModel('salestaff/staff');
                $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('salestaff')->__('Staff was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
    }

    public function uploadImgAction($files_name, $prefix_name, $path)
    {
        try {
            $fileName = $files_name;
            $fileExt = strtolower(substr(strrchr($fileName, "."), 1));
            $fileNamewoe = uniqid($prefix_name);
            $fileName = str_replace(' ', '', $fileNamewoe) . '.' . $fileExt;
            $uploader = new Varien_File_Uploader($prefix_name);
            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            $uploader->save($path, $fileName);
            return $fileName;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('salestaff/staff');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Staff was successfully deleted')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
}