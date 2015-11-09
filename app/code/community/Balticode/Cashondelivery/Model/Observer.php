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

class Balticode_Cashondelivery_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Collects codFee from quote/addresses to quote
     *
     * @param Varien_Event_Observer $observer
     * @return This class
     */
    public function sales_quote_collect_totals_after(Varien_Event_Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $data  = $observer->getInput();

        $quote->setCodFee(0);
        $quote->setBaseCodFee(0);
        $quote->setCodTaxAmount(0);
        $quote->setBaseCodTaxAmount(0);

        foreach ($quote->getAllAddresses() as $address) {
            $quote->setCodFee((float)($quote->getCodFee() + $address->getCodFee()));
            $quote->setBaseCodFee((float)($quote->getBaseCodFee() + $address->getBaseCodFee()));
            $quote->setCodTaxAmount((float)($quote->getCodTaxAmount() + $address->getCodTaxAmount()));
            $quote->setBaseCodTaxAmount((float)($quote->getBaseCodTaxAmount() + $address->getBaseCodTaxAmount()));
        }
        return $this;
    }

    /**
     * Performs order_create_loadBlock response update
     * adds totals block to each response
     * This function is deprecated, the totals block update is implemented
     * in Balticode/cashondelivery/sales.js (SalesOrder class extension)
     *
     * @param Varien_Event_Observer $observer
     * @return this class
     */
    public function controller_action_layout_load_before(Varien_Event_Observer $observer)
    {
        $action = $observer->getAction();

        if ($action->getFullActionName() != 'adminhtml_sales_order_create_loadBlock' || !$action->getRequest()->getParam('json')) {
            return $this;
        }

        $layout = $observer->getLayout();
        $layout->getUpdate()->addHandle('adminhtml_sales_order_create_load_block_totals');

        return $this;
    }

    /**
     * When the order gets canceled we put the Cash on Delivery fee and tax also in the canceled columns.
     *
     * @param Varien_Event_Observer $observer
     * @return Balticode_CashOnDelivery_Model_Observer
     */
    public function order_cancel_after(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'Balticode_cashondelivery') {
            return $this;
        }

        $codFee     = $order->getCodFee();
        $baseCodFee = $order->getBaseCodFee();
        $codTax     = $order->getCodTaxAmount();
        $baseCodTax = $order->getBaseCodTaxAmount();

        $codFeeInvoiced     = $order->getCodFeeInvoiced();

        if ($codFeeInvoiced) {
            $baseCodFeeInvoiced = $order->getBaseCodFeeInvoiced();
            $codTaxInvoiced     = $order->getCodTaxAmountInvoiced();
            $baseCodTaxInvoiced = $order->getBaseCodTaxAmountInvoiced();

            $codFee     = $codFee     - $codFeeInvoiced;
            $baseCodFee = $baseCodFee - $baseCodFeeInvoiced;
            $codTax     = $codTax     - $codTaxInvoiced;
            $baseCodTax = $baseCodTax - $baseCodTaxInvoiced;
        }

        if ($baseCodFee) {
            $order->setCodFeeCanceled($codFee)
                  ->setBaseCodFeeCanceled($baseCodFee)
                  ->setCodTaxAmountCanceled($codTax)
                  ->setBaseCodTaxAmountCanceled($baseCodTax)
                  ->save();
        }

        return $this;
    }

    /**
     * Observer for Messages from heaven
     */
    public function preDispatch(Varien_Event_Observer $observer)
    {
        if (Mage::getSingleton('admin/session')->isLoggedIn()) {
            $feedModel  = Mage::getModel('Balticode_Cashondelivery/feed');
            $feedModel->checkUpdate();
        }
    }
}
