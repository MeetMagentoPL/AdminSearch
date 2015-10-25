<?php

namespace MeetMagentoPL\AdminSearch\Controller\Adminhtml;

abstract class AbstractAction extends \Magento\Backend\App\Action
{
    /**
     * Check ACL permissions
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        switch ($this->_request->getActionName()) {
            case 'list':
            case 'reindex':
                return $this->_authorization->isAllowed('MeetMagentoPL_AdminSearch::index');
        }
        return false;
    }
}
