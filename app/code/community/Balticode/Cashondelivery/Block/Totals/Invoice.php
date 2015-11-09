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

class Balticode_Cashondelivery_Block_Totals_Invoice extends Balticode_Cashondelivery_Block_Totals_Abstract
{
    /**
     * Set the correct parent block and the object from which we get / set our total data.
     *
     * @return This class
     */
    protected function _prepareTotals()
    {
        parent::_prepareTotals();

        $this->_parentBlock = $this->getParentBlock();
        $this->_totalObject = $this->_parentBlock->getInvoice();

        return $this;
    }
}
