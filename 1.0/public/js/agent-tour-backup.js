function getPrice(cur,name){

    // console.log("click: " +cur.id.charAt(0));
    var c = cur.id.charAt(0);
    var curRow = '0';
    if($('#tour_type').val()=='public(2h)'){
        curRow = '0';
    }else if($('#tour_type').val()=='private(2h)'){
        curRow='1';
    }else if($('#tour_type').val()=='private(3h)'){
        curRow='2';
    }

    //check if the same row
    if(c!=curRow){
        //curRow = c;
        $(cur).notify("Please choose tour type first", {position:"right middle",autoHideDelay:2000});
        //console.log('not equal');
        return false;
    }

    for(j=0;j<3;j++) {
        for (i = 0; i <= bikes.length; i++) {
            var tmp = '#' + j + i;
            //console.log(tmp);
            $(tmp).css('background-color', 'white');
        }
    }

    for(i=0;i<=bikes.length;i++){
        var tmp = '#'+c+i;
        //console.log(tmp);
        $(tmp).css('background-color','#rgb(232, 232, 232);');
    }
    //update this color
    //update bike nums
    var nam = name+'_tour';
    if($(nam).val()<=19){
        $(nam).val(parseInt($(nam).val())+1);
    }

    calculate();
}

var totalsum;
var tax =1.00;
var total_bikeNum;

