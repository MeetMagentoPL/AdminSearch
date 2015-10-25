<?php

namespace MeetMagentoPL\AdminSearch\Block\Indexer\Grid\Column\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\Datetime;
use Magento\Framework\DataObject;

class Updated extends Datetime
{
    /**
     * Render indexer updated time
     *
     * @param DataObject $row
     * @return \Magento\Framework\Phrase|string
     */
    public function render(DataObject $row)
    {
        $value = parent::render($row);
        if (!$value) {
            return __('Never');
        }

        return $value;
    }
}
