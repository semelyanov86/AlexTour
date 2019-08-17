/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
var VDSimplyKPI_Edit1_Js = {
	
	
	step1Container : false,
	
	
	init : function() {
		this.initialize();
	},
	/**
	 * Function to get the container which holds all the reports step1 elements
	 * @return jQuery object
	 */
	getContainer : function() {
		return this.step1Container;
	},

	/**
	 * Function to set the reports step1 container
	 * @params : element - which represents the reports step1 container
	 * @return : current instance
	 */
	setContainer : function(element) {
		this.step1Container = element;
		return this;
	},
	
	/**
	 * Function  to intialize the reports step1
	 */
	initialize : function(container) {
		if(typeof container == 'undefined') {
			container = jQuery('#VDSimplyKPI_step1');
		}
		if(container.is('#VDSimplyKPI_step1')) {
			this.setContainer(container);
		}else{
			this.setContainer(jQuery('#VDSimplyKPI_step1'));
		}
		
	},
        
	submit : function(){
		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		var form = this.getContainer();
		var formData = form.serializeFormData();
		
		var params = {};
		var kpiName = jQuery.trim(formData.subject);
		var recordId = formData.record;
		
		var progressIndicatorElement = jQuery.progressIndicator({
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});
		
		
				AppConnector.request(formData).then(
					function(data) {
						form.hide();
						progressIndicatorElement.progressIndicator({
							'mode' : 'hide'
						})
						aDeferred.resolve(data);
					},
					function(error,err){

					}
					);
			
			
		return aDeferred.promise();
	},
	
	
	
	registerEvents : function(){
		var container = this.getContainer();
		
		var opts = app.validationEngineOptions;
		// to prevent the page reload after the validation has completed 
		opts['onValidationComplete'] = function(form,valid) {
            //returns the valid status
            return valid;
        };
		opts['promptPosition'] = "bottomRight";
		// container.validationEngine(opts);
		//schedule reports
		
	}
};