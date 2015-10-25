<?php

namespace MeetMagentoPL\AdminSearch\Model\Indexer\Menu\Action;

use Magento\Framework\ObjectManagerInterface;
use MeetMagentoPL\AdminSearch\Model\Indexer\AbstractAction;
use MeetMagentoPL\AdminSearch\Model\Indexer\ActionInterface;
use MeetMagentoPL\AdminSearch\Model\Indexer\Menu\Processor;

class Full extends AbstractAction implements ActionInterface
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Processor
     */
    private $menuProcessor;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param Processor $menuProcessor
     * @param array $data
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Processor $menuProcessor,
        array $data
    )
    {
        parent::__construct($objectManager, $data);

        $this->objectManager = $objectManager;
        $this->menuProcessor = $menuProcessor;
    }

    /**
     * @inheritDoc
     */
    public function reindexAll()
    {
        $data = $this->menuProcessor->process();

        $items = [];
        foreach ($data as $dataItem) {
            $item = $this->objectManager->create(
                'MeetMagentoPL\AdminSearch\Model\Index\Menu\ItemInterface',
                ['data' => $dataItem]
            );
            $items[] = $item;
        }

        $this->getIndexHandler()->saveIndex($items);

        return $items;
    }
}
