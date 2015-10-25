<?php

namespace MeetMagentoPL\AdminSearch\Model\Indexer\Config\Action;

use Magento\Framework\ObjectManagerInterface;
use MeetMagentoPL\AdminSearch\Model\Indexer\Config\Processor;
use MeetMagentoPL\AdminSearch\Model\Indexer\AbstractAction;
use MeetMagentoPL\AdminSearch\Model\Indexer\ActionInterface;

class Full extends AbstractAction implements ActionInterface
{
    /**
     * @var Processor
     */
    private $configProcessor;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param Processor $configProcessor
     * @param array $data
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Processor $configProcessor,
        array $data
    )
    {
        parent::__construct($objectManager, $data);

        $this->configProcessor = $configProcessor;
        $this->objectManager = $objectManager;
    }

    /**
     * @inheritDoc
     */
    public function reindexAll()
    {
        $data = $this->configProcessor->process();

        $items = [];
        foreach ($data as $dataItem) {
            $item = $this->objectManager->create(
                'MeetMagentoPL\AdminSearch\Model\Index\Config\ItemInterface',
                ['data' => $dataItem]
            );
            $items[] = $item;
        }

        $this->getIndexHandler()->saveIndex($items);

        return $items;
    }
}
