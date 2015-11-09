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

class Balticode_Cashondelivery_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * Config path constants
     */
    const CONFIG_XML_PATH_COD_TAX_CLASS    = 'tax/classes/Balticode_cashondelivery_tax_class';
    const CONFIG_XML_PATH_COD_INCLUDES_TAX = 'tax/calculation/Balticode_cashondelivery_includes_tax';
    const CONFIG_XML_PATH_DISPLAY_COD      = 'tax/display/Balticode_cashondelivery_fee';

    /**
     * Instance variable for lazy load.
     * @var array
     */
    protected $_codPriceIncludesTax = array();

    /**
     * Instance variable for lazy load.
     * @var array
     */
    protected $_shippingPriceDisplayType = array();

    /**
     * Holds the total block which is before the CoD total block.
     * @var string
     */
    protected $_getTotalAfterPosition;

    /**
     * Are the taxes included in the configured or calculated ( percentage ) Cash on Delivery fee?
     *
     * @param @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return int
     */
    public function codPriceIncludesTax($store = null)
    {
        $store   = Mage::app()->getStore($store);
        $storeId = $store->getId();

        if (!isset($this->_codPriceIncludesTax[$storeId])) {
            $this->_codPriceIncludesTax[$storeId] = (int)Mage::getStoreConfig(self::CONFIG_XML_PATH_COD_INCLUDES_TAX, $store);
        }
        return $this->_codPriceIncludesTax[$storeId];
    }

    /**
     * Get the configured Tax class id.
     *
     * @param @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return int
     */
    public function getCodTaxClass($store = null)
    {
        return (int)Mage::getStoreConfig(self::CONFIG_XML_PATH_COD_TAX_CLASS, $store);
    }

    public function getCodPrice($price, $includingTax = null, $shippingAddress = null, $ctc = null, $store = null)
    {
        return $price;
        $billingAddress = false;
        if ($shippingAddress && $shippingAddress->getQuote() && $shippingAddress->getQuote()->getBillingAddress()) {
            $billingAddress = $shippingAddress->getQuote()->getBillingAddress();
        }

        $calc = Mage::getSingleton('tax/calculation');
        $taxRequest = $calc->getRateRequest(
            $shippingAddress,
            $billingAddress,
            $shippingAddress->getQuote()->getCustomerTaxClassId(),
            $store
        );
        $taxRequest->setProductClassId($this->getCodTaxClass($store));

        $rate = $calc->getRate($taxRequest);
        $tax = $calc->calcTaxAmount($price, $rate, $this->codPriceIncludesTax($store), true);

        if ($this->codPriceIncludesTax($store)) {
            return $includingTax ? $price : $price - $tax;
        } else {
            return $includingTax ? $price + $tax : $price;
        }
    }

    public function getCodFeeDisplayType($store = null)
    {
        $storeId = Mage::app()->getStore($store)->getId();

        if (!isset($this->_shippingPriceDisplayType[$storeId])) {
            $this->_shippingPriceDisplayType[$storeId] = (int)Mage::getStoreConfig(self::CONFIG_XML_PATH_DISPLAY_COD, $store);
        }
        return $this->_shippingPriceDisplayType[$storeId];
    }

    public function displayCodFeeIncludingTax()
    {
        return $this->getCodFeeDisplayType() == Mage_Tax_Model_Config::DISPLAY_TYPE_INCLUDING_TAX;
    }

    public function displayCodFeeExcludingTax()
    {
        return $this->getCodFeeDisplayType() == Mage_Tax_Model_Config::DISPLAY_TYPE_EXCLUDING_TAX;
    }

    public function displayCodBothPrices()
    {
        return $this->getCodFeeDisplayType() == Mage_Tax_Model_Config::DISPLAY_TYPE_BOTH;
    }

    /**
     * Get the configured total position for the backend views.
     * If nothing is set it will place it after the subtotal.
     *
     * @ return string
     */
    public function getTotalAfterPosition()
    {
        if (!$this->_getTotalAfterPosition) {
            $config      = Mage::app()->getConfig()->getXpath('//sales/totals_sort');
            $positions   = end($config);
            $positions   = $positions->asArray();
            $codPos      = $positions['Balticode_cashondelivery'];
            $beforeTotal = 'subtotal';

            asort($positions);

            while ($val = current($positions)) {
                if ($val == $codPos) {
                    prev($positions);
                    $beforeTotal = key($positions);
                    break;
                }
                next($positions);
            }
            $this->_getTotalAfterPosition = $beforeTotal;
        }

        return $this->_getTotalAfterPosition;
    }

    public function isMe($order)
    {
        $order_payment_method = $order->getPayment()->getMethod();
        $my_payment_method = Mage::getModel('Balticode_Cashondelivery/cashondelivery')->_code;
        if ($order_payment_method == $my_payment_method) {
            return true;
        }
        return false;
    }
}
