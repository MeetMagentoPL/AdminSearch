<?php

namespace MeetMagentoPL\AdminSearch\Model\Config;

use Magento\Framework\Config\CacheInterface;

class Data extends \Magento\Framework\Config\Data
{
    /**
     * @var \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\Collection
     */
    protected $stateCollection;

    /**
     * @param Reader $reader
     * @param CacheInterface $cache
     * @param \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\Collection $stateCollection
     * @param string $cacheId
     */
    public function __construct(
        Reader $reader,
        CacheInterface $cache,
        \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\Collection $stateCollection,
        $cacheId = 'adminsearch_indexer_config'
    )
    {
        $this->stateCollection = $stateCollection;

        $isCacheExists = $cache->test($cacheId);

        parent::__construct($reader, $cache, $cacheId);

        if (!$isCacheExists) {
            $this->deleteNonexistentStates();
        }
    }

    /**
     * Delete all states that are not in configuration
     *
     * @return void
     */
    protected function deleteNonexistentStates()
    {
        foreach ($this->stateCollection->getItems() as $state) {
            if (!isset($this->_data[$state->getIndexerId()])) {
                $state->delete();
            }
        }
    }
}
