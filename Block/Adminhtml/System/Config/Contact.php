<?php
/**
 * Copyright © Wubinworks. All rights reserved.
 * See LICENSE.txt for license details.
 */
declare(strict_types=1);

namespace Wubinworks\InjectHead\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Contact extends \Wubinworks\InjectHead\Block\Adminhtml\System\Config\AbstractField
{
    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Remove scope label
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        $element->setLabel('<div class="wubinworks-email-icon"></div>');
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
		$content = __('Email address has been copied to clipboard!');
		$copyEmailJs =
<<<COPYEMAIL
<script>
	require([
        'jquery',
		'Magento_Ui/js/lib/view/utils/dom-observer',
		'Magento_Ui/js/modal/alert'
    ], function ($, \$do, Alert) {
		'use strict';
		
		\$do.get('#copy-email', function(elem){
			$('#copy-email').on('click', function(e){
				var email = '{$this->getWubinworksEmail()}';
				if(navigator.clipboard == undefined){
					window.clipboardData.setData('Text', email);
				}
				else{
					navigator.clipboard.writeText(email);
				}
				Alert({
					title: '\\u2713',
					content: '{$content}',
					modalClass: 'confirm wubinworks-copy-email-alert',
					actions: {
						always: function() {
						}
					},
					clickableOverlay: true,
					focus: '.focus-ok',
					buttons: [{
						text: $.mage.__('OK'),
						class: 'action secondary accept focus-ok',

						/**
						 * Click handler.
						 */
						click: function () {
							this.closeModal(true);
						}
					}]
				});
				e.preventDefault();
				return false;
			});
			$('#copy-email').attr('disabled', false);
        });
	});
</script>
COPYEMAIL;

		$dropdownDlgJs =
<<<DROPDOWNDLG
<div class="wubinworks-like">
<span data-bind="i18n: 'Like this extension?'" style="margin-right: 20px;"></span>
<button type="button" class="primary action" data-trigger="popup-modal">
    <span data-bind="i18n: 'Support Author'"></span>
</button>
<div data-bind="mageInit: {
        'Magento_Ui/js/modal/modal':{
            'type': 'popup',
            'title': jQuery.mage.__('Support Author'),
            'trigger': '[data-trigger=popup-modal]',
			'modalClass': 'wubinworks-copy-email-alert overlay-bugfix',
			'focus': 'none',
            'responsive': true,
            /*'buttons': [
				{
					text: jQuery.mage.__('Submit'),
					class: 'action secondary accept',
					click: function(){console.log('Clicked')}
				},
				{
					text: jQuery.mage.__('Cancel'),
					class: 'action',
					click: function(){console.log('Canceled');this.closeModal();}
				}
			]*/
			'buttons': [],
        }}">
    <div class="wubinworks-like-popup-content">
        <span data-bind="i18n: 'Buy Me A Beer!'"></span><span data-bind="i18n: '(Paypal)'"></span><br />
		<a class="wubinworks-external-link" href="{$this->escapeHtmlAttr($this->getPaypalMeUrl())}" target="_blank" rel="noopener noreferrer">{$this->escapeHtml($this->getPaypalMeUrl())}</a>
    </div>
</div>
</div>
DROPDOWNDLG;

        return '<div class="wubinworks-contact">' .
			__(
				'Report issues and bugs <a class="wubinworks-external-link" href="%1" target="_blank" rel="noopener noreferrer">here</a>',
				$this->helper->getProjectIssueUrl()
			) . '<br /><br />' .
            __('Our team is offering Magento custom development service. If you require any kind of store customization, tailor-made extensions or EC technical consulting service, please feel free to reach out to us via the email below') . '<br />' .
            __('Supported languages') . ': ' . __('English') . ', ' . __('Japanese') . '<br /><br />' .
            __('<a class="wubinworks-external-link" href="%1" target="_blank" rel="noopener noreferrer">Privacy Policy</a>', $this->helper->getHomeUrl() . '/tree/master/privacy-policy') . '<br /><br />' .
            '<div class="wubinworks-email">' .
            '<span class="label">' . __('Email') . '</span>' . '<a id="wubinworks-email-address" target="_blank" href="mailto:' . $this->getMailto() . '">' . $this->getWubinworksEmail() . '</a>' .
            '<span class="wubinworks-copy-email-button">' . '<button disabled="disabled" type="button" class="secondary" id="copy-email">' . __('Copy Email') . '</button>' . '</span>' .
			'</div>' .
			'</div>' .
			$copyEmailJs . $dropdownDlgJs;
    }

	/**
     *
     * @return string
     */
    private function getPaypalMeUrl()
    {
        return base64_decode('aHR0cHM6Ly9wYXlwYWwubWUvd3ViaW53b3Jrcw==', true);
    }
	
    /**
     *
     * @return string
     */
    private function getWubinworksEmail()
    {
        return base64_decode('d3ViaW53b3Jrc0BvdXRsb29rLmNvbQ==', true);
    }

    /**
     *
     * @return string
     */
    private function getMailto()
    {
        return $this->getWubinworksEmail() . '?subject=' . $this->getSubject() . '&body=' . $this->getBody();
    }

    /**
     *
     * @return string
     */
    private function getSubject()
    {
        return rawurlencode(__('[Your Name/Company Name]') . __('Custom Development'));
    }

    /**
     *
     * @return string
     */
    private function getBody()
    {
        $lines = [
            [
                'label' => '',
                'value' => 'Wubinworks'
            ],
            [
                'label' => '',
                'value' => ''
            ],
            [
                'label' => '',
                'value' => ''
            ],
            [
                'label' => '',
                'value' => __('[Your decision whether to provide the information below to us and you can remove any parts you do not want to provide. Howerver, we suggest you to include them for an effective inquiry. The content of this email will not be shared to any third parties. You can also confirm our Privacy Policy]')
            ],
            [
                'label' => __('Store Name') . ': ',
                'value' => $this->helper->getStoreName()
            ],
            [
                'label' => __('Country of Store') . ': ',
                'value' => $this->helper->getStoreCountry() . '(' . $this->helper->getStoreCountry(true) . ')'
            ],
            [
                'label' => __('URL') . ': ',
                'value' => $this->helper->getUnsecureBaseUrl()
            ],
            [
                'label' => __('Magento Version') . ': ',
                'value' => $this->helper->getMagentoVersion()
            ],
            [
                'label' => __('Wubinworks_InjectHead Version') . ': ',
                'value' => $this->helper->getModuleSetupVersion()
            ],
        ];
        $body = '';
        foreach($lines as $line) {
            $body .= $line['label'] . $line['value'] . "\r\n";
        }
        $body = rtrim($body, "\r\n");
        return rawurlencode($body);
    }
}
