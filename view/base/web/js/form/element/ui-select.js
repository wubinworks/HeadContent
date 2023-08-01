/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'Magento_Ui/js/form/element/ui-select',
    'jquery',
    'underscore'
], function (Select, $, _) {
    'use strict';

    return Select.extend({
        /**
         * Toggle activity list element
         *
         * @param {Object} data - selected option data
         * @returns {Object} Chainable
         */
        toggleOptionSelected: function (data) {
            var isSelected = this.isSelected(data.value);

            if (this.lastSelectable && data.hasOwnProperty(this.separator)) {
                return this;
            }

            if (!this.multiple) {
                if (!isSelected) {
                    this.value(data.value);
                }
                this.listVisible(false);
            } else {
                if (!isSelected) { /*eslint no-lonely-if: 0*/
					if(this.name === this.parentName + '.' + 'enabled' ||
						this.name === this.parentName + '.' + 'match_mode'){
						this.value.removeAll();
					}
					else if(this.name === this.parentName + '.' + 'store_ids' ||
						this.name === this.parentName + '.' + 'store_ids' + '_' + 'partial'){
						if(data.value === '0'){
							this.value.removeAll();
						}
						else{
							this.value.remove('0');
						}
					}
					else if(this.name === this.parentName + '.' + 'customer_groups' ||
						this.name === this.parentName + '.' + 'customer_groups' + '_' + 'partial'){
						if(data.value === 'all'){
							this.value.removeAll();
						}
						else{
							this.value.remove('all');
						}
					}
					
                    this.value.push(data.value);
                } else {
                    this.value(_.without(this.value(), data.value));
                }
            }

            return this;
        }
    });
});
