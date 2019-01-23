$(function() {

    $('tr').click(function() {
        var href = $(this).attr("href");
        // console.log('href: '+href);
        if(href) {
            window.location = href;
        }
    });

    // $('.extra').hide();
    //
    // $( ".field" ).keyup(function() {
    //     //console.log( "Handler for .change() called." );
    //     calculate();
    // });
    //
    // $('.ui-spinner-button').click(function () {
    //     //console.log("Handler for .change() called.");
    //     calculate();
    // });
    //
    // $('#basket').click(function () {
    //     calculate();
    // });
    // $('#insurance').click(function () {
    //     calculate();
    // });
    // $('#dropoff').click(function () {
    //     calculate();
    // });
    //
    //
    // if($('#bike_insurance').val()=='1'){
    //     console.log('yes');
    //     $('#insurance_label').hide();
    //
    // }else{
    //     console.log('no');
    // }
    //
    $('#rent_rendered').keyup(function() {

        if(!$('#rent_rendered').val()){
            // $('#rent_change').val(null);
            if(parseFloat($('#rent_total_after_tax').val().trim())==0){
                $('#rent_change').val(null);
            }
        }else{
            var rent_rendered = parseFloat($('#rent_rendered').val());
            var rent_total_after_tax = $('#rent_total_after_after_tax').val();

            if(rent_total_after_tax<0){
                rent_total_after_tax *= (-1);
            }
            console.log("value: "+rent_total_after_tax);
            if(rent_rendered>=rent_total_after_tax){
                $('#rent_change').val((rent_rendered-rent_total_after_tax).toFixed(2));
            }else{
                $('#rent_change').val(null);
            }
        }
    });


    $('#rent_total_after_tax').keyup(function() {

        if(!$('#rent_total_after_tax').val()){
            // $('#rent_change').val(null);
            $('#rent_total_after_after_tax').val(null);

        }else{
            $('#rent_total_after_after_tax').val((parseFloat($('#rent_total_after_tax').val())*1.0875).toFixed(2));
        }
    });

    $( "#rent_rendered" ).focus(function() {
        if($('#rent_rendered').val()=="0"){
            $('#rent_rendered').val(null);
        }
    });


    if($('#rent_total_after_tax').val()==' 0 '){
        $('#rent_rendered').val(0);
        //calculate();
    }

    $("#selectAllRent").change(function() {
        console.log("here");

        if($(this).prop('checked') == true) {
            console.log("Checked Box Selected");
            $('.delete_rent').prop('checked',true);

        } else {
            console.log("Checked Box deselect");
            $('.delete_rent').prop('checked',false);
        }
    });

    $("#selectAll").change(function() {
        console.log("here");
        if($(this).prop('checked') == true) {

            $('.delete_tour').prop('checked', true);

        } else {
            //console.log("Checked Box deselect");
            $('.delete_tour').prop('checked',false);
        }
    });


});

function releasePPCheck(){
    if(!checkNumValid()) return false;
    var tmp = parseFloat($('#rent_total_after_tax').val());
    if(tmp>0){
        $('#rent_total_after_tax').notify("Please Pay The Late Fee", {position: "top left"});
    }
    tmp *= -1;

    console.log('release: '+tmp);
    var refund_amt = parseFloat($('#refund_amt').val());
    if(refund_amt>tmp){
        $('#rent_total_after_tax').notify("Refund Amount can't not larger than Total amount", {position: "top left"});
        return false;
    }

    return true;
}

function checkNumValid(){
    var floatReg = /^-?\d*(\.\d+)?$/;
    if(!$('#refund_amt').val().match(floatReg)){
        // console.log('cash4');

        $('#refund_amt').notify("Please input valid number", {position: "top right"});
        return false;
    }
    return true;
}

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

    console.log("total: "+total);
    $("#rent_total_label").val((total*1).toFixed(2)).css('color','green');
    // $("#rent_tips_label").val(Math.floor(total*30)/100).css('color','green');

    // $("#rent_tips_label").val((total*0.2995).toFixed(2)).css('color','green');
    $("#rent_tax").val((total*0.08875).toFixed(2)).css('color','green');
    $("#rent_total_after_tax").val((total*tax).toFixed(2)).css('color','green');

}

function cashSubmitCheck(){

    if(!checkRenderedCash()) return false;

    return true;
}

function checkRenderedCash(){
    if($('#rent_rendered').val().length<=0){
        $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
        return false;
    }

    if(parseFloat($('#rent_total_after_tax').val())>parseFloat($('#rent_rendered').val())){
        $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
        // console.log('not enough');
        return false;
    }
    // console.log('enough');

    return true;
}

function deleteTour(){
    console.log('delete tour');
    ids = "";

    $(".delete_tour:checked").each(function(){

        id = $(this).attr("id");
        ids = ids+","+id;

        // tour_id = $(this).attr("tour_id");
        // tour_ids = tour_ids+","+tour_id;

    });

    // console.log('ids: '+ids.length);
    if(ids.length==0){
        swal('Select a Bike Tour at least');
        return false;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/bigbike/agent/tour/delete',
        type: 'POST',
        data: {
            ids:ids
        },
        success: function(data){
            swal(data);
            setTimeout(function(){ window.location.reload(); }, 1000);

        },error:function(e){
            // var errors = e.responseJSON;
            swal(e);
        }
    });
    return false;
}


function deleteRent(){
    console.log('delete tour');
    ids = "";

    $(".delete_rent:checked").each(function(){

        id = $(this).attr("id");
        ids = ids+","+id;

        // tour_id = $(this).attr("tour_id");
        // tour_ids = tour_ids+","+tour_id;

    });

    // console.log('ids: '+ids.length);
    if(ids.length==0){
        swal('Select a Bike Tour at least');
        return false;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/bigbike/agent/rent/delete',
        type: 'POST',
        data: {
            ids:ids
        },
        success: function(data){
            swal(data);
            setTimeout(function(){ window.location.reload(); }, 500);

        },error:function(e){
            // var errors = e.responseJSON;
            swal(e);
        }
    });
    return false;
}