$(function() {


    $( ".field" ).keyup(function() {
        // console.log( "Handler for .change() called." );
        calculate();
    });

    $('.ui-spinner-button').click(function () {
        console.log("Handler for .change() called.");
        calculate();
    });

    for(i=0;i<=bikes.length;i++){
        var tmp = '#'+0+i;
        // console.log(tmp);
        $(tmp).css('background-color','#rgb(232, 232, 232);');
        //$(tmp).notify("Please input valid name", {position:"right middle"});
    }

    $('.tour-title').click(function () {
        // console.log("check hour.");
        $('#tour_type').val(this.value);
        // console.log("now: "+$('#rent_duration').val());
        // console.log('id: '+this.id);
        var c = this.id.charAt(0);
        for(j=0;j<=2;j++) {
            for (i = 0; i <= bikes.length; i++) {
                var tmp = '#' + j + i;
                // console.log(tmp);
                $(tmp).css('background-color', 'white');
                //$(tmp).notify("Please input valid name", {position:"right middle"});
            }
        }
        for(i=0;i<=bikes.length;i++){
            var tmp = '#'+c+i;
            // console.log(tmp);
            $(tmp).css('background-color','#rgb(232, 232, 232);');
            //$(tmp).notify("Please input valid name", {position:"right middle"});
        }
        calculate();
    });

    $("#cash_paid_label").keyup(function() {
        var customer = parseFloat($( "#cash_paid_label" ).val());
        var agent = parseFloat($( "#tour_tips" ).val());
        // console.log("here");
        if(customer>=agent){
            // console.log("true");
            $("#cash_change_label").val((customer-agent).toFixed(2)).css('color','green');
        }else{
            // console.log("false");
        }
    });

    $('#tour_type').change(function() {

        //console.log( "Handler for .change() called." );
        for(j=0;j<=2;j++) {
            for (i = 0; i <= bikes.length; i++) {
                var tmp = '#' + j + i;
                //console.log(tmp);
                $(tmp).css('background-color', 'white');
                //$(tmp).notify("Please input valid name", {position:"right middle"});
            }
        }
        var curRow = '0';
        if($('#tour_type').val()=='public(2h)'){
            curRow = '0';
        }else if($('#tour_type').val()=='private(2h)'){
            curRow='1';
        }else if($('#tour_type').val()=='private(3h)'){
            curRow='2';
        }

        for(i=0;i<=bikes.length;i++){
            var tmp = '#'+curRow+i;
            //console.log(tmp);
            $(tmp).css('background-color','#rgb(232, 232, 232)');
            //$(tmp).notify("Please input valid name", {position:"right middle"});
        }
        calculate();
    });

    goBack();

    $( "#rent_rendered" ).focus(function() {
        if($('#rent_rendered').val()=="0"){
            $('#rent_rendered').val(null);
        }
    });

    $('#rent_rendered').keyup(function() {

        if(!$('#rent_rendered').val()){
            $('#rent_change').val(null);
        }else{
            var rent_rendered = parseFloat($('#rent_rendered').val());
            var rent_total_after_tax = parseFloat($('#rent_total_after_tax').val());
            if(rent_rendered>=rent_total_after_tax){
                $('#rent_change').val((rent_rendered-rent_total_after_tax).toFixed(2));
            }else{
                $('#rent_change').val(null);
            }
        }
    });

    $('#rent_adjust').keyup(function() {
        numOrPercent = true;
        if(!$('#rent_adjust').val()){
            $('#rent_discount').val(null);
        }else{
            //var total = calculate();
            // console.log("adjust: "+$('#rent_adjust').val());
            // console.log("sum: "+totalsum);

            $('#rent_discount').val((100*(1-parseFloat($('#rent_adjust').val())/parseFloat(totalsum))).toFixed(0));
            $('#rent_total_label').val($('#rent_adjust').val());
            // $('#rent_tax').val((parseFloat($('#rent_adjust').val())*(tax-1)).toFixed(2));
            // $("#rent_total_after_tax").val((parseFloat($('#rent_adjust').val()*tax).toFixed(2))).css('color','green');
        }
        calculate();
    });

    $('#rent_discount').keyup(function() {
        numOrPercent = false;
        if(!$('#rent_discount').val()){
            $('#rent_adjust').val(null);
        }else{
            // var beforeTaxSum = totalsum;
            // console.log('before sum: '+totalsum);
            //
            // console.log('rate: '+(1-(parseFloat($('#rent_discount').val())/100)));
            // $('#rent_adjust').val(((1-(parseFloat($('#rent_discount').val())/100))*parseFloat(beforeTaxSum)).toFixed(2));
            // $('#rent_total_label').val($('#rent_adjust').val());
            var bikeNum = 0;
            var doubleBikeNum = 0;
            for(i=0;i<bikes.length;i++){
                bikeNum += parseInt($(bikes[i]).val());
            }
            var tmpTotalsum = totalsum/tax;


            if($('#insurance').prop('checked')==true){
                tmpTotalsum -= bikeNum*2;
            }

            tmpTotalsum -= parseFloat($('#basket_bike').val());


            var beforeTaxSum = tmpTotalsum;

            //console.log('rate: '+parseFloat($('#rent_discount').val())/100);

            $('#rent_adjust').val(((1-(parseFloat($('#rent_discount').val())*0.01))*parseFloat(beforeTaxSum)).toFixed(2));
            // $('#rent_total_label').val($('#rent_adjust').val());
        }
        calculate();

    });

    //agent list
    $("#tour_agent").autocomplete({
        source: agentList
    });

    $('#tour_agent').keyup(function() {
        // console.log('agent: '+agentListMap.get("java python"));
        // console.log('agent: '+agentListMap.get($("#tour_agent").val()));
        $('#tour_agent_level').val(agentListMap.get($("#tour_agent").val()));
    });

    $('#tour_agent').focusout(function() {
        $('#tour_agent_level').val(agentListMap.get($("#tour_agent").val()));

    });

    $('#insurance').click(function () {
        calculate();
    });

    $('#tour_coupon').click(function () {
        calculate();
    });


    $('[name="cash"]').click( function() {
        $("form").attr("target", "_self");
    });


    $("#deposit_cc_checkbox").change(function() {
        if($(this).prop('checked') == true) {
            $('#deposit_cash_checkbox').prop('checked',false);
            $('#deposit_id_checkbox').prop('checked',false);
            //console.log("Checked Box Selected");
        }
        calculate();
    });

    $("#deposit_cash_checkbox").change(function() {
        if($(this).prop('checked') == true) {
            $('#deposit_cc_checkbox').prop('checked',false);
            $('#deposit_id_checkbox').prop('checked',false);
            //console.log("Checked Box Selected");
        }
        calculate();
        console.log('cash_checkbox');
    });

    $("#deposit_id_checkbox").change(function() {
        if($(this).prop('checked') == true) {
            $('#deposit_cash_checkbox').prop('checked',false);
            $('#deposit_cc_checkbox').prop('checked',false);
            //console.log("Checked Box Selected");
        }
        $("#rent_deposit").val(0);
        calculate();
    });

    $('#rent_deposit').keyup(function() {
        if(!$('#rent_deposit').val()){
            calculate();
            $('#rent_deposit').val(null);
        }else{
            calculate();
        }
    });

});


