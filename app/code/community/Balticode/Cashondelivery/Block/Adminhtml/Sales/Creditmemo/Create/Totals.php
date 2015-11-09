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

class Balticode_Cashondelivery_Block_Adminhtml_Sales_Creditmemo_Create_Totals extends Mage_Adminhtml_Block_Template
{
    /**
     * Holds the creditmemo object.
     * @var Mage_Sales_Model_Order_Creditmemo
     */
    protected $_source;

    /**
     * Initialize creditmemo COD totals
     *
     * @return Balticode_CashOnDelivery_Block_Adminhtml_Sales_Creditmemo_Create_Totals
     */
    public function initTotals()
    {
        $parent         = $this->getParentBlock();
        $this->_source  = $parent->getSource();
        $total          = new Varien_Object(array(
            'code'      => 'Balticode_cashondelivery_fee',
            'value'     => $this->getCodAmount(),
            'base_value'=> $this->getCodAmount(),
            'label'     => $this->helper('Balticode_cashondelivery')->__('Refund Cash on Delivery fee')
        ));

        $parent->addTotalBefore($total, 'shipping');
        return $this;
    }

    /**
     * Getter for the creditmemo object.
     *
     * @return Mage_Sales_Model_Order_Creditmemo
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * Get CoD fee amount for actual invoice.
     * @return float
     */
    public function getCodAmount()
    {
        $codFee = $this->_source->getCodFee() + $this->_source->getCodTaxAmount();

        return Mage::app()->getStore()->roundPrice($codFee);
    }
}