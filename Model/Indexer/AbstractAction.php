<?php

namespace MeetMagentoPL\AdminSearch\Model\Indexer;

use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;

abstract class AbstractAction extends DataObject
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        array $data = []
    )
    {
        parent::__construct($data);

        $this->objectManager = $objectManager;
    }

    /**
     * Return indexer handler instance
     *
     * @return \MeetMagentoPL\AdminSearch\Model\IndexHandlerInterface
     */
    protected function getIndexHandler()
    {
        return $this->objectManager->create(
            'MeetMagentoPL\AdminSearch\Model\IndexHandlerInterface',
            ['data' => $this->getData()]
        );
    }
}
