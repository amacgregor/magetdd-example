<?php

$installer = $this;
$connection = $installer->getConnection();

$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('magetdd_magepay/transaction'),
    'customer_id',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => false,
        'default' => 0,
        'comment' => 'Customer Id'
    )
);

$installer->endSetup();
