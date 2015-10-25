<?php
namespace MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Collection initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MeetMagentoPL\AdminSearch\Model\Indexer\State', 'MeetMagentoPL\AdminSearch\Model\ResourceModel\Indexer\State');
    }
}
