<?php

$this->startSetup();

    $this->_conn->addColumn($this->getTable('sales_flat_quote'), 'cod_fee', 'decimal(12,4)');
    $this->_conn->addColumn($this->getTable('sales_flat_quote'), 'base_cod_fee', 'decimal(12,4)');
    $this->_conn->addColumn($this->getTable('sales_flat_quote'), 'cod_tax_amount', 'decimal(12,4)');
    $this->_conn->addColumn($this->getTable('sales_flat_quote'), 'base_cod_tax_amount', 'decimal(12,4)');

    $this->_conn->addColumn($this->getTable('sales_flat_quote_address'), 'cod_fee', 'decimal(12,4)');
    $this->_conn->addColumn($this->getTable('sales_flat_quote_address'), 'base_cod_fee', 'decimal(12,4)');
    $this->_conn->addColumn($this->getTable('sales_flat_quote_address'), 'cod_tax_amount', 'decimal(12,4)');
    $this->_conn->addColumn($this->getTable('sales_flat_quote_address'), 'base_cod_tax_amount', 'decimal(12,4)');

    $eav = new Mage_Eav_Model_Entity_Setup('sales_setup');

    $eav->addAttribute('order', 'cod_fee', array('type' => 'decimal'));
    $eav->addAttribute('order', 'base_cod_fee', array('type' => 'decimal'));
    $eav->addAttribute('order', 'cod_tax_amount', array('type' => 'decimal'));
    $eav->addAttribute('order', 'base_cod_tax_amount', array('type' => 'decimal'));

    $eav->addAttribute('order', 'cod_fee_invoiced', array('type' => 'decimal'));
    $eav->addAttribute('order', 'base_cod_fee_invoiced', array('type' => 'decimal'));

    $eav->addAttribute('order', 'cod_tax_amount_invoiced', array('type' => 'decimal'));
    $eav->addAttribute('order', 'base_cod_tax_amount_invoiced', array('type' => 'decimal'));

    $eav->addAttribute('invoice', 'cod_fee', array('type' => 'decimal'));
    $eav->addAttribute('invoice', 'base_cod_fee', array('type' => 'decimal'));

    $eav->addAttribute('invoice', 'cod_tax_amount', array('type' => 'decimal'));
    $eav->addAttribute('invoice', 'base_cod_tax_amount', array('type' => 'decimal'));

    $sales = new Mage_Sales_Model_Mysql4_Setup('sales_setup');

    $sales->addAttribute('order', 'cod_fee', array('type' => 'decimal'));
    $sales->addAttribute('order', 'base_cod_fee', array('type' => 'decimal'));
    $sales->addAttribute('order', 'cod_fee_invoiced', array('type' => 'decimal'));
    $sales->addAttribute('order', 'base_cod_fee_invoiced', array('type' => 'decimal'));
    $sales->addAttribute('order', 'cod_tax_amount', array('type' => 'decimal'));
    $sales->addAttribute('order', 'base_cod_tax_amount', array('type' => 'decimal'));
    $sales->addAttribute('order', 'cod_tax_amount_invoiced', array('type' => 'decimal'));
    $sales->addAttribute('order', 'base_cod_tax_amount_invoiced', array('type' => 'decimal'));

    $sales->addAttribute('invoice', 'cod_fee', array('type' => 'decimal'));
    $sales->addAttribute('invoice', 'base_cod_fee', array('type' => 'decimal'));
    $sales->addAttribute('invoice', 'cod_tax_amount', array('type' => 'decimal'));
    $sales->addAttribute('invoice', 'base_cod_tax_amount', array('type' => 'decimal'));

    $sales->addAttribute('quote', 'cod_fee', array('type' => 'decimal'));
    $sales->addAttribute('quote', 'base_cod_fee', array('type' => 'decimal'));
    $sales->addAttribute('quote', 'cod_tax_amount', array('type' => 'decimal'));
    $sales->addAttribute('quote', 'base_cod_tax_amount', array('type' => 'decimal'));
    $sales->addAttribute('quote_address', 'cod_fee', array('type' => 'decimal'));
    $sales->addAttribute('quote_address', 'base_cod_fee', array('type' => 'decimal'));
    $sales->addAttribute('quote_address', 'cod_tax_amount', array('type' => 'decimal'));
    $sales->addAttribute('quote_address', 'base_cod_tax_amount', array('type' => 'decimal'));

    $sales->addAttribute('order', 'cod_fee_refunded', array('type' => 'decimal'));
    $sales->addAttribute('order', 'base_cod_fee_refunded', array('type' => 'decimal'));
    $sales->addAttribute('order', 'cod_tax_amount_refunded', array('type' => 'decimal'));
    $sales->addAttribute('order', 'base_cod_tax_amount_refunded', array('type' => 'decimal'));
    $sales->addAttribute('order', 'cod_fee_canceled', array('type' => 'decimal'));
    $sales->addAttribute('order', 'base_cod_fee_canceled', array('type' => 'decimal'));
    $sales->addAttribute('order', 'cod_tax_amount_canceled', array('type' => 'decimal'));
    $sales->addAttribute('order', 'base_cod_tax_amount_canceled', array('type' => 'decimal'));

    $sales->addAttribute('creditmemo', 'cod_fee', array('type' => 'decimal'));
    $sales->addAttribute('creditmemo', 'base_cod_fee', array('type' => 'decimal'));
    $sales->addAttribute('creditmemo', 'cod_tax_amount', array('type' => 'decimal'));
    $sales->addAttribute('creditmemo', 'base_cod_tax_amount', array('type' => 'decimal'));

$this->endSetup();
