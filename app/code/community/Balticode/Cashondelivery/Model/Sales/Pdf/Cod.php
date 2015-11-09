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

class Balticode_Cashondelivery_Model_Sales_Pdf_Cod extends Mage_Sales_Model_Order_Pdf_Total_Default
{
    /**
     * Get array of arrays with totals information for display in PDF
     * array(
     *  $index => array(
     *      'amount'   => $amount,
     *      'label'    => $label,
     *      'font_size'=> $font_size
     *  )
     * )
     * @return array
     */
    public function getTotalsForDisplay()
    {
        $amount        = $this->getOrder()->formatPriceTxt($this->getAmount());
        $amountInclTax = $this->getAmount() + $this->getSource()->getCodTaxAmount();
        $amountInclTax = $this->getOrder()->formatPriceTxt($amountInclTax);
        $fontSize      = $this->getFontSize() ? $this->getFontSize() : 7;
        $helper        = Mage::helper('Balticode_Cashondelivery/data');

        if ($helper->displayCodBothPrices()){
            $totals = array(
                array(
                    'amount'    => $this->getAmountPrefix().$amount,
                    'label'     => $helper->__('Cash on Delivery fee (Excl.Tax)') . ':',
                    'font_size' => $fontSize
                ),
                array(
                    'amount'    => $this->getAmountPrefix().$amountInclTax,
                    'label'     => $helper->__('Cash on Delivery fee (Incl.Tax)') . ':',
                    'font_size' => $fontSize
                ),
            );
        } elseif ($helper->displayCodFeeIncludingTax()) {
            $totals = array(
                array(
                    'amount'    => $this->getAmountPrefix().$amountInclTax,
                    'label'     => $helper->__($this->getTitle()) . ':',
                    'font_size' => $fontSize
                )
            );
        } else {
            $totals = array(
                array(
                    'amount'    => $this->getAmountPrefix().$amount,
                    'label'     => $helper->__($this->getTitle()) . ':',
                    'font_size' => $fontSize
                )
            );
        }
        return $totals;
    }
}
