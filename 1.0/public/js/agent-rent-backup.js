function getPrice(cur,name){

    if(cur.id=='53'||cur.id=='54'||cur.id=='55'||cur.id=='56'||cur.id=='57'||cur.id=='58'){
        $(cur).notify("No service", {position:"right middle",autoHideDelay:2000});
        //console.log("invalid");
        return false;
    }

    console.log("click: " +cur.id.charAt(0));
    var c = cur.id.charAt(0);
    var curRow = '0';
    if($('#rent_duration').val()=='1 hour'){
        curRow = '0';
    }else if($('#rent_duration').val()=='2 hours'){
        curRow='1';
    }else if($('#rent_duration').val()=='3 hours'){
        curRow='2';
    }else if($('#rent_duration').val()=='5 hours'){
        curRow='3';
    }else if($('#rent_duration').val()=='All Day (8am-8pm)'){
        curRow='4';
    }else if($('#rent_duration').val()=='24 hours'){
        curRow='5';
    }

    //check if the same row
    if(c!=curRow){
        //curRow = c;
        $(cur).notify("Please choose hours first", {position:"right middle",autoHideDelay:2000});
        //console.log('not equal');
        return false;
    }

    for(j=0;j<=5;j++) {
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
    var nam = name+'_bike';
    if($(nam).val()<=19){
        $(nam).val(parseInt($(nam).val())+1);
    }

    calculate();
}


$(function() {

    $( ".field" ).keyup(function() {
        //console.log( "Handler for .change() called." );
        calculate();
    });

    $('.ui-spinner-button').click(function () {
        //console.log("Handler for .change() called.");
        calculate();
    });

    $('#basket').click(function () {
        calculate();
    });
    $('#insurance').click(function () {
        calculate();
    });
    $('#dropoff').click(function () {
        calculate();
    });

    for(i=0;i<=bikes.length;i++){
        var tmp = '#'+0+i;
        //console.log(tmp);
        $(tmp).css('background-color','#rgb(232, 232, 232);');
        //$(tmp).notify("Please input valid name", {position:"right middle"});
    }

    $('.duration-title').click(function () {
        //console.log("check duration.");
        $('#rent_duration').val(this.value);
        // console.log("now: "+$('#rent_duration').val());
        //console.log('id: '+this.id);
        var c = this.id.charAt(0);
        for(j=0;j<=5;j++) {
            for (i = 0; i <= bikes.length; i++) {
                var tmp = '#' + j + i;
                //console.log(tmp);
                $(tmp).css('background-color', 'white');
                //$(tmp).notify("Please input valid name", {position:"right middle"});
            }
        }
        for(i=0;i<=bikes.length;i++){
            var tmp = '#'+c+i;
            //console.log(tmp);
            $(tmp).css('background-color','#rgb(232, 232, 232)');
            //$(tmp).notify("Please input valid name", {position:"right middle"});
        }
        calculate();
    });

    $('.rent_duration').change(function() {

        console.log( "Handler for .change() called." );
        for(j=0;j<=5;j++) {
            for (i = 0; i <= bikes.length; i++) {
                var tmp = '#' + j + i;
                //console.log(tmp);
                $(tmp).css('background-color', 'white');
                //$(tmp).notify("Please input valid name", {position:"right middle"});
            }
        }
        var curRow = '0';
        var form = document.getElementById("payment-form");
        // console.log(form.elements["rent_duration"].value);
        // console.log("val: "+$('[name="rent_duration"]').val());

        if(form.elements["rent_duration"].value=='1 hour'){
            curRow = '0';
        }else if(form.elements["rent_duration"].value=='2 hours'){
            curRow='1';
        }else if(form.elements["rent_duration"].value=='3 hours'){
            curRow='2';
        }else if(form.elements["rent_duration"].value=='5 hours'){
            curRow='3';
        }else if(form.elements["rent_duration"].value=='All Day (8am-8pm)'){
            curRow='4';
        }else if(form.elements["rent_duration"].value=='24 hours'){
            curRow='5';
            $('#tandem_bike').val(0);
            $('#road_bike').val(0);
            $('#mountain_bike').val(0);
            // $('#trailer_bike').val(0);
            // $('#seat_bike').val(0);
            // $('#basket_bike').val(0);

        }

        // if($('.rent_duration').val()=='1 hour'){
        //     curRow = '0';
        // }else if($('.rent_duration').val()=='2 hours'){
        //     curRow='1';
        // }else if($('.rent_duration').val()=='3 hours'){
        //     curRow='2';
        // }else if($('.rent_duration').val()=='5 hours'){
        //     curRow='3';
        // }else if($('.rent_duration').val()=='All Day (8am-9pm)'){
        //     curRow='4';
        // }else if($('.rent_duration').val()=='24 hours'){
        //     curRow='5';
        //     $('#tandem_bike').val(0);
        //     $('#road_bike').val(0);
        //     $('#mountain_bike').val(0);
        //     $('#trailer_bike').val(0);
        //     $('#seat_bike').val(0);
        //     $('#basket_bike').val(0);
        //
        // }

        for(i=0;i<=bikes.length;i++){
            var tmp = '#'+curRow+i;
            //console.log(tmp);
            $(tmp).css('background-color','#rgb(232, 232, 232)');
            //$(tmp).notify("Please input valid name", {position:"right middle"});
        }
        calculate();
    });


    $("#cash_paid_label").keyup(function() {
        var customer = parseFloat($( "#cash_paid_label" ).val());
        var agent = parseFloat($( "#tips_label" ).val());
        if(customer>=agent){
            console.log("true");
            $("#rent_cash_change_label").val((customer-agent).toFixed(2)).css('color','green');
        }else{
            console.log("false");
        }
    });


    goBack();


    $('#rent_adjust').keyup(function() {
        numOrPercent = true;

        if(!$('#rent_adjust').val()){
            $('#rent_discount').val(null);
        }else{
            //var total = calculate();
            // console.log("adjust: "+$('#rent_adjust').val());
            // console.log("sum: "+totalsum);

            var beforeTaxSum = totalsum/tax;
            if(totalsum!=0){
                $('#rent_discount').val((100*(1-parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(0));
            }
            // $('#rent_discount').val((100*(parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(0)+'%');
            // $('#rent_discount').val((100*(1-parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(0));
            if(parseFloat($('#rent_discount').val())==0){
                $('#rent_discount').val(0);
            }
            $('#rent_total_label').val($('#rent_adjust').val());
            // $('#rent_tax').val((parseFloat($('#rent_adjust').val())*(tax-1)).toFixed(2));
            // $("#rent_total_after_tax").val((parseFloat($('#rent_adjust').val()*tax).toFixed(2))).css('color','green');
        }
        calculate();
    });


    // $('#rent_adjust').keyup(function() {
    //
    //     if(!$('#rent_adjust').val()){
    //         $('#rent_discount').val(null);
    //     }else{
    //         //var total = calculate();
    //         console.log("adjust: "+$('#rent_adjust').val());
    //         console.log("sum: "+totalsum);
    //
    //         var beforeTaxSum = totalsum/tax;
    //
    //         // $('#rent_discount').val((100*(parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(0)+'%');
    //         $('#rent_discount').val((100*(1-parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(2));
    //         if(parseFloat($('#rent_discount').val())==0){
    //             $('#rent_discount').val(0);
    //         }
    //         $('#rent_total_label').val($('#rent_adjust').val());
    //         // $('#rent_tax').val((parseFloat($('#rent_adjust').val())*(tax-1)).toFixed(2));
    //         // $("#rent_total_after_tax").val((parseFloat($('#rent_adjust').val()*tax).toFixed(2))).css('color','green');
    //     }
    //     calculate();
    // });


    $('#rent_discount').keyup(function() {
        numOrPercent = false;

        if(!$('#rent_discount').val()){
            // calculate();
            $('#rent_adjust').val(null);
        }else{
            //var total = calculate();
            var bikeNum = 0;
            var doubleBikeNum = 0;
            for(i=1;i<=bikes_arr.length-1;i++){
                bikeNum += parseInt($(bikes[i-1]).val());
            }

            for(i=3;i<6;i++){
                doubleBikeNum += parseInt($(bikes[i-1]).val());
            }

            var tmpTotalsum = totalsum/tax;

            if($('#dropoff').prop('checked')==true){
                tmpTotalsum -= bikeNum*5;
            }

            if($('#insurance').prop('checked')==true){
                tmpTotalsum -= (bikeNum-doubleBikeNum)*2;
                tmpTotalsum -= doubleBikeNum*4;
            }

            tmpTotalsum -= parseFloat($('#basket_bike').val());



            var beforeTaxSum = tmpTotalsum;

            //console.log('rate: '+parseFloat($('#rent_discount').val())/100);

            $('#rent_adjust').val(((1-(parseFloat($('#rent_discount').val())/100))*parseFloat(beforeTaxSum)).toFixed(2));
            // $('#rent_total_label').val($('#rent_adjust').val());
        }
        calculate();
    });

    $( "#rent_rendered" ).focus(function() {
        if($('#rent_rendered').val()=="0"){
            $('#rent_rendered').val(null);
        }
    });

    $('#rent_rendered').keyup(function() {

        if(!$('#rent_rendered').val()){
            $('#rent_change').val(null);
        }else{

            if($('#deposit_cc_checkbox').prop('checked')==true){
                console.log('cc checked');
                var rent_rendered = parseFloat($('#rent_rendered').val());
                var rent_total_after_tax = parseFloat($('#rent_total_after_tax').val());
                if(rent_rendered>=rent_total_after_tax){
                    $('#rent_change').val((rent_rendered-rent_total_after_tax).toFixed(2));
                }else{
                    $('#rent_change').val(null);
                }
            }
            else{
                var rent_rendered = parseFloat($('#rent_rendered').val());
                var rent_total_after_tax = parseFloat($('#rent_total_after_tax_deposit').val());
                if(rent_rendered>=rent_total_after_tax){
                    $('#rent_change').val((rent_rendered-rent_total_after_tax).toFixed(2));
                }else{
                    $('#rent_change').val(null);
                }
            }

        }
    });

    $( "#rent_rendered" ).focus(function() {
        if($('#rent_rendered').val()=='0'){
            $('#rent_rendered').val(null);
        }
    });

    $( "#rent_rendered" ).blur(function() {
        if($('#rent_rendered').val()==null || $('#rent_rendered').val().length==0){
            $('#rent_rendered').val(0);
        }
    });


    $("#member_checkbox").change(function() {
        if($(this).prop('checked') == true) {
            //console.log("Checked Box Selected");
            $('#member_guest_checkbox').prop('checked',false);
            $('.member_checkbox_label').css('display','inline-block');
        } else {
            //console.log("Checked Box deselect");
            $('.member_checkbox_label').css('display','none');
        }
        calculate();
    });

    $("#member_guest_checkbox").change(function() {
        if($(this).prop('checked') == true) {
            $('#member_checkbox').prop('checked',false);
            //console.log("Checked Box Selected");
            $('.member_checkbox_label').css('display','inline-block');
            $('#coupon_bike').prop('checked',false);
        } else {
            //console.log("Checked Box deselect");
            $('.member_checkbox_label').css('display','none');
        }
        calculate();
    });


    $("#coupon_bike").change(function() {
        if($(this).prop('checked') == true) {
            // $('#coupon_bike').prop('checked',true);
            $('#member_checkbox').prop('checked',false);
            $('#member_guest_checkbox').prop('checked',false);
            $('.member_checkbox_label').css('display','none');

            //console.log("Checked Box Selected");
        } else {
            // $('#coupon_bike').prop('checked',false);
        }
        calculate();
    });


    $( "#rent_agent" ).autocomplete({
        source: agentList
    });

    $( "#rent_agent" ).focus(function() {
        if(this.value == ' ') {
            this.value = '';
        }
    });

    $( ".field" ).focus(function() {
        if(this.value == ' ') {
            this.value = '';
        }
    });

    $('#rent_deposit').keyup(function() {
        console.log('click');
        if(!$('#rent_deposit').val()){
            calculate();
            $('#rent_deposit').val(null);
        }else{
            //var total = calculate();
            calculate();
        }
    });

    $("#deposit_cc_checkbox").change(function() {
        if($(this).prop('checked') == true) {
            $('#deposit_cash_checkbox').prop('checked',false);
            //console.log("Checked Box Selected");
        }
    });

    $("#deposit_cash_checkbox").change(function() {
        if($(this).prop('checked') == true) {
            $('#deposit_cc_checkbox').prop('checked',false);
            //console.log("Checked Box Selected");
        }
        calculate();
        console.log('cash_checkbox');
    });

    $('[name="cash"]').click( function() {
        $("form").attr("target", "_self");

    });

    $( ".bike" ).focus(function() {
        if($(this).val()=='0'){
            $(this).val(null);
        }
    });


    $("#rent_agent").blur(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/bigbike/agent/rent/addAgent',
            data: {'rent_agent': $('#rent_agent').val()},
            type: 'POST',
            datatype: 'JSON',
            success: function (data) {

                var data2 = JSON.parse(data)

                console.log("success: "+data2);

            },
            error: function (data) {
                console.log('data: '+data);
                // $('#rent_agent').val('No such member!');
            }
        });
    });


});

