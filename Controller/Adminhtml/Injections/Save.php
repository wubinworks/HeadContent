<?php
/**
 * TODO: need to improve data processing logic
 *
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Controller\Adminhtml\Injections;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
	/**
	 * Data persistor
	 * @var \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
	 */
    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('injections_id');
            if(isset($data['store_ids']) && is_array($data['store_ids'])) {
                if(in_array(0, $data['store_ids'])) {
                    $data['store_ids'] = '0';
                } else {
                    $data['store_ids'] = implode(',', $data['store_ids']);
                }
            }
            if(isset($data['customer_groups']) && is_array($data['customer_groups'])) {
                if(in_array('all', $data['customer_groups'])) {
                    $data['customer_groups'] = \Wubinworks\InjectHead\Ui\Component\Listing\Column\CustomerGroups::ALL;
                } else {
                    $data['customer_groups'] = implode(',', $data['customer_groups']);
                }
            }
            //var_dump($data['start_datetime']);die;
            /*
			TODO:
            Use Wubinworks_Base Validation Helper to check datetime
			Check match_mode, uri_pattern, full_action_name, content
			*/
            $model = $this->_objectManager->create(\Wubinworks\InjectHead\Model\Injections::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Rule no longer exists'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Rule'));
                //$this->messageManager->addWarningMessage(__('partially saved'));
                $this->dataPersistor->clear('wubinworks_injecthead_injections');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['injections_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('This rule was failed to save for unknown reasons. Please check the exception log'));
            }

            $this->dataPersistor->set('wubinworks_injecthead_injections', $data);
            return $resultRedirect->setPath('*/*/edit', ['injections_id' => $this->getRequest()->getParam('injections_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
