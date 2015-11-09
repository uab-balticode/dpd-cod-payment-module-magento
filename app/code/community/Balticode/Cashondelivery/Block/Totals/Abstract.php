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

class Balticode_Cashondelivery_Block_Totals_Abstract extends Mage_Core_Block_Abstract
{
    /**
     * Holds the correct object from where we get the CoD fees and taxes from.
     * For example this could be the order or invoice object.
     *
     * @var Varien_Object
     */
    protected $_totalObject;

    /**
     * Holds the correct parent block from which we get the total object and set the totals.
     *
     * @var Mage_Core_Block_Abstract
     */
    protected $_parentBlock;

    /**
     * Generate and add the CoD totals to the parent block.
     *
     * @return Balticode_CashOnDelivery_Block_Totals_Abstract
     */
    public function initTotals()
    {
        $this->_prepareTotals();
        if (Mage::helper('Balticode_Cashondelivery/data')->isMe($this->_parentBlock->getOrder())) {
            if ($this->_totalObject->getCodFee()) {
                $label     = $this->__('Cash on Delivery fee');
                $value     = $this->_totalObject->getCodFee();
                $baseValue = $this->_totalObject->getBaseCodFee();
                $code      = 'balticode_cashondelivery_fee';

                if (Mage::helper('Balticode_Cashondelivery/data')->displayCodBothPrices()) {
                    $label = $this->__('Cash on Delivery fee (Excl.Tax)');

                    $totalInclLabel     = $this->__('Cash on Delivery fee (Incl.Tax)');
                    $totalInclValue     = $this->_totalObject->getCodFee()     + $this->_totalObject->getCodTaxAmount();
                    $totalInclBaseValue = $this->_totalObject->getBaseCodFee() + $this->_totalObject->getBaseCodTaxAmount();
                    $totalInclCode      = 'Balticode_cashondelivery_fee_incl';

                } elseif (Mage::helper('Balticode_Cashondelivery/data')->displayCodFeeIncludingTax()) {
                    $value     = $this->_totalObject->getCodFee()     + $this->_totalObject->getCodTaxAmount();
                    $baseValue = $this->_totalObject->getBaseCodFee() + $this->_totalObject->getBaseCodTaxAmount();
                }

                $totalObject = $this->_getTotalObject($label, $value, $baseValue, $code);
                $this->_addTotalToParent($totalObject);

                if (isset($totalInclLabel)) {
                    $totalInclObject = $this->_getTotalObject($totalInclLabel, $totalInclValue, $totalInclBaseValue, $totalInclCode);
                    $this->_addTotalToParent($totalInclObject, 'balticode_cashondelivery_fee');
                }
            }
        }
        return $this;
    }

    /**
     * To be able to abstract the CoD totals we need an own method to set the right objects.
     *
     * @return Balticode_CashOnDelivery_Block_Totals_Abstract
     */
    protected function _prepareTotals()
    {
        return $this;
    }

    /**
     * Generate an Varien_Object which could be set as total to the parent block.
     *
     * @param $label string
     * @param $value string
     * @param $baseValue string
     * @param $code string
     * @return Varien_Object
     */
    protected function _getTotalObject($label, $value, $baseValue, $code)
    {
        $total = new Varien_Object();
        $total->setLabel($label)
              ->setValue($value)
              ->setBaseValue($baseValue)
              ->setCode($code);

        return $total;
    }

    /**
     * Add an Varien_Object, which holds the total values, to the parent block.
     *
     * @param $total Varien_Object
     * @param $after null|string
     * @return Balticode_CashOnDelivery_Block_Totals_Abstract
     */
    protected function _addTotalToParent($total, $after = null)
    {
        if (!$after) {
            $after = Mage::helper('Balticode_Cashondelivery/data')->getTotalAfterPosition();
        }

        $this->_parentBlock->addTotal($total, $after);

        return $this;
    }
}