
var cursor         = 0;
var lineSpace      = 0;
var leftPosition   = 0;
var centerPosition = 0;
var rightPosition  = 0;

function DrawLeftText(text) {
    var canvas = document.getElementById('canvasPaper');

    if (canvas.getContext) {
        var context = canvas.getContext('2d');

        context.textAlign = 'left';

        context.fillText(text, leftPosition, cursor);

        context.textAlign = 'start';
    }
}

function DrawCenterText(text) {
    var canvas = document.getElementById('canvasPaper');

    if (canvas.getContext) {
        var context = canvas.getContext('2d');

        context.textAlign = 'center';

        context.fillText(text, centerPosition, cursor);

        context.textAlign = 'start';
    }
}

function DrawRightText(text) {
    var canvas = document.getElementById('canvasPaper');

    if (canvas.getContext) {
        var context = canvas.getContext('2d');

        context.textAlign = 'right';

        context.fillText(text, rightPosition, cursor);

        context.textAlign = 'start';
    }
}

function onDrawReceipt() {
    switch (document.getElementById('paperWidth').value) {
        case 'inch2' :
            drawReceipt(28, 28, 384, 1);
            break;
        case 'inch3DotImpact' :
            drawReceipt(32, 32, 576, 1.5);
            break;
        default :
            drawReceipt(32, 32, 576, 1.5);
            break;
        case 'inch4' :
            drawReceipt(48, 48, 832, 2);
            break;
    }
}

function drawReceipt(fontSize, lineSpace, receiptWidth, logoScale) {
    var canvas = document.getElementById('canvasPaper');

    if (canvas.getContext) {
        var context = canvas.getContext('2d');

        context.clearRect(0, 0, canvas.width, canvas.height);

//      context.textAlign    = 'start';
        context.textBaseline = 'top';

        var font = '';

        if (document.getElementById('italic').checked) font += 'italic ';

        font += fontSize + 'px ';

        font += document.getElementById('font').value;

        context.font = font;

        leftPosition   =  0;
//      centerPosition =  canvas.width       / 2;
        centerPosition = (canvas.width - 16) / 2;
//      rightPosition  =  canvas.width;
        rightPosition  = (canvas.width - 16);

//      cursor = 0;
        cursor = 55 * logoScale; // ロゴが入るスペースを空けておく

        DrawRightText('TEL 9999-99-9999'); cursor += lineSpace;

        cursor += lineSpace;

        DrawCenterText('Thank you for your coming.');  cursor += lineSpace;
        DrawCenterText("We hope you'll visit again."); cursor += lineSpace;

        cursor += lineSpace;

        DrawLeftText('Apple');    DrawRightText('$20.00');  cursor += lineSpace;
        DrawLeftText('Banana');   DrawRightText('$30.00');  cursor += lineSpace;
        DrawLeftText('Grape');    DrawRightText('$40.00');  cursor += lineSpace;
        DrawLeftText('Lemon');    DrawRightText('$50.00');  cursor += lineSpace;
        DrawLeftText('Orange');   DrawRightText('$60.00');  cursor += lineSpace;
        DrawLeftText('Subtotal'); DrawRightText('$200.00'); cursor += lineSpace;

        cursor += lineSpace;

        DrawLeftText('Tax');      DrawRightText('$10.00');  cursor += lineSpace;

        context.fillRect(0, cursor - 2, receiptWidth, 2);     // Underline

        DrawLeftText('Total');    DrawRightText('$210.00'); cursor += lineSpace;

        cursor += lineSpace;

        DrawLeftText('Received'); DrawRightText('$300.00'); cursor += lineSpace;
        DrawLeftText('Change');   DrawRightText('$90.00');  cursor += lineSpace;

//      alert('Cursor:' + cursor + ', ' + 'Canvas:' + canvas.height);

        var image = new Image();

        image.src = 'img/StarLogo1.jpg' + '?' + new Date().getTime();

        image.onload = function () {
            context.drawImage(image, canvas.width - image.width * logoScale, 0, image.width * logoScale, image.height * logoScale);
        }

        image.onerror = function () {
            alert('Image file was not able to be loaded.');
        }
    }
}

function onResizeCanvas() {
    var canvas = document.getElementById('canvasPaper');

    if (canvas.getContext) {
        var context = canvas.getContext('2d');

        switch (document.getElementById('paperWidth').value) {
            case 'inch2' :
                canvas.width = 384;
                canvas.height = 555;
                break;
            case 'inch3DotImpact' :
                canvas.width = 576;
                canvas.height = 640;
                break;
            default :
                canvas.width = 576;
                canvas.height = 640;
                break;
            case 'inch4' :
                canvas.width = 832;
                canvas.height = 952;
                break;
        }
        document.getElementById('canvasPaper').style.width="700px";
        onDrawReceipt();
    }
}

