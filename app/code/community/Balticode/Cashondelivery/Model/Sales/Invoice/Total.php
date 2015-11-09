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

class Balticode_Cashondelivery_Model_Sales_Invoice_Total extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order = $invoice->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'Balticode_Cashondelivery' || !$order->getCodFee()) {
            return $this;
        }

        foreach ($invoice->getOrder()->getInvoiceCollection() as $previousInvoice) {
            if ($previousInvoice->getCodAmount() && !$previousInvoice->isCanceled()) {
                $includeCodTax = false;
            }
        }

        $baseCodFee         = $order->getBaseCodFee();
        $baseCodFeeInvoiced = $order->getBaseCodFeeInvoiced();
        $baseInvoiceTotal   = $invoice->getBaseGrandTotal();
        $codFee             = $order->getCodFee();
        $codFeeInvoiced     = $order->getCodFeeInvoiced();
        $invoiceTotal       = $invoice->getGrandTotal();

        if (!$baseCodFee || $baseCodFeeInvoiced==$baseCodFee) {
            return $this;
        }

        $baseCodFeeToInvoice = $baseCodFee - $baseCodFeeInvoiced;
        $codFeeToInvoice     = $codFee     - $codFeeInvoiced;

        $baseInvoiceTotal = $baseInvoiceTotal + $baseCodFeeToInvoice;
        $invoiceTotal     = $invoiceTotal     + $codFeeToInvoice;

        $invoice->setBaseGrandTotal($baseInvoiceTotal);
        $invoice->setGrandTotal($invoiceTotal);

        $invoice->setBaseCodFee($baseCodFeeToInvoice);
        $invoice->setCodFee($codFeeToInvoice);

        $order->setBaseCodFeeInvoiced($baseCodFeeInvoiced + $baseCodFeeToInvoice);
        $order->setCodFeeInvoiced($codFeeInvoiced         + $codFeeToInvoice);

        return $this;
    }
}
