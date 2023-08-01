/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
	'jquery',
	'underscore',
	'ko'
], function ($, _, ko) {
	'use strict';
	
	var InjectHead = {
		/**
		 * Init listener
         * @return {Object} Chainable
         */
    	init: function () {
			$(document).on('wubinworks-injectionhead-prepared', function(event, data){
				this.execute(data);
			}.bind(this));
			return this;
    	},
		
		/**
         * @param {Object} data ie: sectionData
         * @return {Object} Chainable
         */
		execute: function(data){
			if(_.isObject(data)){
				var tag, tagName, i, attrName;
				for(tagName in data){
					for(i in data[tagName]){
						tag = document.createElement(tagName);
						if(!_.isEmpty(data[tagName][i].attr)){
							for(attrName in data[tagName][i].attr){
								tag.setAttribute(attrName, data[tagName][i].attr[attrName]);
							}
						}
						if(data[tagName][i].innertext.length){
							tag.innerHTML = data[tagName][i].innertext;
						}
						document.head.appendChild(tag);
					}
				}
			}
			return this;
		}
	};
	
	InjectHead.init();
	return InjectHead;
});
