<?php
require_once 'Mage/Catalog/controllers/CategoryController.php';
class Paulc_AjaxNavigation_Catalog_CategoryController extends Mage_Catalog_CategoryController
{
	public function viewAction() {
		if(Mage::helper('ajaxnavigation')->getIsActive() && Mage::app()->getRequest()->getPost('isAjax')) {
			if ($category = $this->_initCatagory()) {
				$_response 	= array();
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

				// apply custom layout (page) template once the blocks are generated
				if ($settings->getPageLayout()) {
					$this->getLayout()->helper('page/layout')->applyTemplate($settings->getPageLayout());
				}

	        	$_response['left'] = $this->getLayout()->getBlock('catalog.leftnav')->toHtml();
		        $_response['list'] = $this->getLayout()->getBlock('product_list')->toHtml();

				$this->getResponse()->setHeader('Content-type', 'application/json');
	        	$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($_response));
		    }
		    elseif (!$this->getResponse()->isRedirect()) {
		        $this->_forward('noRoute');
		    }
		}
		else {
			parent::viewAction();
		}
	}
}