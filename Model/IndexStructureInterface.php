<?php

namespace MeetMagentoPL\AdminSearch\Model;

interface IndexStructureInterface
{
    /**
     * Delete index table.
     *
     * @param $name
     */
    public function delete($name);

    /**
     * Create index table.
     *
     * @param $name
     * @throws \Zend_Db_Exception
     */
    public function create($name);
}
