<?php

namespace MeetMagentoPL\AdminSearch\Model\Indexer\Config;

use Magento\Config\Model\Config\Structure;
use Magento\Framework\Filter\SplitWords;

class Processor
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var
     */
    private $configStructure;

    /**
     * @var SplitWords
     */
    private $splitWordsFilter;

    /**
     * @param Structure $configStructure
     * @param SplitWords $splitWordsFilter
     */
    public function __construct(Structure $configStructure, SplitWords $splitWordsFilter)
    {
        $this->configStructure = $configStructure;
        $this->splitWordsFilter = $splitWordsFilter;
    }

    /**
     * Process configuration.
     *
     * @return array
     */
    public function process()
    {
        foreach ($this->configStructure->getTabs() as $tab) {
            $this->processTab($tab);
        }

        return $this->data;
    }

    /**
     * @param Structure\Element\Tab $tab
     */
    private function processTab(Structure\Element\Tab $tab)
    {
        foreach ($tab->getChildren() as $section) {
            $this->processSection($section, $tab);
        }
    }

    /**
     * @param Structure\Element\Section $section
     * @param Structure\Element\Tab $tab
     */
    private function processSection(
        Structure\Element\Section $section,
        Structure\Element\Tab $tab
    )
    {
        foreach ($section->getChildren() as $group) {
            $this->processGroup($group, $section, $tab);
        }
    }

    /**
     * @param Structure\Element\Group $group
     * @param Structure\Element\Section $section
     * @param Structure\Element\Tab $tab
     */
    private function processGroup(
        Structure\Element\Group $group,
        Structure\Element\Section $section,
        Structure\Element\Tab $tab
    )
    {
        foreach ($group->getChildren() as $element) {
            if ($element instanceof Structure\Element\Group) {
                $this->processGroup($element, $section, $tab);
            } else {
                $this->processField($element, $group, $section, $tab);
            }
        }
    }

    /**
     * @param Structure\Element\Field $field
     * @param Structure\Element\Group $group
     * @param Structure\Element\Section $section
     * @param Structure\Element\Tab $tab
     */
    private function processField(
        Structure\Element\Field $field,
        Structure\Element\Group $group,
        Structure\Element\Section $section,
        Structure\Element\Tab $tab
    )
    {
        if (!$field->getLabel()) {
            return;
        }

        $words = $this->splitWordsFilter->filter($field->getLabel());
        foreach ($words as $word) {
            if (mb_strlen($word) < 2) {
                continue;
            }
            $this->data[] = [
                'label' => $field->getLabel(),
                'data_index' => $word,
                'action' => $section->getId(),
                'parents' => [$tab->getLabel(), $section->getLabel(), $group->getLabel()],
            ];
        }
    }
}