function refocusFontSelectbox() {
    var fontSelectbox = document.getElementById('font');
    fontSelectbox.blur();
    fontSelectbox.focus();
}

function refocusWidthSelectbox() {
    var paperWidthSelectbox = document.getElementById('paperWidth');
    paperWidthSelectbox.blur();
    paperWidthSelectbox.focus();
}

function onSendMessage() {
    nowPrinting();
    var url              = document.getElementById('url').value;
    var papertype        = document.getElementById('papertype').value;
    var blackmark_sensor = document.getElementById('blackmark_sensor').value;

    var trader = new StarWebPrintTrader({url:url, papertype:papertype, blackmark_sensor:blackmark_sensor});

    trader.onReceive = function (response) {
        var msg = '- onReceive -\n\n';

        msg += 'TraderSuccess : [ ' + response.traderSuccess + ' ]\n';

//      msg += 'TraderCode : [ ' + response.traderCode + ' ]\n';

        msg += 'TraderStatus : [ ' + response.traderStatus + ',\n';

        if (trader.isCoverOpen            ({traderStatus:response.traderStatus})) {msg += '\tCoverOpen,\n';}
        if (trader.isOffLine              ({traderStatus:response.traderStatus})) {msg += '\tOffLine,\n';}
        if (trader.isCompulsionSwitchClose({traderStatus:response.traderStatus})) {msg += '\tCompulsionSwitchClose,\n';}
        if (trader.isEtbCommandExecute    ({traderStatus:response.traderStatus})) {msg += '\tEtbCommandExecute,\n';}
        if (trader.isHighTemperatureStop  ({traderStatus:response.traderStatus})) {msg += '\tHighTemperatureStop,\n';}
        if (trader.isNonRecoverableError  ({traderStatus:response.traderStatus})) {msg += '\tNonRecoverableError,\n';}
        if (trader.isAutoCutterError      ({traderStatus:response.traderStatus})) {msg += '\tAutoCutterError,\n';}
        if (trader.isBlackMarkError       ({traderStatus:response.traderStatus})) {msg += '\tBlackMarkError,\n';}
        if (trader.isPaperEnd             ({traderStatus:response.traderStatus})) {msg += '\tPaperEnd,\n';}
        if (trader.isPaperNearEnd         ({traderStatus:response.traderStatus})) {msg += '\tPaperNearEnd,\n';}

        msg += '\tEtbCounter = ' + trader.extractionEtbCounter({traderStatus:response.traderStatus}).toString() + ' ]\n';

//      msg += 'Status : [ ' + response.status + ' ]\n';
//
//      msg += 'ResponseText : [ ' + response.responseText + ' ]\n';

        alert(msg);
    }

    trader.onError = function (response) {
        var msg = '- onError -\n\n';

        msg += '\tStatus:' + response.status + '\n';

        msg += '\tResponseText:' + response.responseText;

        alert(msg);
    }

    try {
        var canvas = document.getElementById('canvasPaper');

        if (canvas.getContext) {
            var context = canvas.getContext('2d');

            var builder = new StarWebPrintBuilder();

            var request = '';

            request += builder.createInitializationElement();

            request += builder.createBitImageElement({context:context, x:0, y:0, width:canvas.width, height:canvas.height});

            request += builder.createCutPaperElement({feed:true});

            trader.sendMessage({request:request});
        }
    }
    catch (e) {
        alert(e.message);
    }
}
function nowLoading(){
    document.getElementById('form').style.display="block";
    document.getElementById('overlay').style.display="none";
    document.getElementById('nowLoadingWrapper').style.display="none";
}
function nowPrinting(){
    document.getElementById('overlay').style.display="block";
    document.getElementById('nowPrintingWrapper').style.display="table";
    timer = setTimeout(function (){
        document.getElementById('overlay').style.opacity= 0.0;
        document.getElementById('overlay').style.transition= "all 0.3s";
        intimer = setTimeout(function (){
            document.getElementById('overlay').style.display="none";
            document.getElementById('overlay').style.opacity= 1;
            clearTimeout(intimer);
        }, 300);
        document.getElementById('nowPrintingWrapper').style.display="none";
        clearTimeout(timer);
    }, 11000);
}
window.onload = function() {
        nowLoading();
        onResizeCanvas();
    }