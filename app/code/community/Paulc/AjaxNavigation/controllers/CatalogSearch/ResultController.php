<?php
require_once 'Mage/CatalogSearch/controllers/ResultController.php';
class Paulc_AjaxNavigation_CatalogSearch_ResultController extends Mage_CatalogSearch_ResultController
{
	public function indexAction() {
		if(Mage::helper('ajaxnavigation')->getIsActive() && Mage::app()->getRequest()->getPost('isAjax')) {
			$_response = array();
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
	        	$_response['left'] = $this->getLayout()->getBlock('catalogsearch.leftnav')->toHtml();
		        $_response['list'] = $this->getLayout()->getBlock('search_result_list')->toHtml();

				$this->getResponse()->setHeader('Content-type', 'application/json');
	        	$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($_response));

	            if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
	                $query->save();
	            }
	        }
	        else {
	            $this->_redirectReferer();
	        }
		}
		else {
			parent::indexAction();
		}
	}
}