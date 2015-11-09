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

class Balticode_Cashondelivery_Model_Sales_Creditmemo_Total extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'Balticode_Cashondelivery') {
            return $this;
        }

        $creditmemoBaseGrandTotal = $creditmemo->getBaseGrandTotal();
        $creditmemoGrandTotal     = $creditmemo->getGrandTotal();

        $baseCodFeeRefunded       = $order->getBaseCodFeeRefunded();
        $codFeeRefunded           = $order->getCodFeeRefunded();

        $baseCodFeeInvoiced       = $order->getBaseCodFeeInvoiced();
        $codFeeInvoiced           = $order->getCodFeeInvoiced();

        $baseCodFeeToRefund       = abs($baseCodFeeInvoiced - $baseCodFeeRefunded);
        $codFeeToRefund           = abs($codFeeInvoiced     - $codFeeRefunded);

        if ($baseCodFeeToRefund <= 0) {
            return $this;
        }

        $creditmemo->setBaseGrandTotal($creditmemoBaseGrandTotal + $baseCodFeeToRefund)
                   ->setGrandTotal($creditmemoGrandTotal         + $codFeeToRefund)
                   ->setBaseCodFee($baseCodFeeToRefund)
                   ->setCodFee($codFeeToRefund);

        $order->setBaseCodFeeRefunded($baseCodFeeRefunded + $baseCodFeeToRefund)
              ->setCodFeeRefunded($codFeeRefunded         + $codFeeToRefund);

        return $this;
    }
}
