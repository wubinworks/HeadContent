/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'underscore',
	'uiRegistry',
	'Magento_Ui/js/form/components/html',
	'Magento_Ui/js/lib/view/utils/dom-observer',
    'CodeMirror',
    'CodeMirrorHtmlmixed'
], function ($, _, uiRegistry, Html, $do, CodeMirror) {
    'use strict';

	var editor;
	
	return Html.extend({
		defaults: {
            actualInputField: '${ $.ns }.${ $.ns }.general.content',
			listens: {
				'${ $.provider }:data.reset': 'onContentReset'
			}
        },
		
        /**
         * Extends instance with default config, calls 'initialize' method of parent
		 * Init CodeMitrror
		 *
		 * @return {Object} - reference to instance
         */
        initialize: function () {
            this._super();
			$do.get('.html_editor', function(elem){
				this.initEditor();
			}.bind(this));
            return this;
        },
		
		/**
         * Init editor and do extra things
         *
         * @return {Object} - reference to instance
         */
		initEditor: function (){
			editor = CodeMirror.fromTextArea($('.html_editor')[0], {
				lineNumbers:     true,
				matchBrackets:   true,
				mode:            "htmlmixed",
				indentUnit:      4,
				indentWithTabs:  false,
				viewportMargin:  Infinity,
				styleActiveLine: true,
				tabSize:         4,
				theme:           "solarized light",
				readOnly:        false
				});
			$(editor.getWrapperElement()).css({
				'font-weight': 600
			});
			// Editor -> input
			editor.on('change',function(editor){
				uiRegistry.get(this.actualInputField).value(editor.getValue());
			}.bind(this));
			$($('.html_editor')[0]).css('display', '');
			return this;
		},
		
		/**
         * @return {(Object|undefined)} - the CodeMirror Editor instance
         */
		getEditor: function (){
			return editor;
		},
		
		/**
         * Init editor and do extra things
         *
         * @return {Object} - reference to instance
         */
		onContentReset: function (){
			// Input -> editor
			uiRegistry.get(this.actualInputField).reset(); // Do this first
			editor.setValue(uiRegistry.get(this.actualInputField).value());
			return this;
		}
	});
});
