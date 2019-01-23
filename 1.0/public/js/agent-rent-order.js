

function calculateRent() {

    console.log("calculateRent");

    if(!checkName()) return;
    if(!checkBikeNum()) return;
    if(!check24hours()) return;
    if(!checkValid()) return;

    $.ajax({
        url: '/bigbike/agent/rent/order-cal',
        type: 'get',
        data: {
            rent_date: $('#rent_date').val(),
            rent_time: $('#rent_time').val(),
            adult_bike: $('#adult_bike').val(),
            child_bike: $('#child_bike').val(),
            tandem_bike: $('#tandem_bike').val(),
            road_bike: $('#road_bike').val(),
            mountain_bike: $('#mountain_bike').val(),
            trailer: $('#trailer').val(),
            seat: $('#seat').val(),
            basket: $('#basket').val(),
            insurance: $('#insurance').is(':checked')?1:0,
            dropoff: $('#dropoff').is(':checked')?1:0,
            rent_duration: $('#rent_duration').val(),
        },
        success: function (data) {
            console.log("success: "+data);
            passRentData(data);
        },error:function(e){
            var errors = e.responseJSON;
            console.log("error!!!! "+ e);
        }
    });
}


function checkName(){
    if($('#rent_customer').val().length<=0){
        $('#rent-submit').notify("Please input valid name", {position:"right middle"});
        return false;
    }else{
        return true;
    }
}

function checkBikeNum(){
    if($('#adult_bike').val()==0 && $('#child_bike').val()==0 && $('#tandem_bike').val()==0 && $('#road_bike').val()==0 &&
        $('#mountain_bike').val()==0 ){
        $('#rent-submit').notify("Bike number can't be 0", {position:"right middle"});
        return false;
    }else{
        return true;
    }
}

function check24hours(){
    if($('#rent_duration').val()=='24 hours'){
        if($('#tandem_bike').val()!=0 || $('#road_bike').val()!=0 ||
            $('#mountain_bike').val()!=0 || $('#trailer').val()!=0 || $('#seat').val()!=0) {
            // $('#tandem_bike').val(0);
            // $('#road_bike').val(0);
            // $('#mountain_bike').val(0);
            // $('#trailer').val(0);
            // $('#seat').val(0);

            $('.c24hours').val(0);
            $('.c24hours-label').notify("Not available for 24 hours", {position: "right middle"});

            // $('#rent_tandem_label').notify("Not available for 24 hours", {position: "right middle"});
            // $('#rent_road_label').notify("Not available for 24 hours", {position: "right middle"});
            // $('#rent_mountain_label').notify("Not available for 24 hours", {position: "right middle"});
            // $('#rent_trailer_label').notify("Not available for 24 hours", {position: "right middle"});
            // $('#rent_seat_label').notify("Not available for 24 hours", {position: "right middle"});

            return false;
        }else{
            return true;
        }
    }else{
        return true;
    }
}

function checkValid(){
    var numReg = /^\d+$/;
    var timeReg = /([01]\d|2[0-3]):([0-5]\d)/;
    var dateReg = /^\d{2}[/]\d{2}[/]\d{4}$/;


    if(!$('#adult_bike').val().match(numReg)||!$('#child_bike').val().match(numReg)||!$('#tandem_bike').val().match(numReg)
        ||!$('#road_bike').val().match(numReg) ||!$('#mountain_bike').val().match(numReg)||!$('#trailer').val().match(numReg)
        ||!$('#seat').val().match(numReg)){


        $('#rent-submit').notify("Please input valid number", {position: "right middle"});

        return false;
    }else if(!$('#rent_date').val().match(dateReg)){
        $('#rent_date_label').notify("Please input valid date", {position: "right middle"});
        return false;
    } else if(!$('#rent_time').val().match(timeReg)){
        $('#rent_time_label').notify("Please input valid time", {position: "right middle"});
        return false;
    }else{

        return true;
    }
}

function passRentData(data){
    $('#rent-pay-form').css('display','block');
    $('#rent_total_pay').val(data['total_price_after_tax']);
    $('#rent_agent_total_pay').val((parseFloat(data['total_price_after_tax'])*0.2995).toFixed(2));

    $('#rent_date_pay').val($('#rent_date').val());
    $('#rent_customer_pay').val($('#rent_customer').val());
    $('#rent_email_pay').val($('#rent_email').val());
    $('#rent_time_pay').val($('#rent_time').val());
    $('#adult_bike_pay').val($('#adult_bike').val());
    $('#child_bike_pay').val($('#child_bike').val());
    $('#tandem_bike_pay').val($('#tandem_bike').val());
    $('#road_bike_pay').val($('#road_bike').val());
    $('#mountain_bike_pay').val($('#mountain_bike').val());
    $('#trailer_pay').val($('#trailer').val());
    $('#seat_pay').val($('#seat').val());
    $('#basket_pay').val($('#basket').val());
    $('#insurance').is(':checked')?$('#insurance_pay').prop('checked',true):$('#insurance_pay').prop('checked',false);
    $('#dropoff').is(':checked')?$('#dropoff_pay').prop('checked',true):$('#dropoff_pay').prop('checked',false);
    $('#rent_duration_pay').val($('#rent_duration').val());
}

function cashSubmitCheck(){
    var floatRegex = /^(0\.[1-9]|[1-9][0-9]{0,2}(\.[0-9]{0,2})?)$/;

    if(!$('#rent_agent_total_pay').val().match(floatRegex)){
        $('#rent_agent_total_label').notify("Please input valid price", {position: "right middle"});
        return false;
    }

    var agent_price = parseFloat($('#rent_agent_total_pay').val());
    var total_price = (parseFloat($('#rent_total_pay').val())*0.3).toFixed(2);

    if(agent_price>total_price){
        $('#rent_agent_total_label').notify("Can not be more than $"+total_price, {position: "right middle"});
        return false;
    }else{
        return true;
    }
}