function goBack(){
    //check go back this page
    var curRow = 0;
    if($('#tour_type').val()=='public(2h)'){
        curRow = 0;
    }else if($('#tour_type').val()=='private(2h)'){
        curRow=1;
    }else if($('#tour_type').val()=='private(3h)'){
        curRow=2;
    }

    for(j=0;j<=2;j++) {
        for (i = 0; i <= bikes.length; i++) {
            var tmp = '#' + j + i;
            //console.log(tmp);
            $(tmp).css('background-color', 'white');
            //$(tmp).notify("Please input valid name", {position:"right middle"});
        }
    }

    for(i=0;i<=bikes.length;i++){
        var tmp = '#'+curRow+i;
        //console.log(tmp);
        $(tmp).css('background-color','#rgb(232, 232, 232)');
        //$(tmp).notify("Please input valid name", {position:"right middle"});
    }
}

var bikes = ["#adult_tour","#child_tour","#seat_tour"];
var numOrPercent = false;


function calculate(){
    var s = 0;
    if($('#tour_type').val()=='public(2h)'){
        s = 0;
    }else if($('#tour_type').val()=='private(2h)'){
        s=1;
    }else if($('#tour_type').val()=='private(3h)'){
        s=2;
    }

    // console.log("#"+s+'1');
    var total = 0;
    var bikeNum = 0;
    for(i=1;i<=bikes.length;i++){
        // console.log("seats: "+$("#"+s+i).val());

        total += parseInt($(bikes[i-1]).val()*$("#"+s+i).val());
        if(i<bikes.length){
            bikeNum += parseInt($(bikes[i-1]).val());
        }
    }

    total += parseInt($('#basket_tour').val());

    var tmpSeat = parseInt($('#seat_tour').val());
    if($('#tour_type').val()=="private(3h)"){
        tmpSeat *=15;
    }else{
        tmpSeat *=10;
    }

    if($('#tour_coupon').prop('checked')==true){
        total = 0;
        total += tmpSeat;
        total += parseFloat($('#basket_tour').val());
    }


    if($('#insurance').prop('checked')==true){
        total += bikeNum*2;
    }
    // console.log("after cal, now total: "+total+" ,original: "+originalPriceBefore);
    total -= originalPriceBefore;
    totalsum = total;


    if(numOrPercent) {
        if ($('#rent_adjust').val().trim().length >= 1) {
            total = parseFloat($('#rent_adjust').val());
            total += parseInt($('#basket_tour').val());
            total += tmpSeat;
            if ($('#insurance').prop('checked') == true) {
                total += bikeNum * 2;
            }
        }
    }

    if(!numOrPercent) {

        if ($('#insurance').prop('checked') == true) {
            total -= bikeNum*2;
        }
        total -= parseFloat($('#basket_tour').val());
        total -= tmpSeat;

        if ($('#rent_discount').val().trim().length >= 1) {
            total = total * (1 - parseFloat($('#rent_discount').val()) * 0.01);
            $('#rent_adjust').val(total.toFixed(2));
        }

        total += tmpSeat;
        if ($('#insurance').prop('checked') == true) {
            total += bikeNum*2;
        }

        total += parseFloat($('#basket_tour').val());
    }

    // if($('#rent_discount').val().trim().length>=1){
    //     total = total*(1-parseFloat($('#rent_discount').val()*0.01));
    //     $('#rent_adjust').val(total.toFixed(2));
    // }

    total_bikeNum = bikeNum;

    $("#rent_total_label").val(total.toFixed(2)).css('color','green');
    // $("#tour_tips").val((total*0.2995).toFixed(2)).css('color','green');
    // $("#tour_tips").val(Math.floor(total*30)/100).css('color','green');

    if($('#tour_coupon').prop('checked')==true){
        $("#rent_tax").val(0).css('color','green');
        $("#rent_total_after_tax").val((total*1).toFixed(2)).css('color','green');

    }else{
        $("#rent_tax").val((total*(tax-1)).toFixed(2)).css('color','green');
        $("#rent_total_after_tax").val((total*tax).toFixed(2)).css('color','green');

    }

    if($('#rent_deposit').val()&&($('#deposit_cash_checkbox').prop('checked')==true ||$('#deposit_cc_checkbox').prop('checked')==true)){
        var deposit = parseFloat($('#rent_deposit').val());
    }else{
        var deposit = 0;
    }
    console.log('update');
    $('#rent_total_after_tax_deposit').val((parseFloat($('#rent_total_after_tax').val())+parseFloat(inv_price)+deposit).toFixed(2)).css('color','green');

    $("#rent_rendered").val($("#rent_total_after_tax_deposit").val()).css('color','green');

}

