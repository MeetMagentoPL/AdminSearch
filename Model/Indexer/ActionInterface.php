<?php

namespace MeetMagentoPL\AdminSearch\Model\Indexer;

interface ActionInterface
{
    /**
     * Reindex all items.
     *
     * @return void
     */
    public function reindexAll();
}
