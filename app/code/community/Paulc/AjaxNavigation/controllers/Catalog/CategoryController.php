<?php
require_once 'Mage/Catalog/controllers/CategoryController.php';
class Paulc_AjaxNavigation_Catalog_CategoryController extends Mage_Catalog_CategoryController
{	
	public function viewAction() {
		$_isAjax 	= $this->getRequest()->getParam('isAjax');
		$_helper 	= Mage::helper('ajaxnavigation');
		$_response 	= array();

		if ($category = $this->_initCatagory()) {
			$design = Mage::getSingleton('catalog/design');
			$settings = $design->getDesignSettings($category);
	 
			// apply custom design
			if ($settings->getCustomDesign()) {
				$design->applyCustomDesign($settings->getCustomDesign());
			}
	 
	        Mage::getSingleton('catalog/session')->setLastViewedCategoryId($category->getId());
	 
	        $update = $this->getLayout()->getUpdate();
	        $update->addHandle('default');
	 
	        if (!$category->hasChildren()) {
	            $update->addHandle('catalog_category_layered_nochildren');
	        }
	 
	        $this->addActionLayoutHandles();
	        $update->addHandle($category->getLayoutUpdateHandle());
	        $update->addHandle('CATEGORY_' . $category->getId());
	        $this->loadLayoutUpdates();
	 
	        // apply custom layout update once layout is loaded
	        if ($layoutUpdates = $settings->getLayoutUpdates()) {
	            if (is_array($layoutUpdates)) {
	                foreach($layoutUpdates as $layoutUpdate) {
	                    $update->addUpdate($layoutUpdate);
	                }
	            }
	        }
	 
	        $this->generateLayoutXml()->generateLayoutBlocks();
	        if($_helper->getIsActive() == 1 && $_isAjax == 1) {
	        	// Create new layered navigation
	        	$_view = $this->getLayout()->getBlock('catalog.leftnav')->toHtml();
				
		        // Create new product list
		        $_list = $this->getLayout()->getBlock('product_list')->toHtml();
					
				// Create response
		        $_response['view']		= $_view;
		        $_response['list'] 		= $_list;
				
	        	$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($_response));
	        	return;
	        }
	        else {
	             // apply custom layout (page) template once the blocks are generated
	             if ($settings->getPageLayout()) {
	                 $this->getLayout()->helper('page/layout')->applyTemplate($settings->getPageLayout());
	             }
	              
	             if ($root = $this->getLayout()->getBlock('root')) {
	                 $root->addBodyClass('categorypath-' . $category->getUrlPath())
	                 	  ->addBodyClass('category-' . $category->getUrlKey());
	             }
	              
	             $this->_initLayoutMessages('catalog/session');
	             $this->_initLayoutMessages('checkout/session');
	             $this->renderLayout();
	         }
	    }
	    elseif (!$this->getResponse()->isRedirect()) {
	    	if($_helper->getIsActive() == 1 && $_isAjax == 1) {
	    		$response['status'] = 'Error';
	    	}

	        $this->_forward('noRoute');
	    }
	}
}
				