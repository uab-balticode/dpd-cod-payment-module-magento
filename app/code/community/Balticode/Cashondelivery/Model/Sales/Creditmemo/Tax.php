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

class Balticode_Cashondelivery_Model_Sales_Creditmemo_Tax extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'Balticode_cashondelivery') {
            return $this;
        }

        $creditmemoBaseGrandTotal = $creditmemo->getBaseGrandTotal();
        $creditmemoGrandTotal     = $creditmemo->getGrandTotal();
        $creditmemoBaseTaxAmount  = $creditmemo->getBaseTaxAmount();
        $creditmemoTaxAmount      = $creditmemo->getTaxAmount();

        $baseCodTaxAmountRefunded = $order->getBaseCodTaxAmountRefunded();
        $codTaxAmountRefunded     = $order->getCodTaxAmountRefunded();

        $baseCodTaxAmountInvoiced = $order->getBaseCodTaxAmountInvoiced();
        $codTaxAmountInvoiced     = $order->getCodTaxAmountInvoiced();

        $baseCodTaxAmountToRefund = abs($baseCodTaxAmountInvoiced - $baseCodTaxAmountRefunded);
        $codTaxAmountToRefund     = abs($codTaxAmountInvoiced     - $codTaxAmountRefunded);

        if ($baseCodTaxAmountToRefund <= 0) {
            return $this;
        }

        $creditmemo->setBaseGrandTotal($creditmemoBaseGrandTotal + $baseCodTaxAmountToRefund)
                   ->setGrandTotal($creditmemoGrandTotal         + $codTaxAmountToRefund)
                   ->setBaseTaxAmount($creditmemoBaseTaxAmount   + $baseCodTaxAmountToRefund)
                   ->setTaxAmount($creditmemoTaxAmount           + $codTaxAmountToRefund)
                   ->setBaseCodTaxAmount($codTaxAmountToRefund)
                   ->setCodTaxAmount($codTaxAmountToRefund);

        $order->setBaseCodTaxAmountRefunded($baseCodTaxAmountRefunded + $baseCodTaxAmountToRefund)
              ->setCodTaxAmountRefunded($codTaxAmountRefunded         + $codTaxAmountToRefund);

        return $this;
    }
}
