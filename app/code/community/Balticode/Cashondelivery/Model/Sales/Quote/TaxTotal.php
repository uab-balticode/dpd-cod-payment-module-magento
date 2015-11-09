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

class Balticode_Cashondelivery_Model_Sales_Quote_TaxTotal extends Mage_Sales_Model_Quote_Address_Total_Tax
{
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        $collection = $address->getQuote()->getPaymentsCollection();
        if ($collection->count() <= 0 || $address->getQuote()->getPayment()->getMethod() == null) {
            return $this;
        }

        $paymentMethod = $address->getQuote()->getPayment()->getMethodInstance();

        if ($paymentMethod->getCode() != 'Balticode_cashondelivery') {
            return $this;
        }

        $store = $address->getQuote()->getStore();        

        $items = $address->getAllItems();
        if (!count($items)) {
            return $this;
        }

        $custTaxClassId = $address->getQuote()->getCustomerTaxClassId();

        $taxCalculationModel = Mage::getSingleton('tax/calculation');
        /* @var $taxCalculationModel Mage_Tax_Model_Calculation */
        $request = $taxCalculationModel->getRateRequest(
            $address,
            $address->getQuote()->getBillingAddress(),
            $custTaxClassId,
            $store
        );
        $codTaxClass = Mage::helper('Balticode_Cashondelivery/data')->getCodTaxClass($store);

        $codTax      = 0;
        $codBaseTax  = 0;

        if ($codTaxClass) {
            if ($rate = $taxCalculationModel->getRate($request->setProductClassId($codTaxClass))) {
                if (!Mage::helper('Balticode_Cashondelivery/data')->codPriceIncludesTax()) {
                    $codTax    = $address->getCodFee() * $rate/100;
                    $codBaseTax= $address->getBaseCodFee() * $rate/100;
                } else {
                    $codTax    = $address->getCodTaxAmount();
                    $codBaseTax= $address->getBaseCodTaxAmount();
                }

                $codTax    = $store->roundPrice($codTax);
                $codBaseTax= $store->roundPrice($codBaseTax);

                $address->setTaxAmount($address->getTaxAmount() + $codTax);
                $address->setBaseTaxAmount($address->getBaseTaxAmount() + $codBaseTax);

                $this->_saveAppliedTaxes(
                    $address,
                    $taxCalculationModel->getAppliedRates($request),
                    $codTax,
                    $codBaseTax,
                    $rate
                );
            }
        }

        if (!Mage::helper('Balticode_Cashondelivery/data')->codPriceIncludesTax()) {
            $address->setCodTaxAmount($codTax);
            $address->setBaseCodTaxAmount($codBaseTax);
        }

        $address->setGrandTotal($address->getGrandTotal() + $address->getCodTaxAmount());
        $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseCodTaxAmount());

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {        
        $store = $address->getQuote()->getStore();
        /**
         * Modify subtotal
         */
        if (Mage::getSingleton('tax/config')->displayCartSubtotalBoth($store) ||
            Mage::getSingleton('tax/config')->displayCartSubtotalInclTax($store)) {
            if ($address->getSubtotalInclTax() > 0) {
                $subtotalInclTax = $address->getSubtotalInclTax();
            } else {
                $subtotalInclTax = $address->getSubtotal()+ $address->getTaxAmount() -
                    $address->getShippingTaxAmount() - $address->getCodTaxAmount();
            }            

            $address->addTotal(
                array(
                    'code'      => 'subtotal',
                    'title'     => Mage::helper('sales')->__('Subtotal'),
                    'value'     => $subtotalInclTax,
                    'value_incl_tax' => $subtotalInclTax,
                    'value_excl_tax' => $address->getSubtotal()
                )
            );
        }
        return $this;
    }
}
