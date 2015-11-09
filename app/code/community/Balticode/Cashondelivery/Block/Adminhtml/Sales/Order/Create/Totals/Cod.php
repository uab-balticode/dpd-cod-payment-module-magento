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

/**
 * COD fee Total Row Renderer
 * 
 */
class Balticode_Cashondelivery_Block_Adminhtml_Sales_Order_Create_Totals_Cod extends Mage_Adminhtml_Block_Sales_Order_Create_Totals_Default
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'balticode/cashondelivery/sales/order/create/totals/cod.phtml';

    /**
     * Variable to lazy load the helper.
     *
     * @var Balticode_CashOnDelivery_Helper_Data
     */
    protected $_helper;

    /**
     * Get the helper object.
     *
     * @return Balticode_CashOnDelivery_Helper_Data
     */
    protected function _getHelper()
    {
        if (!$this->_helper) {
            $this->_helper = Mage::helper('Balticode_Cashondelivery/data');
        }
        return $this->_helper;
    }

    /**
     * Check if we need to display the CoD fee including and excluding the tax.
     *
     * @return bool
     */
    public function displayBoth()
    {
        return $this->_getHelper()->displayCodBothPrices();
    }

    /**
     * Check if we need to display the CoD fee including the tax.
     *
     * @return bool
     */
    public function displayIncludeTax()
    {
        return $this->_getHelper()->displayCodFeeIncludingTax();
    }

    /**
     * Get the CoD fee including the tax.
     *
     * @return float
     */
    public function getCodFeeIncludeTax()
    {
        return $this->getTotal()->getAddress()->getCodFee() + $this->getTotal()->getAddress()->getCodTaxAmount();
    }

    /**
     * Get the CoD fee excluding the tax.
     *
     * @return float
     */
    public function getCodFeeExcludeTax()
    {
        return $this->getTotal()->getAddress()->getCodFee();
    }

    /**
     * Get the label for the CoD fee including the tax.
     *
     * @return float
     */
    public function getIncludeTaxLabel()
    {
        return $this->_getHelper()->__('Cash on Delivery fee Incl. Tax');
    }

    /**
     * Get the label for the CoD fee excluding the tax.
     *
     * @return float
     */
    public function getExcludeTaxLabel()
    {
        return $this->_getHelper()->__('Cash on Delivery fee Excl. Tax');
    }
}
