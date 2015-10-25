<?php

namespace MeetMagentoPL\AdminSearch\Model;

use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;

class Search extends DataObject
{
    /**
     * @var \Magento\Backend\Helper\Data
     */
    private $backendHelper;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ObjectManagerInterface
     */
    private $objectmanager;

    /**
     * @param ObjectManagerInterface $objectmanager
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param Config $config
     */
    public function __construct(
        ObjectManagerInterface $objectmanager,
        \Magento\Backend\Helper\Data $backendHelper,
        Config $config
    )
    {
        parent::__construct();

        $this->backendHelper = $backendHelper;
        $this->config = $config;
        $this->objectmanager = $objectmanager;
    }

    /**
     * Load search results
     *
     * @return $this
     */
    public function load()
    {
        $mergedResults = [];
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($mergedResults);
            return $this;
        }

        foreach ($this->config->getIndexers() as $indexerId => $indexer) {
            $indexHandler = $this->objectmanager->create(
                'MeetMagentoPL\AdminSearch\Model\IndexHandlerInterface',
                ['data' => $indexer]
            );

            $searchResults = $indexHandler->searchIndex($this->getQuery(), $this->getLimit(), $this->getStart());
            $indexerResults = [];
            foreach ($searchResults as $result) {
                $indexerResults[] = [
                    'id' => $result['action'],
                    'name' => $result['label'],
                    'url' => $this->backendHelper->getUrl($result['action']),
                    'description' => $result['description'],
                ];
            }
            $mergedResults = array_merge_recursive($indexerResults, $mergedResults);
        }

        $this->setResults($mergedResults);

        return $this;
    }
}