function ccSubmitCheck(){

    if(!checkName()) return false;
    if(!checkBikeNum()) return false;
    if(!checkValid()) return false;
    if(!checkDepositType()) return false;
    if(!checkAdjust()){
//                $('body').css('cursor', 'default');

        return false;
    }


    // var floatRegex = /^(0\.[0-9]|[0-9][0-9]{0,2}(\.[0-9]{0,2})?)$/;
    // // console.log("price: "+$('#tour_total').val().replace('$',''));
    // // console.log("val: "+$('#rent_total_label').val().trim());
    // if(!$('#rent_total_label').val().trim().match(floatRegex)){
    //     $('#rent_total_label').notify("Please input valid price", {position: "right middle"});
    //     return false;
    // }
    // else{
    //     return true;
    // }
    return true;
}


function cashSubmitCheck(){
    $('#cashBtn').attr("disabled", true);

    if(!checkName()) {
        $('#cashBtn').attr("disabled", false);
        return false;
    }
    if(!checkBikeNum()) {
        $('#cashBtn').attr("disabled", false);
        return false;
    }
    if(!checkValid()) {
        $('#cashBtn').attr("disabled", false);
        return false;
    }
    if(!checkRenderedCash()) {
        $('#cashBtn').attr("disabled", false);
        return false;
    }
    if(!checkDepositType()) {
        $('#cashBtn').attr("disabled", false);
        return false;
    }
    if(!checkAdjust()){
//                $('body').css('cursor', 'default');
        $('#cashBtn').attr("disabled", false);

        return false;
    }

    // var floatRegex = /^(0\.[1-9]|[1-9][0-9]{0,2}(\.[0-9]{0,2})?)$/;
    // // console.log("price: "+$('#tour_total').val().replace('$',''));
    //
    // if(!$('#tour_tips').val().match(floatRegex)){
    //
    //     $('#tour_tips').notify("Please input valid price", {position: "bottom center",autoHideDelay:2000});
    //     return false;
    // }
    //
    // var agent_price = parseFloat($('#rent_tips_label').val());
    // var total_price = (parseFloat($('#rent_total_label').val())*0.3).toFixed(2);
    //
    // if(agent_price>total_price){
    //     $('#rent_tips_label').notify("Can not be more than $"+total_price, {position: "bottom center",autoHideDelay:2000});
    //     return false;
    // }else{
    //     return true;
    // }

    $('#cashBtn').attr("disabled", true);
//            console.log('disable');

//            $('body').css('cursor', 'wait');
    var form = document.getElementById('payment-form');
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'cash'; // 'the key/name of the attribute/field that is sent to the server
    input.value = 'Cash';
    form.appendChild(input);

    form.submit();
    return true;

}

