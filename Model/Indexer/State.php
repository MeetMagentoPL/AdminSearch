<?php
namespace MeetMagentoPL\AdminSearch\Model\Indexer;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class State extends AbstractModel
{
    /**
     * @param Context $context
     * @param Registry $registry
     * @param \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State $resource
     * @param \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State $resource,
        \MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State\Collection $resourceCollection,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Fill object with state data
     *
     * @param string $indexerId
     * @return $this
     */
    public function loadByIndexer($indexerId)
    {
        $this->load($indexerId, 'indexer_id');

        if (!$this->getId()) {
            $this->setIndexerId($indexerId);
        }

        return $this;
    }
}
