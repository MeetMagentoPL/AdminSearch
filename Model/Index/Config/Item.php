<?php

namespace MeetMagentoPL\AdminSearch\Model\Index\Config;

use MeetMagentoPL\AdminSearch\Model\Index\AbstractItem;

class Item extends AbstractItem
{
    /**
     * @inheritDoc
     */
    public function getAction()
    {
        $action = parent::getAction();
        $action = 'adminhtml/system_config/edit/section/' . $action;

        return $action;
    }
}
