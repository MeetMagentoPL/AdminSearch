<?php

namespace MeetMagentoPL\AdminSearch\Model;

use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;

class Indexer extends DataObject
{
    /**
     * @var \MeetMagentoPL\AdminSearch\Model\Config
     */
    private $config;

    /**
     * @var \MeetMagentoPL\AdminSearch\Model\Indexer\StateFactory
     */
    private $stateFactory;

    /**
     * @var Indexer\State
     */
    private $state;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var IndexStructureFactory
     */
    private $indexStructureFactory;

    /**
     * @param \MeetMagentoPL\AdminSearch\Model\Config $config
     * @param \MeetMagentoPL\AdminSearch\Model\Indexer\StateFactory $stateFactory
     * @param IndexStructureFactory $indexStructureFactory
     * @param ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        \MeetMagentoPL\AdminSearch\Model\Config $config,
        \MeetMagentoPL\AdminSearch\Model\Indexer\StateFactory $stateFactory,
        \MeetMagentoPL\AdminSearch\Model\IndexStructureFactory $indexStructureFactory,
        ObjectManagerInterface $objectManager,
        array $data = []
    )
    {
        parent::__construct($data);

        $this->config = $config;
        $this->stateFactory = $stateFactory;
        $this->objectManager = $objectManager;
        $this->indexStructureFactory = $indexStructureFactory;
    }

    /**
     * Return indexer action class
     *
     * @return string
     */
    public function getActionClass()
    {
        return $this->getData('action_class');
    }

    /**
     * Load indexer.
     *
     * @param $indexerId
     * @return $this
     */
    public function load($indexerId)
    {
        $indexer = $this->config->getIndexer($indexerId);
        if (empty($indexer) || empty($indexer['indexer_id']) || $indexer['indexer_id'] != $indexerId) {
            throw new \InvalidArgumentException("{$indexerId} indexer does not exist.");
        }

        $this->setId($indexerId);
        $this->setData($indexer);

        return $this;
    }

    /**
     * Retrieve indexer state.
     *
     * @return Indexer\State
     */
    public function getState()
    {
        if (!$this->state) {
            $this->state = $this->stateFactory->create();
            $this->state->loadByIndexer($this->getIndexerId());
        }

        return $this->state;
    }

    /**
     * Return index last update time.
     *
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->getState()->getUpdated();
    }

    /**
     * Create index structure instance.
     *
     * @return \MeetMagentoPL\AdminSearch\Model\IndexStructure
     */
    protected function getStuctureInstance()
    {
        return $this->indexStructureFactory->create();
    }

    /**
     * Return indexer action instance
     *
     * @return Indexer\ActionInterface
     */
    protected function getActionInstance()
    {
        return $this->objectManager->create($this->getActionClass(), [
            'data' => $this->getData()
        ]);
    }

    /**
     * Reindex all items.
     *
     * @return void
     */
    public function reindexAll()
    {
        $this->getActionInstance()->reindexAll();
        $this->getState()
            ->setUpdated(time())
            ->save();
    }
}