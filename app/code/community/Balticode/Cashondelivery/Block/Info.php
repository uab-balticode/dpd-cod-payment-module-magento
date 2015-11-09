<?php
/**
 * 2015 UAB BaltiCode
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License available
 * through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@balticode.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to
 * newer versions in the future.
 *
 *  @author    UAB Balticode Kęstutis Kaleckas
 *  @package   Balticode_Cashondelivery
 *  @copyright Copyright (c) 2015 UAB Balticode (http://balticode.com/)
 *  @license   http://www.gnu.org/licenses/gpl-3.0.txt  GPLv3
 */

class Balticode_Cashondelivery_Block_Info extends Mage_Payment_Block_Info
{
    protected $_dataObject;
    protected $_priceModel;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('balticode/cashondelivery/info.phtml');
    }

    public function toPdf()
    {
        $this->setTemplate('balticode/cashondelivery/pdf/info.phtml');
        return $this->toHtml();
    }

    public function getRawCodFee()
    {
        if ($_dataObject = $this->_getDataObject()) {
            return $_dataObject->getCodFee();
        }
        return null;
    }

    public function getCodFeeExclTax()
    {
        if ($_dataObject = $this->_getDataObject()) {
            $extraFeeExcl = $_dataObject->getCodFee() ? $this->_getPriceModel()->formatPrice($_dataObject->getCodFee()) : null;
            return $extraFeeExcl;
        }
        return null;
    }

    public function getCodFeeInclTax()
    {
        if ($_dataObject = $this->_getDataObject()) {
            $extraFeeIncl = $_dataObject->getCodFee() ? $this->_getPriceModel()->formatPrice($_dataObject->getCodFee()+$_dataObject->getCodTaxAmount()) : null;
            return $extraFeeIncl;
        }
        return null;
    }

    protected function _getDataObject()
    {
        if (!isset($this->_dataObject)) {
            $dataObject = $this->getInfo()->getQuote();
            if (!is_object($dataObject)) {
                $dataObject = $this->getInfo()->getOrder();
            }

            $this->_dataObject = $dataObject;
        }
        return $this->_dataObject;
    }

    protected function _getPriceModel()
    {
        if (!isset($this->_priceModel)) {
            $quote      = $this->getInfo()->getQuote();
            $priceModel = null;

            if (is_object($quote)) {
                $priceModel = $quote->getStore();
            }

            if (!$priceModel) {
                $priceModel = $this->getInfo()->getOrder();
            }

            $this->_priceModel = $priceModel;
        }
        return $this->_priceModel;
    }
}
