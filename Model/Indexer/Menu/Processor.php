<?php

namespace MeetMagentoPL\AdminSearch\Model\Indexer\Menu;

use Magento\Backend\Model\Menu;
use Magento\Framework\Filter\SplitWords;

class Processor
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Menu\Config
     */
    private $menuConfig;

    /**
     * @var SplitWords
     */
    private $splitWordsFilter;

    /**
     * @param Menu\Config $menuConfig
     * @param SplitWords $splitWordsFilter
     */
    public function __construct(Menu\Config $menuConfig, SplitWords $splitWordsFilter)
    {
        $this->menuConfig = $menuConfig;
        $this->splitWordsFilter = $splitWordsFilter;
    }

    /**
     * Process configuration.
     *
     * @return array
     */
    public function process()
    {
        $root = $this->menuConfig->getMenu();
        $this->processMenu($root);

        return $this->data;
    }

    /**
     * Process single menu level.
     *
     * @param Menu $menu
     * @param array $parents
     * @return array
     */
    protected function processMenu(Menu $menu, $parents = [])
    {
        foreach ($menu as $menuItem) {
            if ($menuItem->getAction()) {
                $words = $this->splitWordsFilter->filter($menuItem->getTitle());
                foreach ($words as $word) {
                    if (mb_strlen($word) < 2) {
                        continue;
                    }
                    $this->data[] = [
                        'label' => $menuItem->getTitle(),
                        'data_index' => $word,
                        'parents' => $parents,
                        'action' => $menuItem->getAction(),
                    ];
                }
            } else {
                $parents[] = $menuItem->getTitle();
            }
            if ($menuItem->hasChildren()) {
                $this->processMenu($menuItem->getChildren(), $parents);
                array_pop($parents);
            }
        }

        return $this->data;
    }
}
