/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */
/* Most parts are Local Storage only. */
define([
    'jquery',
    'underscore',
    'ko',
    'Magento_Customer/js/section-config',
    'mage/url',
	'Magento_Customer/js/customer-data',
    'mage/storage',
    'jquery/jquery-storageapi',
	'Wubinworks_InjectHead/js/injecthead'
], function ($, _, ko, sectionConfig, url, customerData) {
    'use strict';

	const INJECTHEAD_SESSION = 'wubinworks_injecthead_session';
    var options = {},
        storage,
		storageNS,
		invalidateCacheByCustomerChange,
        dataProvider,
        buffer,
        localStorageData,
		customerSessionHash,
        deferred = $.Deferred();

	customerSessionHash = function (){
		if($.cookieStorage.isSet(INJECTHEAD_SESSION)){
			return $.cookieStorage.get(INJECTHEAD_SESSION);
		}
		return false;
	}
	
	/**
     * Invalidate Cache By Customer Session Hash
	 * Maybe it is better to use Customer Id
     */
    invalidateCacheByCustomerChange = function (sectionNames) {
		_.each(sectionNames, function(sectionName){
			if(!storage.isSet(sectionName)){
			}
			else if(!storage.isSet(sectionName + '.' + 'customer_session')){
				storage.remove([sectionName]);
			}
			else if(storage.get(sectionName + '.' + 'customer_session') != customerSessionHash()){
				storage.remove([sectionName]);
			}
		});
    };
	
    dataProvider = {
        /**
         * @param {Object} sectionNames
         * @return {Object}
         */
        getFromStorage: function (sectionNames) {
            var result = {};

            _.each(sectionNames, function (sectionName) {
                result[sectionName] = storage.get(sectionName + '.' + options.identity);
            });

            return result;
        },

        /**
         * @param {Object} sectionNames
         * @param {Boolean} forceNewSectionTimestamp
         * @return {*}
         */
        getFromServer: function (sectionNames, forceNewSectionTimestamp) {
            sectionNames = sectionConfig.filterClientSideSections(sectionNames);
			var parameters = {
				referer: options.referer,
				refererFullActionName: options.refererFullActionName,
			};
            parameters['force_new_section_timestamp'] = forceNewSectionTimestamp;

			return $.ajax({
				//async: false,
				method: 'GET',
				dataType: "json",
				url: options.sectionLoadUrl,
				data: parameters,
				processData: true
			}).fail(function (jqXHR) {
                throw new Error(jqXHR);
            });
			// done is not here
        }
    };

    buffer = {
        data: {},

        /**
         * @param {String} sectionName
         */
        bind: function (sectionName) {
            this.data[sectionName] = ko.observable({});
        },

        /**
         * @param {String} sectionName
         * @return {Object}
         */
        get: function (sectionName) {
            if (!this.data[sectionName]) {
                this.bind(sectionName);
            }

            return this.data[sectionName];
        },

        /**
         * @return {Array}
         */
        keys: function () {
            return _.keys(this.data);
        },

        /**
         * @param {String} sectionName
         * @param {Object} sectionData
         */
        notify: function (sectionName, sectionData) {
            if (!this.data[sectionName]) {
                this.bind(sectionName);
            }
            this.data[sectionName](sectionData);
        },

        /**
         * @param {Object} sections
         */
        update: function (sections) {
            _.each(sections, function (sectionData, sectionName) {
                storage.set(sectionName + '.' + options.identity, sectionData);
                buffer.notify(sectionName, sectionData);
				storage.set(sectionName + '.' + 'customer_session', customerSessionHash());
				storage.set(sectionName + '.' + 'version_number', options.versionNumber);
            });
        },

        /**
         * @param {Object} sections
         */
        remove: function (sections) {
            _.each(sections, function (sectionName) {
                storage.remove(sectionName + '.' + options.identity);
            });
        }
    };

    localStorageData = {
        /**
         * Customer data initialization
         */
        init: function () {
            var expiredSectionNames = this.getExpiredSectionNames();
            if (expiredSectionNames.length > 0) {
				this.reload(expiredSectionNames, true)
					.done(function (sections) {
						_.each(sections, function (sectionData) {
							if(sectionData.data){
								$(document).trigger('wubinworks-injectionhead-prepared', [sectionData.data]);
							}
						});
					}.bind(this));
            } else {
                _.each(dataProvider.getFromStorage(storage.keys()), function (sectionData, sectionName) {
                    buffer.notify(sectionName, sectionData);
					if(sectionData.data){
						$(document).trigger('wubinworks-injectionhead-prepared', [sectionData.data]);
					}
                });
				
            }
        },

        /**
         * Storage init
         */
        initStorage: function () {
			storageNS = $.initNamespaceStorage('wubinworks-cache-storage');
            storage = storageNS.localStorage;
        },

        /**
         * Retrieve the list of sections that has expired since last page reload.
         *
         * Sections can expire due to lifetime constraints or due to inconsistent storage information
         * (validated by cookie data).
         *
         * @return {Array}
         */
        getExpiredSectionNames: function () {
            var expiredSectionNames = [],
				expirableSectionNames = options.sectionNames,
                sectionLifetime = options.maxClientDataCacheLifetime,
                currentTimestamp = Math.floor(Date.now() / 1000),
                sectionData;

            // process sections that can expire due to lifetime constraints
            _.each(expirableSectionNames, function (sectionName) {
				if(storage.isSet(sectionName + '.' + options.identity)){
					sectionData = storage.get(sectionName + '.' + options.identity);
				}

                if (!_.isObject(sectionData) ||
					// expire_before can be not existing
					(sectionData['expire_before'] != undefined && sectionData['expire_before'] <= currentTimestamp) ||
					// maxClientDataCacheLifetime
					(sectionData['data_id'] === undefined || sectionData['data_id'] + sectionLifetime <= currentTimestamp) ||
					// maxClientUnsuccessDataCacheLifetime
					(sectionData['code'] === undefined || (sectionData['code'] > 0 && currentTimestamp - sectionData['data_id'] > options.maxClientUnsuccessDataCacheLifetime)) ||
					(!storage.isSet(sectionName + '.' + 'version_number') || storage.get(sectionName + '.' + 'version_number') != options.versionNumber)
				) {
                    expiredSectionNames.push(sectionName);
                }
            });

            return _.uniq(expiredSectionNames);
        },

        /**
         * @param {String} sectionName
         * @return {*}
         */
        get: function (sectionName) {
            return buffer.get(sectionName);
        },

        /**
         * @param {String} sectionName
         * @param {Object} sectionData
         */
        set: function (sectionName, sectionData) {
            var data = {};

            data[sectionName] = sectionData;
            buffer.update(data);
        },

        /**
         * Avoid using this function directly 'cause of possible performance drawbacks.
         * Each customer section reload brings new non-cached ajax request.
         *
         * @param {Array} sectionNames
         * @param {Boolean} forceNewSectionTimestamp
         * @return {*}
         */
        reload: function (sectionNames, forceNewSectionTimestamp) {
            return dataProvider.getFromServer(sectionNames, forceNewSectionTimestamp)
				.done(function (sections) {
					$(document).trigger('wubinworks-customer-data-reloaded', [sectionNames]);
					buffer.update(sections);
            });
        },

        /**
         * @param {Array} sectionNames
         */
        invalidate: function (sectionNames) {
            $(document).trigger('wubinworks-customer-data-invalidate', [sectionNames]);
            buffer.remove(sectionNames);
			//storage.removeAll();
        },

        /**
         * Checks if customer data is initialized.
         *
         * @returns {jQuery.Deferred}
         */
        getInitCustomerData: function () {
            return deferred.promise();
        },

        /**
         * Reload sections on ajax complete
         *
         * @param {Object} jsonResponse
         * @param {Object} settings
         */
        onAjaxComplete: function (jsonResponse, settings) {
            var sectionNames,
                redirects;

            if (settings.type.match(/post|put|delete/i)) {
                if (this.needInvalidate(settings.url)) {
					sectionNames = options.sectionNames;
                    this.invalidate(sectionNames);
                    redirects = ['redirect', 'backUrl'];

                    if (_.isObject(jsonResponse) && !_.isEmpty(_.pick(jsonResponse, redirects))) { //eslint-disable-line
                        return;
                    }
                    this.reload(sectionNames, true);
                }
            }
        },

        /**
         * @param {Object} settings
         * @constructor
         */
        'Wubinworks_InjectHead/js/customer-data': function (settings) {
            options = settings;
			options.postActions = [
				//'catalog/product_compare/add', // For test
				//'stores/store/switch',
				//'stores/store/switchrequest',
				//'directory/currency/switch',
				'customer/account/logout',
				'customer/account/loginPost',
				'customer/account/createPost',
				//'customer/account/editPost',
				'customer/ajax/login',
				'customer/ajax/logout'
			];
			options.identity = 'PI_' + options.identity;
            localStorageData.initStorage();
			invalidateCacheByCustomerChange(options.sectionNames);
            localStorageData.init();
            deferred.resolve();
        },
		
		/**
         * @param {String} url
		 * @param {String} fullActionName
         */
        urlMatch: function (url, fullActionName) {
			var re = new RegExp('^' + '(' + window.location.origin + ')?' +  '/' + fullActionName + '($|\\?|/)');
			//console.log(re);
			return url.match(re);
        },
		
		/**
         * @param {String} url
         */
        needInvalidate: function (url) {
			return !_.every(options.postActions, function(fullActionName){
				return !this.urlMatch(url, fullActionName);
			}.bind(this));
        },
		
		/**
         * @param {String} url
         */
        storeSwitch: function (url) {
			if(this.urlMatch(url, 'stores/store/redirect')){
				this.invalidate(options.sectionNames);
			}
        }
    };

    /**
     * Events listener
     */
    $(document).on('ajaxComplete', function (event, xhr, settings) {
        localStorageData.onAjaxComplete(xhr.responseJSON, settings);
    });

    /**
     * Events listener
     */
    $(document).on('submit', function (event) {
        var sections;

        if (event.target.method.match(/post|put|delete/i)) {
            if (localStorageData.needInvalidate(event.target.action)) {
				sections = options.sectionNames;
                localStorageData.invalidate(sections);
            }
        }
    });

    return localStorageData;
});
