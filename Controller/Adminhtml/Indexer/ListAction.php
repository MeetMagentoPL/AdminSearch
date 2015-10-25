<?php

namespace MeetMagentoPL\AdminSearch\Controller\Adminhtml\Indexer;

use MeetMagentoPL\AdminSearch\Controller\Adminhtml\AbstractAction;

class ListAction extends AbstractAction
{
    /**
     * Display processes grid action
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('MeetMagentoPL_AdminSearch::system_adminsearch');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Admin Search Management'));
        $this->_view->renderLayout();
    }
}
