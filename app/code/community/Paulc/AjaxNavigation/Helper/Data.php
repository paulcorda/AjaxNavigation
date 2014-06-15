<?php
class Paulc_AjaxNavigation_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getIsActive() {
        return Mage::getStoreConfigFlag('paulc_ajaxnavigation/general/active');
    }
}