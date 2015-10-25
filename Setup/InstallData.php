<?php

namespace MeetMagentoPL\AdminSearch\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;

/**
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InstallData implements InstallDataInterface
{
    /**
     * Indexer collection factory
     *
     * @var \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\CollectionFactory
     */
    private $statesFactory;

    /**
     * @var \MeetMagentoPL\AdminSearch\Model\Config
     */
    private $config;

    /**
     * @var \MeetMagentoPL\AdminSearch\Model\Indexer\StateFactory
     */
    private $stateFactory;

    /**
     * @param \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\CollectionFactory $statesFactory
     * @param \MeetMagentoPL\AdminSearch\Model\Indexer\StateFactory $stateFactory
     * @param \MeetMagentoPL\AdminSearch\Model\Config $config
     */
    public function __construct(
        \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\CollectionFactory $statesFactory,
        \MeetMagentoPL\AdminSearch\Model\Indexer\StateFactory $stateFactory,
        \MeetMagentoPL\AdminSearch\Model\Config $config
    )
    {
        $this->statesFactory = $statesFactory;
        $this->stateFactory = $stateFactory;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $stateIndexers = [];
        $states = $this->statesFactory->create();
        foreach ($states->getItems() as $state) {
            $stateIndexers[$state->getIndexerId()] = $state;
        }

        foreach ($this->config->getIndexers() as $indexerId => $indexerConfig) {
            if (isset($stateIndexers[$indexerId])) {
                $stateIndexers[$indexerId]->save();
            } else {
                $state = $this->stateFactory->create();
                $state->loadByIndexer($indexerId);
                $state->save();
            }
        }
    }
}
