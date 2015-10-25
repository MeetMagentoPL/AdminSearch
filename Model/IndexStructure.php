<?php

namespace MeetMagentoPL\AdminSearch\Model;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\State;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Exception\LocalizedException;

class IndexStructure implements IndexStructureInterface
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var State
     */
    private $appState;

    /**
     * @param ResourceConnection $resource
     * @param State $appState
     */
    public function __construct(
        ResourceConnection $resource,
        State $appState
    )
    {
        $this->resource = $resource;
        $this->appState = $appState;

        try {
            $this->appState->setAreaCode('adminhtml');
        } catch (LocalizedException $e) {
        }
    }

    /**
     * @inheritDoc
     */
    public function delete($name)
    {
        $tableName = $this->getTableName($name);
        if ($this->resource->getConnection()->isTableExists($tableName)) {
            $this->resource->getConnection()->dropTable($tableName);
        }
    }

    /**
     * @inheritDoc
     */
    public function create($name)
    {
        $tableName = $this->getTableName($name);
        $table = $this->resource->getConnection()->newTable($tableName)
            ->addColumn(
                'item_id',
                Table::TYPE_INTEGER,
                10,
                ['unsigned' => true, 'nullable' => false, 'primary' => true, 'identity' => true],
                'Item ID'
            )->addColumn(
                'label',
                Table::TYPE_TEXT,
                '256k',
                ['nullable' => false],
                'Label'
            )->addColumn(
                'description',
                Table::TYPE_TEXT,
                '255',
                ['nullable' => false],
                'Action'
            )->addColumn(
                'data_index',
                Table::TYPE_TEXT,
                '50',
                ['nullable' => false],
                'Searchable Value'
            )->addColumn(
                'action',
                Table::TYPE_TEXT,
                '255',
                ['nullable' => false],
                'Action'
            )->addIndex(
                'FTI_ACTION_INDEX',
                ['action'],
                ['type' => AdapterInterface::INDEX_TYPE_INDEX]
            )->addIndex(
                'FTI_DATA_INDEX_INDEX',
                ['data_index'],
                ['type' => AdapterInterface::INDEX_TYPE_INDEX]
            );
        $this->resource->getConnection()->createTable($table);
    }

    /**
     * Return indexer table name.
     *
     * @param string $indexName
     * @return string
     */
    private function getTableName($indexName)
    {
        return $this->resource->getTableName($indexName);
    }
}