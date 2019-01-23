

function checkTourType(place){

    if(place.value=="Central Park" || place.value=="Brooklyn Bridge"){
        $('#public').css('display','block');
    }else{
        if($('#tour_type').val()=="public(2h)") {
            $('#tour_type').val('private(2h)');
        }
        $('#public').css('display','none');
    }
}


function calculateTour() {

    console.log("calculateTour");

    if(!checkTourName()) return;
    if(!checkTourNum()) return;

    $.ajax({
        url: '/bigbike/agent/tour/order-cal',
        type: 'get',
        data: {
            tour_adult: $('#tour_adult').val(),
            tour_child: $('#tour_child').val(),
            tour_type: $('#tour_type').val()
        },
        success: function (data) {
            console.log("success: "+data);
            passTourData(data);
        },error:function(e){
            var errors = e.responseJSON;
            console.log("error!!!! "+ e);
        }
    });
}

function checkTourName(){
    if($('#tour_customer').val().length<=0){
        $('#tour-submit').notify("Please input valid name", {position:"right middle"});
        return false;
    }else{
        return true;
    }
}

function checkTourNum(){
    if($('#tour_adult').val()==0 && $('#tour_child').val()==0){
        $('#tour-submit').notify("Tour number can't be 0", {position:"right middle"});
        return false;
    }else{
        return true;
    }
}

function passTourData(data){
    $('#tour-pay-form').css('display','block');
    $('#tour_customer_pay').val($('#tour_customer').val());
    $('#tour_email_pay').val($('#tour_email').val());
    $('#tour_place_pay').val($('#tour_place').val());
    $('#tour_date_pay').val($('#tour_date').val());
    $('#tour_time_pay').val($('#tour_time').val());
    $('#tour_type_tour_pay').val($('#tour_type').val());
    $('#tour_adult_pay').val($('#tour_adult').val());
    $('#tour_child_pay').val($('#tour_child').val());
    $('#tour_total_people_pay').val(data['total_people']);
    $('#tour_duration_pay').val($('#tour_duration').val());
    $('#tour_total_pay').val(data['total_tour_price_after_tax']);
    $('#tour_agent_total_pay').val((parseFloat(data['total_tour_price_after_tax'])*0.2995).toFixed(2));
}

function cashSubmitCheck(){
    var floatRegex = /^(0\.[1-9]|[1-9][0-9]{0,2}(\.[0-9]{0,2})?)$/;

    if(!$('#tour_agent_total_pay').val().match(floatRegex)){
        $('#tour_agent_total_pay').notify("Please input valid price", {position: "right middle"});
        return false;
    }

    var agent_price = parseFloat($('#tour_agent_total_pay').val());
    var total_price = (parseFloat($('#tour_total_pay').val())*0.3).toFixed(2);

    if(agent_price>total_price){
        $('#tour_agent_total_label').notify("Can not be more than $"+total_price, {position: "right middle"});
        return false;
    }else{
        return true;
    }
}
