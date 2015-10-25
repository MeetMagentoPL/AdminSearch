<?php
namespace MeetMagentoPL\AdminSearch\Model\Indexer;

class Collection extends \Magento\Framework\Data\Collection
{
    /**
     * Item object class name
     *
     * @var string
     */
    protected $_itemObjectClass = 'MeetMagentoPL\AdminSearch\Model\IndexerInterface';

    /**
     * @var \MeetMagentoPL\AdminSearch\Model\Config
     */
    protected $config;

    /**
     * @var \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\CollectionFactory
     */
    protected $statesFactory;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \MeetMagentoPL\AdminSearch\Model\Config $config
     * @param \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\CollectionFactory $statesFactory
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \MeetMagentoPL\AdminSearch\Model\Config $config,
        \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\CollectionFactory $statesFactory
    )
    {
        $this->config = $config;
        $this->statesFactory = $statesFactory;
        parent::__construct($entityFactory);
    }

    /**
     * Load data
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return Collection
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            $states = $this->statesFactory->create();
            foreach (array_keys($this->config->getIndexers()) as $indexerId) {
                $indexer = $this->getNewEmptyItem();
                $indexer->load($indexerId);
                foreach ($states->getItems() as $state) {
                    if ($state->getIndexerId() == $indexerId) {
                        $indexer->setState($state);
                        break;
                    }
                }
                $this->_addItem($indexer);
            }
            $this->_setIsLoaded(true);
        }
        return $this;
    }
}
