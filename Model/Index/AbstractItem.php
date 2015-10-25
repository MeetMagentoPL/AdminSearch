<?php

namespace MeetMagentoPL\AdminSearch\Model\Index;

class AbstractItem
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Return item parents.
     *
     * @return array
     */
    public function getParents()
    {
        return $this->data['parents'];
    }

    /**
     * Item url action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->data['action'];
    }

    /**
     * Item description.
     *
     * @return string
     */
    public function getDescription()
    {
        $path = array_map('strip_tags', $this->getParents());
        $path = array_map(array($this, 'removeHtmlEntities'), $path);

        return implode(' / ', array_filter($path));
    }

    /**
     * Item label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->data['label'];
    }

    /**
     * Item searchable value.
     *
     * @return string
     */
    public function getDataIndex()
    {
        return $this->data['data_index'];
    }

    /**
     * Remove html entities from string.
     *
     * @param $string
     * @return mixed
     */
    protected function removeHtmlEntities($string)
    {
        return preg_replace('/&#?[a-z0-9]{2,8};/i', '', $string);
    }
}
