/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
	'underscore',
    'mageUtils',
    'Magento_Ui/js/modal/alert',
	'Magento_Ui/js/grid/filters/filters'
], function ($, _, utils, $alert, Filters) {
    'use strict';

	/**
     * Removes empty properties from the provided object.
     *
     * @param {Object} data - Object to be processed.
     * @returns {Object}
     */
    function removeEmpty(data) {
        var result = utils.mapRecursive(data, utils.removeEmptyValues.bind(utils));

        return utils.mapRecursive(result, function (value) {
            return _.isString(value) ? value.trim() : value;
        });
    }
	
	return Filters.extend({
		/**
         * Sets filters data to the applied state.
         *
         * @returns {Filters} Chainable.
         */
        apply: function () {
			if(this.filters['store_ids']){
				var value = this.filters['store_ids'];
				if(Array.isArray(value)){
					value = value.join();
				}
				value = value.replace(/\s/g, '');
				if(value.length && !value.match(/^\d+(,\d+)*$/)){
					$alert({
						title: $.mage.__('Filters Wrong format'),
						content: $.mage.__('Store IDs "%1" is in wrong format.').replace('%1', this.filters['store_ids'])
							+ '<br />' + $.mage.__('It should be a number list like "2,4,5"')
					});
					return;
				}
			}
            $('body').notification('clear');
            this.set('applied', removeEmpty(this.filters));

            return this;
        }
	});
});
