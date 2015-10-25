<?php

namespace MeetMagentoPL\AdminSearch\Model;

interface IndexHandlerInterface
{
    /**
     * Save data to index.
     *
     * @param $items
     */
    public function saveIndex($items);

    /**
     * Clean index table.
     */
    public function cleanIndex();

    /**
     * Search index data.
     *
     * @param $query
     * @param $limit
     * @param $page
     * @return array
     */
    public function searchIndex($query, $limit, $page);
}
