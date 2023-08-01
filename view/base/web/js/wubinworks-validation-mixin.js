/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
	'jquery',
	'mage/translate'
], function($) {
    'use strict';
	
	return function(validator) {
		validator.addRule(
			'wubinworks-validate-number-list',
			function(value, element) {
				return /^(\d)+(,(\d)+)*$/.test(value);
			},
			$.mage.__('Please enter a number list like: 2,5,9,16')
        );
		
		validator.addRule(
			'wubinworks-validate-ascii',
			function(value, element) {
				return /^[ -~]*$/.test(value); // allow empty value
			},
			$.mage.__('Please enter ASCII only')
        );
		
		return validator;
    }
});
