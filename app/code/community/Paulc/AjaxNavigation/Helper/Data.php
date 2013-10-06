<?php
class Paulc_AjaxNavigation_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getIsActive() {
        return (bool) Mage::getStoreConfig('paulc_ajaxnavigation/general/active');
    }
}