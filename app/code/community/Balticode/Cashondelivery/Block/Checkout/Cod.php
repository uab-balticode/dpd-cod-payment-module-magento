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
class Balticode_Cashondelivery_Block_Checkout_Cod extends Mage_Checkout_Block_Total_Default
{
    protected $_template = 'balticode/cashondelivery/checkout/cod.phtml';

    /**
     * Check if we need display COD fee include and exlude tax
     *
     * @return bool
     */
    public function displayBoth()
    {
        return Mage::helper('Balticode_Cashondelivery/data')->displayCodBothPrices();
    }

    /**
     * Check if we need display COD fee include tax
     *
     * @return bool
     */
    public function displayIncludeTax()
    {
        return Mage::helper('Balticode_Cashondelivery/data')->displayCodFeeIncludingTax();
    }

    /**
     * Get COD fee include tax
     *
     * @return float
     */
    public function getCodFeeIncludeTax()
    {
        $codFeeInclTax = 0;
        foreach ($this->getTotal()->getAddress()->getQuote()->getAllShippingAddresses() as $address) {
            $codFeeInclTax += $address->getCodFee() + $address->getCodTaxAmount();
        }
        return $codFeeInclTax;
    }

    /**
     * Get COD fee exclude tax
     *
     * @return float
     */
    public function getCodFeeExcludeTax()
    {
        $codFeeExclTax = 0;
        foreach ($this->getTotal()->getAddress()->getQuote()->getAllShippingAddresses() as $address) {
            $codFeeExclTax += $address->getCodFee();
        }
        return $codFeeExclTax;
    }

    /**
     * Get label for COD fee include tax
     *
     * @return float
     */
    public function getIncludeTaxLabel()
    {
        return $this->helper('Balticode_Cashondelivery/data')->__('Cash on Delivery fee (Incl.Tax)');
    }

    /**
     * Get label for COD fee exclude tax
     *
     * @return float
     */
    public function getExcludeTaxLabel()
    {
        return $this->helper('Balticode_Cashondelivery/data')->__('Cash on Delivery fee (Excl.Tax)');
    }
}
