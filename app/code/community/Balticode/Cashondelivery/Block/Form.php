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

class Balticode_Cashondelivery_Block_Form extends Mage_Payment_Block_Form
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'balticode/cashondelivery/form.phtml';

    public function getQuote()
    {
        return $this->getMethod()->getInfoInstance()->getQuote();
    }

    public function getShippingAddress()
    {
        return $this->getQuote()->getShippingAddress();
    }

    public function convertPrice($price, $format=false, $includeContainer = true)
    {
        return $this->getQuote()->getStore()->convertPrice($price, $format, $includeContainer);
    }
}