function goBack(){
    //check go back this page
    var curRow = 0;
    // if($('.rent_duration').val()=='1 hour'){
    //     curRow = 0;
    // }else if($('.rent_duration').val()=='2 hours'){
    //     curRow=1;
    // }else if($('.rent_duration').val()=='3 hours'){
    //     curRow=2;
    // }else if($('.rent_duration').val()=='5 hours'){
    //     curRow=3;
    // }else if($('.rent_duration').val()=='All Day (8am-9pm)'){
    //     curRow=4;
    // }else if($('.rent_duration').val()=='24 hours'){
    //     curRow=5;
    // }
    var form = document.getElementById("payment-form");
    if(form.elements["rent_duration"].value=='1 hour'){
        curRow = 0;
    }else if(form.elements["rent_duration"].value=='2 hours'){
        curRow=1;
    }else if(form.elements["rent_duration"].value=='3 hours'){
        curRow=2;
    }else if(form.elements["rent_duration"].value=='5 hours'){
        curRow=3;
    }else if(form.elements["rent_duration"].value=='All Day (8am-8pm)'){
        curRow=4;
    }else if(form.elements["rent_duration"].value=='24 hours'){
        curRow=5;
    }

    for(j=0;j<=5;j++) {
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

var bikes = ["#adult_bike", "#child_bike", "#tandem_bike", "#road_bike", "#mountain_bike","#trailer_bike", "#basket_bike", "#seat_bike"];
var bikes_arr = ["#adult_bike", "#child_bike", "#tandem_bike", "#road_bike", "#mountain_bike","#trailer_bike"];
// var bikes_app_arr = ["#adult_bike", "#child_bike", "#tandem_bike", "#road_bike", "#mountain_bike"];

var totalsum;
var total_bikeNum;
var tax =1.08875;
var numOrPercent = false;


function calculate(){
    var s = 0;
    // console.log("value: "+$('.rent_duration').val());
    // if($('.rent_duration').val()=='1 hour'){
    //     s = 0;
    // }else if($('.rent_duration').val()=='2 hours'){
    //     s=1;
    // }else if($('.rent_duration').val()=='3 hours'){
    //     s=2;
    // }else if($('.rent_duration').val()=='5 hours'){
    //     s=3;
    // }else if($('.rent_duration').val()=='All Day (8am-9pm)'){
    //     s=4;
    // }else if($('.rent_duration').val()=='24 hours'){
    //     s=5;
    // }
    var form = document.getElementById("payment-form");
    if(form.elements["rent_duration"].value=='1 hour'){
        s = 0;
    }else if(form.elements["rent_duration"].value=='2 hours'){
        s=1;
    }else if(form.elements["rent_duration"].value=='3 hours'){
        s=2;
    }else if(form.elements["rent_duration"].value=='5 hours'){
        s=3;
    }else if(form.elements["rent_duration"].value=='All Day (8am-8pm)'){
        s=4;
    }else if(form.elements["rent_duration"].value=='24 hours'){

        s=5;
        $('#tandem_bike').val(0);
        $('#road_bike').val(0);
        $('#mountain_bike').val(0);
        // $('#trailer_bike').val(0);
        // $('#seat_bike').val(0);
        // $('#basket_bike').val(0);

    }
    //console.log("#"+s+'1');
    var total = 0;
    var bikeNum = 0;
    var doubleBikeNum = 0;
    for(i=1;i<=bikes_arr.length;i++){
        total += parseInt($(bikes[i-1]).val()*$("#"+s+i).val());
        // bikeNum += parseInt($(bikes[i-1]).val());
    }

    for(i=1;i<=bikes_arr.length-1;i++){
        bikeNum += parseInt($(bikes[i-1]).val());
    }

    for(i=3;i<6;i++){
        doubleBikeNum += parseInt($(bikes[i-1]).val());
    }

    if($('#dropoff').prop('checked')==true){
        total += bikeNum*5;
    }

    //check membership
    if($('#member_guest_checkbox').prop('checked')==true){
        if($('#member_type').val().length>=1){
            total = (total*0.5).toFixed(2);
        }
    }else if($('#member_checkbox').prop('checked')==true){
        if(bikeNum>1){
            $('#adult_bike_label').notify("Membership only Applies for 1 Bike", {position: "top right"});
        }

        if($('#member_type').val()=="Month Pass/$45" || $('#member_type').val()=="Annual Pass/$129"){
            total = 3;
        }else if($('#member_type').val()=="Day Pass/$5"){

        }
    }else if($('#coupon_bike').prop('checked')==true){
        total = 0;
        if($('#dropoff').prop('checked')==true){
            total += bikeNum*5;
        }

        if($('#insurance').prop('checked')==true){
            total += (bikeNum-doubleBikeNum)*2;
            total += doubleBikeNum*4;
        }

        // total += parseFloat($('#basket_bike').val());
    }



    for(i=7;i<=bikes.length;i++){
        total += parseInt($(bikes[i-1]).val()*$("#"+s+i).val());
    }


    if($('#coupon_bike').prop('checked')==false){
        if($('#insurance').prop('checked')==true){
            total += (bikeNum-doubleBikeNum)*2;
            total += doubleBikeNum*4;
        }

    }

    // console.log("after cal, now total: "+total+" ,original: "+originalPriceBefore);
    total -= originalPriceBefore;
    totalsum = (total*tax).toFixed(2);

    // if($('#rent_adjust').val().length>=1){
    //     total = parseFloat($('#rent_adjust').val());
    //
    //     if($('#dropoff').prop('checked')==true){
    //         total += bikeNum*5;
    //     }
    //
    //     if($('#insurance').prop('checked')==true){
    //         total += (bikeNum-doubleBikeNum)*2;
    //         total += doubleBikeNum*4;
    //     }
    //
    //     total += parseFloat($('#basket_bike').val());
    //
    // }
    // // if($('#rent_discount').val().trim().length>=1){
    // //     total = total*(1-parseFloat($('#rent_discount').val())*0.01);
    // //     $('#rent_adjust').val(total.toFixed(2));
    // //     if($('#dropoff').prop('checked')==true){
    // //         total += bikeNum*5;
    // //     }
    // //
    // //     if($('#insurance').prop('checked')==true){
    // //         total += (bikeNum-doubleBikeNum)*2;
    // //         total += doubleBikeNum*4;
    // //     }
    // //
    // //     total += parseFloat($('#basket_bike').val());
    // //
    // // }
    if(numOrPercent) {
        if ($('#rent_adjust').val().length >= 1) {
            total = parseFloat($('#rent_adjust').val());

            if ($('#dropoff').prop('checked') == true) {
                total += bikeNum * 5;
            }

            if ($('#insurance').prop('checked') == true) {
                total += (bikeNum - doubleBikeNum) * 2;
                total += doubleBikeNum * 4;
            }

            total += parseFloat($('#basket_bike').val());

        }
    }

    if(!numOrPercent) {
        if ($('#rent_discount').val().trim().length >= 1) {
            if ($('#dropoff').prop('checked') == true) {
                total -= bikeNum * 5;
            }

            if ($('#insurance').prop('checked') == true) {
                total -= (bikeNum - doubleBikeNum) * 2;
                total -= doubleBikeNum * 4;
            }

            total -= parseFloat($('#basket_bike').val());


            total = total * (1 - parseFloat($('#rent_discount').val()) * 0.01);

            $('#rent_adjust').val(total.toFixed(2));
            if ($('#dropoff').prop('checked') == true) {
                total += bikeNum * 5;
            }

            if ($('#insurance').prop('checked') == true) {
                total += (bikeNum - doubleBikeNum) * 2;
                total += doubleBikeNum * 4;
            }

            total += parseFloat($('#basket_bike').val());

        }
    }

    total_bikeNum = bikeNum;

    $("#rent_total_label").val((total*1).toFixed(2)).css('color','green');
    // $("#rent_tips_label").val(Math.floor(total*30)/100).css('color','green');

    // $("#rent_tips_label").val((total*0.2995).toFixed(2)).css('color','green');
    if($('#coupon_bike').prop('checked')==true){
        $("#rent_tax").val((0).toFixed(2)).css('color','green');
        $("#rent_total_after_tax").val((total*1).toFixed(2)).css('color','green');

    }else{
        $("#rent_tax").val((total*0.08875).toFixed(2)).css('color','green');
        $("#rent_total_after_tax").val((total*tax).toFixed(2)).css('color','green');

    }

    if($('#rent_deposit').val()){
        var deposit = parseFloat($('#rent_deposit').val());
    }else{
        var deposit = 0;
    }
    $('#rent_total_after_tax_deposit').val((parseFloat($('#rent_total_after_tax').val())+deposit).toFixed(2)).css('color','green');

    if(total<0){
        $('#rent_rendered').val(0);
        $('#rent_change').val((total*tax*(-1)).toFixed(2));
    }else{

        // $('#rent_rendered').val(0);
        if($('#deposit_cash_checkbox').prop('checked') == true){

            // ALEX // $('#rent_rendered').val($("#rent_total_after_tax_deposit").val());
        }else{
            //ALEX  // $('#rent_rendered').val($("#rent_total_after_tax").val());
        }
        $('#rent_change').val(null);
    }



    // return total;

}

function ccSubmitCheck(){
    if(!checkName()) return false;
    if(!checkBikeNum()) return false;
    if(!checkValid()) return false;
    if(!checkMembership()) return false;
    if(!checkMembershipExpire()) return false;
    if(!checkDeposit())return false;

    removeDisable()


    var floatRegex = /^(0\.[1-9]|[1-9][0-9]{0,2}(\.[0-9]{0,2})?)$/;

    if(!$('#rent_total_label').val().trim().match(floatRegex)){
        $('#rent_total_label').notify("Please input valid price", {position: "right middle"});
        return false;
    }else{
        return true;
    }

}


function cashSubmitCheck(){

    if(!checkName()) return false;
    if(!checkBikeNum()) return false;
    if(!checkValid()) return false;
    if(!checkMembership()) return false;
    // if(!checkRenderedCash()) return false;
    if(!checkMembershipExpire()) return false;
    if(!checkDeposit())return false;
    // if(!checkDepositCCwithCash()) return false;

    removeDisable()

    var floatRegex = /^(0\.[1-9]|[1-9][0-9]{0,2}(\.[0-9]{0,2})?)$/;

    // if(!$('#rent_tips_label').val().match(floatRegex)){
    //     $('#rent_tips_label').notify("Please input valid price", {position: "bottom center"});
    //     return false;
    // }

    // var agent_price = parseFloat($('#rent_tips_label').val());
    // var total_price = (parseFloat($('#rent_total_label').val())*0.3).toFixed(2);

    // if(agent_price>total_price){
    //     $('#rent_tips_label').notify("Can not be more than $"+total_price, {position: "bottom center"});
    //     return false;
    // }else{
    //     return true;
    // }
    return true;
}

function checkName(){
    if($('#rent_customer').val().trim().length<=0){
        $('#rent_customer').notify("Please input valid name", {position:"bottom center"});
        return false;
    }else if($('#rent_customer_last').val().trim().length<=0){
        $('#rent_customer_last').notify("Please input valid name", {position:"bottom center"});
        return false;
    }
    else{
        return true;
    }
}

function checkBikeNum(){

    if($('#adult_bike').val()==0 && $('#child_bike').val()==0 && $('#tandem_bike').val()==0 && $('#road_bike').val()==0 &&
        $('#mountain_bike').val()==0){
        $('#adult_bike_label').notify("Bike number can't be 0", {position:"top center"});
        return false;
    }else{
        return true;
    }

}

function checkValid(){
    var numReg = /^\d+$/;
    var timeReg = /([01]\d|2[0-3]):([0-5]\d)/;
    var dateReg = /^\d{2}[/]\d{2}[/]\d{4}$/;
    var floatReg = /^-?\d*(\.\d+)?$/;

    if(!$('#adult_bike').val().trim().match(numReg)||!$('#child_bike').val().trim().match(numReg)||!$('#tandem_bike').val().trim().match(numReg)
        ||!$('#road_bike').val().trim().match(numReg) ||!$('#mountain_bike').val().trim().match(numReg)||!$('#trailer_bike').val().trim().match(numReg)
        ||!$('#seat_bike').val().trim().match(numReg)){

        $('#adult_bike').notify("Please input valid number", {position: "top right"});
        // console.log('cash1');

        return false;
    }else if(!$('#rent_date').val().trim().match(dateReg)){
        // console.log('cash2');

        $('#rent_date_label').notify("Please input valid date", {position: "top right"});
        return false;
    }
    // else if(!$('#rent_time').val().match(timeReg)){
    //     console.log('cash3');
    //
    //     $('#rent_time_label').notify("Please input valid time", {position: "top right"});
    //     return false;
    // }
    else if(!$('#rent_adjust').val().trim().match(floatReg)){
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
// $(function() {
//     $('.btn-default').value('#00').css('bacgkound-color','white');
//
//
// });

function clearMember(){
    $('#member_type').val(null);
    $('#rent_membership_expire').val(null);
    if($('#member_checkbox').prop('checked')==true) {
        $('#rent_customer').val(null);
        $('#rent_customer_last').val(null);
        $('#rent_email').val(null);
    }
}

function memberSearch(){
    console.log('search');
    clearMember();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/bigbike/agent/rent/membership',
        data: {'rent_membership': $('#rent_membership').val()},
        type: 'POST',
        datatype: 'JSON',
        success: function (data) {

            var data2 = JSON.parse(data)

            if(data2.order_completed=='0'){
                $('#member_type').notify("Membership Fee hasn't paid yet", {position: "top right"});
            }

            console.log("response: "+typeof(data2.customer_name));

            if($('#member_checkbox').prop('checked') == true) {
                $('#rent_customer').val(data2.customer_name);
                $('#rent_customer_last').val(data2.customer_lastname);
                $('#rent_email').val(data2.customer_email);
            }

            $('#rent_membership_expire').val(data2.enddate);
            $('#member_type').val(data2.member_type);

            var dateArr = (data2.enddate).split('-');
            var enddate = new Date(dateArr[0],dateArr[1]-1,dateArr[2]);
            enddate.setDate(enddate.getDate()+1);
            // console.log('end: '+enddate);
            var today = new Date();
            // console.log('now: '+today);

            if (enddate<today) {
                //console.log('expire');
                $('#rent_membership_expire').notify("Membership has been expired", {position: "top right"});

            }

            calculate();
        },
        error: function (data) {
            $('#rent_customer').val('No such member!');
        }
    });
    return false;
}

function checkMembership() {
    if($('#member_checkbox').prop('checked')==true && total_bikeNum >1){
        $('#adult_bike_label').notify("Membership only Applies for 1 Bike", {position: "top right"});
        return false;
    }

    return true;
}

function checkRenderedCash(){
    // if($('#rent_rendered').val().length<=0 ){
    //     $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
    //     return false;
    // }

    if($('#rent_adjust').val().length>0){
        // if(parseFloat($('#rent_adjust').val())>parseFloat($('#rent_rendered').val())){
        //     $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
        //     console.log('THIIIS');
        //     return false; ALEX
        // }
    }else{
        if($('#rent_deposit').val()){
            //cash
            if($('#deposit_cash_checkbox').prop('checked')==true){
                // if(parseFloat($('#rent_total_after_tax_deposit').val())>parseFloat($('#rent_rendered').val())){
                //     $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                //     console.log('THIS');
                //     // console.log('after_tax_deposit not enough');
                //     return false;ALEX
                // }
            }else if($('#deposit_cc_checkbox').prop('checked')==true){
                //credit
                if(parseFloat($('#rent_total_after_tax').val())>parseFloat($('#rent_rendered').val())){
                    $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                    console.log('NEW THIS');

                    // console.log('after tax not enough');
                    return false;
                }
            }
        }else{
            // if(parseFloat($('#rent_total_after_tax_deposit').val())>parseFloat($('#rent_rendered').val())){
            //     $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
            //     // console.log('after_tax_deposit not enough');ALEX
            //     return false;
            // }
        }
        // if(parseFloat($('#rent_total_after_tax_deposit').val())>parseFloat($('#rent_rendered').val())){
        //     $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
        //     console.log('not enough');
        //     return false;
        // }
        // console.log('enough');
    }
    return true;
}

function checkDeposit(){
    if($('#rent_deposit').val().length<=0){
        $('#rent_deposit').notify("Deposit", {position: "top right"});
        return false;
    }
}

function checkMembershipExpire(){

    if($('#member_checkbox').prop('checked')==true || $('#member_guest_checkbox').prop('checked')==true){
        var today = new Date();
        var enddate = new Date($('#rent_membership_expire').val());
        enddate.setDate(enddate.getDate()+1);
        console.log("end: "+enddate);
        if (enddate<today) {
            //console.log('expire');
            $('#rent_membership_expire').notify("Membership has been expired", {position: "top right"});
            return false;

        }
    }
    return true;
}

function removeDisable(){
    // console.log('change');
    $("#member_type").prop("disabled",false);

}

function checkDeposit() {
    // console.log('checkDeposit cash: '+$('#rent_deposit').val());

    if($('#rent_deposit').val().trim()){
        // console.log('checkDeposit');
        if(!($('#deposit_cc_checkbox').prop('checked')==true) && !($('#deposit_cash_checkbox').prop('checked')==true)){
            $('#rent_deposit').notify("Select a payment method", {position: "top right"});
            return false;
        }
    }
    return true;
}

function checkDepositCCwithCash(){
    if($('#rent_deposit').val() && $('#deposit_cc_checkbox').prop('checked')==true){
        if($('#rent_rendered').val().length<=0){
            $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
            console.log('THIS ONE');

            return false;
        }

        if($('#rent_adjust').val().length>0){
            if(parseFloat($('#rent_adjust').val())>parseFloat($('#rent_rendered').val())){
                $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                console.log('THIS TWO');
                return false;
            }
        }else{
            if(parseFloat($('#rent_total_after_tax').val())>parseFloat($('#rent_rendered').val())){
                $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                console.log('not enough');
                console.log('THIS 3');

                return false;
            }
        }
    }

    return false;
}
