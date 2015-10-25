<?php

namespace MeetMagentoPL\AdminSearch\Block;

class Container extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Initialize object state with incoming parameters
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'indexer';
        $this->_blockGroup = 'MeetMagentoPL_AdminSearch';
        $this->_headerText = __('Admin Search Management');
        parent::_construct();
        $this->buttonList->remove('add');
    }
}
