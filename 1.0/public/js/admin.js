$(document).ready(function() {

    $("#month").change(function() {
       if($(this).prop('checked') == true) {
           $("#year").prop('checked',false);
       }
    });
    $("#year").change(function() {
        if($(this).prop('checked') == true) {
            $("#month").prop('checked',false);
        }
    });

    $('.datepicker').datepicker( {
        monthNamesShort: [ "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12" ],
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'M/yy',
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });

    // $( ".datepicker" ).datepicker({
    //     minDate: new Date()
    // });
    //
    $(".datepicker").datepicker().datepicker("setDate", new Date());


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function getReport() {

    //console.log("getReport");

    $.ajax({
        url: '/bigbike/admin/agent',
        type: 'post',
        data: {
            admin_date: $('#admin_date').val(),
            admin_agent: $('#admin_agent').val()
        },
        success: function (data) {
            //console.log("success: "+data);
            passData(data);
        },error:function(e){
            var errors = e.responseJSON;
            console.log("error!!!! "+ e);
        }
    });
}

function passData(data){
    $('#pay_form').css('display','block');
    $('#date_pay').val($('#admin_date').val());
    $('#agent_pay').val($('#admin_agent').val());

    $('#agent_cash_pay').val((parseFloat(data['agent_rent_cash'])+parseFloat(data['agent_tour_cash'])).toFixed(2));
    //$('#agent_tour_cash').val(parseFloat(data['agent_tour_cash']).toFixed(2));

    var cc = parseFloat(data['agent_rent_cc'])+parseFloat(data['agent_tour_cc'])
    $('#agent_cc_pay').val((cc*0.7).toFixed(2));
    $('#agent_commission_pay').val((cc*0.3).toFixed(2));

    // $('#rent_agent_total_pay').val((parseFloat(data['total_price_after_tax'])*0.2995).toFixed(2));

}

function form_check(){
    //console.log('check');
    if($("#month").prop('checked') == false && $("#year").prop('checked') == false){
        $('#month').notify("Select month or year first", {position: "top right"});
        return false;
    }else{
        return true;
    }
}