<?php
declare(strict_types=1);

namespace Deity\Sales\Setup;

use Deity\Sales\Model\ResourceModel\OrderIdMask;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 *
 * @package Deity\Sales\Setup
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if ($setup->getConnection()->isTableExists(OrderIdMask::TABLE_NAME_SOURCE_ITEM)) {
            return;
        }
        /**
         * Create table to store cartId and obscured UUID based cartId mapping
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable(OrderIdMask::TABLE_NAME_SOURCE_ITEM)
        )->addColumn(
            OrderIdMask::ID_FIELD_NAME,
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity Id'
        )->addColumn(
            OrderIdMask::ORDER_ID_FIELD_NAME,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Order ID'
        )->addColumn(
            OrderIdMask::MASKED_ID_FIELD_NAME,
            Table::TYPE_TEXT,
            32,
            ['nullable' => 'false'],
            'Masked ID'
        )->addIndex(
            $setup->getIdxName(OrderIdMask::TABLE_NAME_SOURCE_ITEM, [OrderIdMask::ORDER_ID_FIELD_NAME]),
            [OrderIdMask::ORDER_ID_FIELD_NAME]
        )->addForeignKey(
            $setup->getFkName(
                OrderIdMask::TABLE_NAME_SOURCE_ITEM,
                OrderIdMask::ORDER_ID_FIELD_NAME,
                'sales_order',
                'entity_id'
            ),
            OrderIdMask::ORDER_ID_FIELD_NAME,
            $setup->getTable('sales_order'),
            'entity_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Order ID and masked ID mapping'
        );

        $setup->getConnection()->createTable($table);
    }
}
