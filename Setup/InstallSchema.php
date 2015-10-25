<?php

namespace MeetMagentoPL\AdminSearch\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'indexer_state'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('adminsearch_indexer_state'))
            ->addColumn(
                'state_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Indexer State Id'
            )
            ->addColumn(
                'indexer_id',
                Table::TYPE_TEXT,
                255,
                [],
                'Indexer Id'
            )
            ->addColumn(
                'updated',
                Table::TYPE_DATETIME,
                null,
                [],
                'Indexer Status'
            )
            ->addIndex(
                $installer->getIdxName('indexer_state', ['indexer_id']),
                ['indexer_id']
            )
            ->setComment('Indexer State');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
