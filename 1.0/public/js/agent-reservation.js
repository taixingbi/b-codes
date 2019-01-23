$(function() {

    $('tr').click(function() {
        var href = $(this).attr("href");
        if(href) {
            window.location = href;
        }
    });

    $('.extra').hide();

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

    if($('#bike_insurance').val()=='1'){
        $('#insurance_label').hide();
    }

    if($('#bike_dropoff').val()=='1'){
        $('#drop_off_label').hide();
    }

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

    $('#rent_adjust').keyup(function() {

        if(!$('#rent_adjust').val()){
            $('#rent_discount').val(null);
        }else{
            //var total = calculate();
            console.log("adjust: "+$('#rent_adjust').val());
            console.log("sum: "+totalsum);

            $('#rent_discount').val((100*(parseFloat($('#rent_adjust').val())/parseFloat(totalsum))).toFixed(2)+'%');
            $('#rent_total_label').val($('#rent_adjust').val());
            // $('#rent_tax').val((parseFloat($('#rent_adjust').val())*(tax-1)).toFixed(2));
            // $("#rent_total_after_tax").val((parseFloat($('#rent_adjust').val()*tax).toFixed(2))).css('color','green');
        }
        calculate();
    });

    $('#rent_discount').keyup(function() {

        if(!$('#rent_discount').val()){
            calculate();
            $('#rent_adjust').val(null);
        }else{
            //var total = calculate();
            $('#rent_adjust').val((parseFloat($('#rent_discount').val())*parseFloat(totalsum)/100).toFixed(2));
            $('#rent_total_label').val($('#rent_adjust').val());
        }
    });

});

var totalsum;


function calculate(){
    console.log('cal');
    var total = 0;
    var tax = 1.08875;
    var bikeNum = parseInt($('#bike_total').val());
    total += parseInt($('#basket_bike').val());
    if($('#dropoff').prop('checked')==true){
        total += bikeNum*5;
    }

    if($('#insurance').prop('checked')==true){
        total += bikeNum*2;
    }
    totalsum = (total*tax).toFixed(2);

    // console.log("total: "+total);
    $("#rent_total_label").val((total*1).toFixed(2)).css('color','green');
    // $("#rent_tips_label").val(Math.floor(total*30)/100).css('color','green');

    // $("#rent_tips_label").val((total*0.2995).toFixed(2)).css('color','green');
    $("#rent_tax").val((total*0.08875).toFixed(2)).css('color','green');
    $("#rent_total_after_tax").val((total*tax).toFixed(2)).css('color','green');

}

function ccSubmitCheck(){
    if($('#rent_total_after_tax').val()=='0'){
        $('#mer').notify("Click Cash Button Instead", {position: "top right"});
        return false;
    }
    return true;
}

function cashSubmitCheck(){

    // if(!checkValid()) return false;
    if(!checkRenderedCash()) return false;
    return true;
}

function checkRenderedCash(){
    if($('#rent_rendered').val().length<=0){
        $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
        return false;
    }

    if($('#rent_adjust').val().length>0){
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