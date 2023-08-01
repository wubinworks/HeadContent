/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Ui/js/grid/filters/range'
], function (Range) {
    'use strict';

	return Range.extend({
		/**
		 * For datetime picker
		 * May need further test
		 */
		defaults: {
			templates: {
				datetime: {
					//component: 'Magento_Ui/js/form/element/date',
					//dateFormat: 'MM/dd/YYYY',
					//shiftedValue: 'filter',
					options: {
						dateFormat: 'yyyy-MM-dd',
						timeFormat: 'HH:mm:ss',
						showsTime: true
					},
					/*
					inputDateFormat: 'yyyy-MM-dd HH:mm:ss',
					outputDateFormat: 'yyyy-MM-dd HH:mm:ss',
					//storeTimeZone: 'string',
					timezoneFormat: 'yyyy-MM-dd HH:mm:ss',
					*/
				},
			},
		},
	});
});
