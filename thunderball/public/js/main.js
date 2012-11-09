$.datepicker.regional['de'] = {clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
                closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
                prevText: '<zurück', prevStatus: 'letzten Monat zeigen',
                nextText: 'Vor>', nextStatus: 'nächsten Monat zeigen',
                currentText: 'heute', currentStatus: '',
                monthNames: ['Januar','Februar','März','April','Mai','Juni',
                'Juli','August','September','Oktober','November','Dezember'],
                monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
                'Jul','Aug','Sep','Okt','Nov','Dez'],
                monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
                weekHeader: 'Wo', weekStatus: 'Woche des Monats',
                dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
                dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
                dateFormat: 'DD, dd.mm.yy', firstDay: 1,
                initStatus: 'Wähle ein Datum', isRTL: false};
$.datepicker.setDefaults($.datepicker.regional['de']);

showPendingMessageFunction = function() {
	
};

hidePendingMessageFunction = function() {
	
};

showErrorMessageFunction = function() {
	
};

/*
 * Normaler ajax Post request (asynchronously)
 */
function postRequest(controller, action, params, onStart, onSuccess, onComplete) {
	onStart();
	
	$.ajax({
		  type: 'POST',
		  url: baseUrl + '/' + controller + '/' + action,
		  data: params,
		  success: onSuccess,
		  error : showErrorMessageFunction,
		  complete : onComplete,
		  dataType: 'json'
		});
}

/*
 * Normaler ajax Post request (synchronous)
 */
function postRequestSync(controller, action, params, onStart, onSuccess, onComplete) {
	onStart();
	
	$.ajax({
		  type: 'POST',
		  url: baseUrl + '/' + controller + '/' + action,
		  async: false,
		  data: params,
		  success: onSuccess,
		  error : showErrorMessageFunction,
		  complete : onComplete,
		  dataType: 'json'
		});
}

/*
 * macht einen einfachen post request in messages wird einfach nur "bitte
 * warten..." und "fehler" verwendet
 */
function ajax(controller, action, params, onSuccess) {
	var successFunction = function(data) {
		showSuccessMessageFunction();
		onSuccess(data);
	};
	
	postRequest(controller, action, params, showPendingMessageFunction, successFunction, hidePendingMessageFunction);
}

/*
 * macht einen post request gibt rückmeldung wenn speicherung erfolgreich war.
 */
function post(controller, action, params, onSuccess) {
	postRequest(controller, action, params, showPendingMessageFunction, onSuccess, hidePendingMessageFunction);
}

/*
 * macht einen post request (synchronous) gibt rückmeldung wenn speicherung
 * erfolgreich war.
 */
function postSync(controller, action, params, onSuccess) {
	postRequestSync(controller, action, params, showPendingMessageFunction, onSuccess, hidePendingMessageFunction);
}

$(function() {
	
	$('a.login-window').click(function() {
		
        //Getting the variable's value from a link 
var loginBox = $(this).attr('href');

//Fade in the Popup
$(loginBox).fadeIn(300);

//Set the center alignment padding + border see css style
var popMargTop = ($(loginBox).height() + 24) / 2; 
var popMargLeft = ($(loginBox).width() + 24) / 2; 

$(loginBox).css({ 
	'margin-top' : -popMargTop,
	'margin-left' : -popMargLeft
});

// Add the mask to body
$('body').append('<div id="mask"></div>');
$('#mask').fadeIn(300);

return false;
});

// When clicking on the button close or the mask layer the popup closed
$('a.close, #mask').live('click', function() { 
$('#mask , .login-popup').fadeOut(300 , function() {
$('#mask').remove();  
}); 
return false;
});
	
	
$.fn.dataTableExt.oApi.fnReloadAjax = function(oSettings, sNewSource,
		fnCallback, bStandingRedraw) {
	if (typeof sNewSource != 'undefined' && sNewSource != null) {
		oSettings.sAjaxSource = sNewSource;
	}
	this.oApi._fnProcessingDisplay(oSettings, true);
	var that = this;
	var iStart = oSettings._iDisplayStart;

	oSettings.fnServerData(oSettings.sAjaxSource, [], function(json) {
		/* Clear the old information from the table */
		that.oApi._fnClearTable(oSettings);

		/* Got the data - add it to the table */
		for ( var i = 0; i < json.aaData.length; i++) {
			that.oApi._fnAddData(oSettings, json.aaData[i]);
		}

		oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		that.fnDraw();

		if (typeof bStandingRedraw != 'undefined'
				&& bStandingRedraw === true) {
			oSettings._iDisplayStart = iStart;
			that.fnDraw(false);
		}

		that.oApi._fnProcessingDisplay(oSettings, false);

		/* Callback user function - for event handlers etc */
		if (typeof fnCallback == 'function' && fnCallback != null) {
			fnCallback(oSettings);
		}
	}, oSettings);
};
});