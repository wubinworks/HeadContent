<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Controller\Adminhtml\Injections;

use Magento\Framework\Exception\LocalizedException;

class Duplicate extends \Magento\Backend\App\Action
{
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Duplicate action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('injections_id', null);
        $multiplier = $this->getRequest()->getParam('multiplier', null);
        if ($id && $multiplier && $multiplier > 0) {
            $model = $this->_objectManager->create(\Wubinworks\InjectHead\Model\Injections::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This rule no longer exists'));
                return $resultRedirect->setPath('*/*/');
            }
            for($i = 0; $i < $multiplier; $i++) {
                $model->setData('injections_id', null);
                $model->setData('enabled', false);
                try {
                    $model->save();
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                } catch (\Exception $e) {
                    $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the rule'));
                }
            }
            $this->messageManager->addSuccessMessage(__('Rule "%1" duplicated %2 time(s) successfully', $id, $multiplier));
        } else {
            $this->messageManager->addErrorMessage(__('Parameters Missing or Incorrect'));
        }
        return $resultRedirect->setPath('*/*/');
    }
}