function checkName(){
    if($('#tour_customer_first').val().trim().length<=0){
        $('#tour_customer_first').notify("Please input valid name", {position:"bottom center",autoHideDelay:2000});
        return false;
    }else if($('#tour_customer_last').val().trim().length<=0){
        $('#tour_customer_last').notify("Please input valid name", {position:"bottom center",autoHideDelay:2000});
    } else{
        return true;
    }
}

function checkBikeNum(){

    if($('#adult_tour').val().trim()==0 && $('#child_tour').val().trim()==0){
        $('#child_tour_label').notify("Tour number can't be 0", {position:"right bottom",autoHideDelay:2000});
        return false;
    }else{
        return true;
    }
}

function checkValid(){
    var numReg = /^\d+$/;
    var dateReg = /^\d{2}[/]\d{2}[/]\d{4}$/;
    var floatReg = /^-?\d*(\.\d+)?$/;

    if(!$('#adult_tour').val().trim().match(numReg)||!$('#child_tour').val().trim().match(numReg)){

        $('#child_tour_label').notify("Please input valid number", {position: "right bottom",autoHideDelay:2000});

        return false;
    }else if(!$('#tour_date').val().trim().match(dateReg)){
        $('#tour_date').notify("Please input valid date", {position: "top center",autoHideDelay:2000});
        return false;
    } else if(!$('#rent_adjust').val().trim().match(floatReg)){
        // console.log('cash4');

        $('#rent_adjust').notify("Please input valid number", {position: "top right"});
        return false;
    }else if(!$('#rent_rendered').val().trim().match(floatReg)) {
        // console.log('cash4');

        $('#rent_rendered').notify("Please input valid number", {position: "top right"});
        return false;
    } else{
        return true;
    }
}

function checkRenderedCash(){
    if($('#rent_rendered').val().trim().length<=0){
        $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
        return false;
    }

    if($('#rent_adjust').val().trim().length>0){
        if(parseFloat($('#rent_adjust').val())>parseFloat($('#rent_rendered').val())){
            $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
            return false;
        }
    }else{
        if(parseFloat($('#rent_total_after_tax').val())>parseFloat($('#rent_rendered').val())){
            $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
            // console.log('not enough');
            return false;
        }
        // console.log('enough');
    }
    return true;
}


function checkDepositType(){
    if($('#rent_deposit').val().trim().length>0 && $('#deposit_id_checkbox').prop('checked')==true){
        $('#rent_deposit').notify("Choose Credit Card or Cash or clear Deposit number", {position: "right middle"});
        return false;
    }
    return true;
}

function checkAdjust() {
    // console.log('checkDeposit cash: '+$('#rent_deposit').val());

    var numReg = /^\s*-?[1-9]\d*(\.\d{1,2})?\s*$/;
    if($('#rent_discount').val().trim()){
        // console.log('checkDeposit');
        if(!($('#rent_discount').val().trim().match(numReg))){
//                    $('#rent_deposit').notify("Select a payment method", {position: "top right"});
            swal("Discount should be a plain number ");
            return false;
        }
    }
    return true;
}


    var target = $('#tour_place');
    $('#tour_place').on('change',function () {
        if($(this).val() =="walking") {
            $('#adult_tour,#child_tour, #rent_total_label, #rent_total_after_tax,#rent_rendered,#rent_total_after_tax_deposit').val(0);
            $('#01').val('24');
            $('#02').val('19');

        }
        else if($('#tour_place').val()!="walking") {
            console.log('A');
            $('#01').val('49');
            $('#02').val('35');
            $('#adult_tour,#child_tour, #rent_total_label, #rent_total_after_tax,#rent_rendered,#rent_total_after_tax_deposit').val(0);


        }
        });

