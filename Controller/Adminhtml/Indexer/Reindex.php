<?php

namespace MeetMagentoPL\AdminSearch\Controller\Adminhtml\Indexer;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;
use MeetMagentoPL\AdminSearch\Controller\Adminhtml\AbstractAction;
use MeetMagentoPL\AdminSearch\Model\Config;
use MeetMagentoPL\AdminSearch\Model\IndexerFactory;

class Reindex extends AbstractAction
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var IndexerFactory
     */
    private $indexerFactory;

    /**
     * @param Context $context
     * @param Config $config
     * @param IndexerFactory $indexerFactory
     */
    public function __construct(
        Context $context,
        Config $config,
        IndexerFactory $indexerFactory
    )
    {
        parent::__construct($context);

        $this->config = $config;
        $this->indexerFactory = $indexerFactory;
    }

    /**
     * Mass action for cache refresh
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        try {
            $selected = $this->getRequest()->getParam('indexers');
            $indexers = $this->config->getIndexers();
            $updatedIndexers = 0;
            foreach ($selected as $indexerId) {
                if (isset($indexers[$indexerId])) {
                    $indexer = $this->indexerFactory->create();
                    $indexer->load($indexerId);
                    if ($indexer->getIndexerId()) {
                        $indexer->reindexAll();
                        ++$updatedIndexers;
                    }
                }
            }
            if ($updatedIndexers) {
                $this->messageManager->addSuccess(__("%1 index(es) rebuilt.", $updatedIndexers));
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('An error occurred while reindexing.'));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('adminsearch/indexer/list');
    }
}
