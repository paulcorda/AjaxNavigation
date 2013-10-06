<?php
require_once 'Mage/CatalogSearch/controllers/ResultController.php';
class Paulc_AjaxNavigation_CatalogSearch_ResultController extends Mage_CatalogSearch_ResultController
{
	public function indexAction()
    {
		$_isAjax 	= $this->getRequest()->getParam('isAjax');
		$_helper 	= Mage::helper('ajaxnavigation');
        $_response 	= array();

        $query = Mage::helper('catalogsearch')->getQuery();
        /* @var $query Mage_CatalogSearch_Model_Query */

        $query->setStoreId(Mage::app()->getStore()->getId());

        if ($query->getQueryText() != '') {
            if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->setId(0)
                    ->setIsActive(1)
                    ->setIsProcessed(1);
            }
            else {
                if ($query->getId()) {
                    $query->setPopularity($query->getPopularity()+1);
                }
                else {
                    $query->setPopularity(1);
                }

                if ($query->getRedirect()){
                    $query->save();
                    $this->getResponse()->setRedirect($query->getRedirect());
                    return;
                }
                else {
                    $query->prepare();
                }
            }

            Mage::helper('catalogsearch')->checkNotes();

            $this->loadLayout();
            if($_helper->getIsActive() == 1 && $_isAjax == 1) {
	        	// Create new layered navigation
                $_view = $this->getLayout()->getBlock('catalogsearch.leftnav')->toHtml();
				
		        // Create new product list
                $_list = $this->getLayout()->getBlock('search_result_list')->toHtml();

				// Create response
                $_response['status']	= 'Success';
                $_response['view']		= $_view;
                $_response['list']		= $_list;
				
				
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($_response));
                return;
            }
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();

            if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->save();
            }
        }
        else {
            $this->_redirectReferer();
        }
    }
}
				