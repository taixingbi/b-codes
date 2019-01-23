/*
 * Basic implementation of methods needed to receive swiped credit card data with javascript.
 * 
 * NOTE: PCI compliance specifies that card data must never be stored in an unencrypted manner, and
 * only certain pieces of card data can be stored persistently.  Ensure that output logging is NOT
 * stored persistently when using this file, as it contains console.log messages that are intended
 * to educate the user, and these messages contain data that may compromise your PCI compliance posture.
 *
 * If you choose to use any of this code with real credit card data, it is your responsibility 
 * to remove all log statements, output, or other code that may serve to persist offending information.
 *
 * Author: Matt Rothstein (http://github.com/marothstein)
 * Contributors: David Wang (https://github.com/davidawang)
 */

// String buffer to store characters from the swipe
var swipe_buffer = "";
// Global keypress timeout to differentiate between typing and swiping
var swipe_timeout = null;
// This governs the maximum number of milliseconds allowed between keypresses for the input to be tested as part of a swipe
var SWIPE_TIMEOUT_MS = 100;

// POPULATE THESE FIELDS WITH YOUR FORM FIELD NAMES
var cc_number_field_id = "cc_number";
var cc_exp_month_field_id = "cc_exp_month";
var cc_exp_year_field_id = "cc_exp_year";

var addListeners = function() {
  $('body').keypress(function(evt) {
    keyPressed(evt);
  });
};

var deleteListeners = function() {
  $('body').unbind("keypress");
};

var addKeyToSwipeBuffer = function(keyCode, keyChar) {
  if (swipe_buffer == null) {
    swipe_buffer = "";
  }
  swipe_buffer += keyChar;
  // console.log('keyChar: '+swipe_buffer);
};

var clearSwipeBuffer = function() {
    deleteListeners();
  // clear the memory
  delete swipe_buffer;

  swipe_buffer = null;
};

var keyPressed = function(event) {
  // console.log("[keyPressed] character: " + String.fromCharCode(event.keyCode) + " keyCode: " + event.keyCode);

  addKeyToSwipeBuffer(event.keyCode, String.fromCharCode(event.keyCode));

  // anonymous function that should be called to extract card data!
  temp_function = function() {
    parseAndDistributeSwipeBuffer();
  };

  // This will ensure that keypresses are only appended to the swipe data buffer
  // if they are coming in fast enough. The theory is, humans probably won't type as fast
  // as the swiper will.
  if (swipe_timeout == null) {
    swipe_timeout = setTimeout("temp_function()", SWIPE_TIMEOUT_MS);
  } else {
    clearTimeout(swipe_timeout);
    swipe_timeout = setTimeout("temp_function()", SWIPE_TIMEOUT_MS);
  }
};

var getTrack1FromSwipe = function(swipe_data) {
  // console.log('swipe_data: '+swipe_data);
  if (swipe_data.substr(0, 1) == '%') {
    swipe_data = swipe_data.substr(1); // remove the leading character
  }

  var tracks = swipe_data.split('?'); // split off tracks one and two
  var track1 = tracks[0];
  var track2 = tracks[1].substr(1); // this will strip off the ';', which is the second track's leading sentinel.

   /* This method parses out both track1 and track2 from the swipe data, but only returns track1
    * To return both, you could do something like the following, instead:
    *
    * return {
    *   track1_data: track1,
    *   track2_data: track2
    * };
    */

  // For this implementation, just return track 1
  return track1;
};

var getCardTypeByNumber = function(cc_number) {
  cc_number = cc_number.toString();

  // Visa
  if (cc_number.match(/^4[0-9]{12}(?:[0-9]{3})?$/) != null) {
    return "Visa";
  }
  // AmericanExpress
  else if (cc_number.match(/^3[47][0-9]{13}$/) != null) {
    return "AmericanExpress";
  }
  // Discover
  else if (cc_number.match(/^6(?:011|5[0-9]{2})[0-9]{12}$/) != null) {
    return "Discover";
  }
  // MasterCard
  else if (cc_number.match(/^5[1-5][0-9]{14}$/) != null) {
    return "MasterCard";
  }
  // Other
  else {
    // this is likely invalid, but let's return 'Visa' anyway for now. The Luhn Algorithm should check validity later anyway.
    return "Visa";
  }
};

var parseAndDistributeSwipeBuffer = function() {
  // Fires just before the field blurs if the field value has changed.
  // console.log( '[parseAndDistributeSwipeBuffer] swipe buffer == ' + swipe_buffer );

  var card_holder, first_name, last_name, cc_number, exp_month, exp_year, cvv;

  // parse new value
  var full_cc_regex = /(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})[=D][0-9]{4}/;
  var cc_regex = /(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})/;
  var exp_regex = /=(\d{4})/;

    if(swipe_buffer.match( full_cc_regex )) {
    var exp = exp_regex.exec( swipe_buffer );
    // console.log('exp: '+exp);
    exp_month = exp[1].substr(2);
    exp_year = '20' + exp[1].substr(0,2);
    cc_number = cc_regex.exec( swipe_buffer );

    // Let's look at our parsed data in the console
    // console.log('cctype ' + getCardTypeByNumber(cc_number));
    // console.log('cc_number_field ' +  cc_number);
    // console.log('cc_exp_month ' + exp_month);
    // console.log('cc_exp_year ' + exp_year);
    // console.log('cc_track1data' + getTrack1FromSwipe(swipe_buffer));

    // Populate the cc number, exp month, and exp year fields (field ids taken from top of this file)
    $('#'+cc_number_field_id).val(cc_number);
    $('#'+cc_exp_month_field_id).val(exp_month);
    $('#'+cc_exp_year_field_id).val(exp_year);

  }
  else {
    console.log( "[parseAndDistributeSwipbuffer] Failed: card swiped. does not fit CC regex" );
  }

  // clear buffer.
  clearSwipeBuffer();
};