<?php

namespace MeetMagentoPL\AdminSearch\Model;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Filter\SplitWords;

class IndexHandler
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var IndexStructureInterface
     */
    private $indexStructure;

    /**
     * @var array
     */
    private $data;

    /**
     * @var SplitWords
     */
    private $splitWordsFilter;

    /**
     * @param ResourceConnection $resource
     * @param IndexStructureInterface $indexStructure
     * @param SplitWords $splitWordsFilter
     * @param array $data
     */
    public function __construct(
        ResourceConnection $resource,
        IndexStructureInterface $indexStructure,
        SplitWords $splitWordsFilter,
        array $data
    )
    {
        $this->resource = $resource;
        $this->indexStructure = $indexStructure;
        $this->data = $data;
        $this->splitWordsFilter = $splitWordsFilter;
    }

    /**
     * @inheritDoc
     */
    public function saveIndex($items)
    {
        if (empty($items)) {
            return;
        }

        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'action' => $item->getAction(),
                'label' => $item->getLabel(),
                'data_index' => $item->getDataIndex(),
                'description' => $item->getDescription(),
            ];
        }

        $this->cleanIndex();
        $this->resource->getConnection()->insertMultiple(
            $this->getTableName(),
            $data
        );
    }

    /**
     * @inheritDoc
     */
    public function cleanIndex()
    {
        $this->indexStructure->delete($this->getIndexName());
        $this->indexStructure->create($this->getIndexName());
    }

    /**
     * @inheritDoc
     */
    public function searchIndex($query, $limit, $page)
    {
        $results = [];
        $words = $this->splitWordsFilter->filter($query);
        if (empty($words)) {
            return $results;
        }

        $select = $this->resource->getConnection()->select()
            ->from($this->getTableName())
            ->group('action');

        foreach ($words as $word) {
            $select->orWhere('data_index like ?', $word . '%');
        }

        $select->limit($limit, $page - 1);
        $items = $this->resource->getConnection()->fetchAll($select);

        foreach ($items as $item) {
            $results[] = [
                'type' => $this->getIndexName(),
                'action' => $item['action'],
                'label' => $item['label'],
                'description' => $item['description'],
            ];
        }

        return $results;
    }

    /**
     * Return indexer table name.
     *
     * @return string
     */
    private function getTableName()
    {
        return $this->resource->getTableName($this->getIndexName());
    }

    /**
     * Indexer name.
     *
     * @return string
     */
    private function getIndexName()
    {
        return $this->data['indexer_id'];
    }
}
