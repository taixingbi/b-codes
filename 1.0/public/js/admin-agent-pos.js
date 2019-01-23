$(document).ready(function() {

    $('#datepickermonth').datepicker( {
        monthNamesShort: [ "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12" ],
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'M/yy',
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
            // $('#datepickerweek').val(null);
            // $('#datepickerday').val(null);
            // window.location.assign('/bigbike/agent/pos/cashier-detail/month/'+$('#datepickermonth').val().replace(new RegExp('/', 'g'),'-'));
            $('#day_checkbox').prop('checked',false);
            $('#week_checkbox').prop('checked',false);
            $('#month_checkbox').prop('checked',true);

        }
    });

    $("#datepickermonth").datepicker().datepicker("setDate", new Date());


    // ui-datepicker-calendar
    $("#datepickermonth").focus(function() {
        $('.ui-datepicker-calendar').css('display','none');
        $('.ui-datepicker').css('top','500px');

    });

    // $("#datepickermonth").datepicker().datepicker("setDate", new Date());
    //

    //pick date
    $('#datepickerday').datepicker({

        onClose: function(dateText, inst) {
            // $('#datepickerweek').val(null);
            // $('#datepickermonth').val(null);

        }
    });

    $("#datepickerday").datepicker().datepicker("setDate", new Date());



    $('#datepickerweek').val($.datepicker.iso8601Week(new Date()));

    // //pick week
    // $('#datepickerweek').datepicker($.datepicker.iso8601Week(new Date(dateText)));

    $('#datepickerweek').datepicker({
        onSelect: function (dateText, inst) {
            $('#datepickerweek').val($.datepicker.iso8601Week(new Date(dateText)));
            // window.location.assign('/bigbike/agent/pos/cashier-detail/week/'+$('#datepickerweek').val());
            $('#day_checkbox').prop('checked',false);
            $('#week_checkbox').prop('checked',true);
            $('#month_checkbox').prop('checked',false);

        }
    });


    // $( "#datepickerweek" ).datepicker({
    //     showWeek: true,
    //     firstDay: 1
    // });
    $(".datepicker").datepicker().datepicker("setDate", new Date());


    $('tr').click(function() {
        var href = $(this).attr("href");
        console.log('href: '+href);
        if(href) {
            window.location = href;
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $("#day_checkbox").change(function() {
        if($(this).prop('checked') == true) {
            //console.log("Checked Box Selected");
            $('#week_checkbox').prop('checked',false);
            $('#month_checkbox').prop('checked',false);
        }
    });

    $("#week_checkbox").change(function() {
        if($(this).prop('checked') == true) {
            //console.log("Checked Box Selected");
            $('#day_checkbox').prop('checked',false);
            $('#month_checkbox').prop('checked',false);
        }
    });

    $("#month_checkbox").change(function() {
        if($(this).prop('checked') == true) {
            //console.log("Checked Box Selected");
            $('#week_checkbox').prop('checked',false);
            $('#day_checkbox').prop('checked',false);
        }
    });

    $("#datepickerday").change(function() {
        console.log("Checked Box Selected");
        $('#day_checkbox').prop('checked',true);
        $('#week_checkbox').prop('checked',false);
        $('#month_checkbox').prop('checked',false);

    });



    // $(".update-agent-com").click(function(e) {
    //     e.preventDefault();
    //     $.ajax({
    //         url: '/bigbike/admin/pos/agent/update',
    //         type: 'post',
    //         data: {
    //             admin_date: $('#admin_date').val(),
    //             admin_agent: $('#admin_agent').val()
    //         },
    //         success: function (data) {
    //             //console.log("success: "+data);
    //             passData(data);
    //         },error:function(e){
    //             var errors = e.responseJSON;
    //             console.log("error!!!! "+ e);
    //         }
    //     });
    // });


});

function updateAgentCom(fullname, id) {

    $.ajax({
        url: '/bigbike/agent/update-com',
        type: 'post',
        data: {
            fullname: fullname,
            value: $('#up'+id).val()
        },
        success: function (data) {
            swal(data);

        },error:function(e){
            var errors = e.responseJSON;
            console.log("error!!!! "+ e);
        }
    });
}

function deleteAgent(){
    console.log('delete tour');
    ids = "";

    $(".delete_agent:checked").each(function(){

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
        url: '/bigbike/agent/delete-agents',
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

function check(){
    if($('#day_checkbox').prop('checked') == false && $('#week_checkbox').prop('checked') == false && $('#month_checkbox').prop('checked') == false){
        $('#day_checkbox').notify("Please choose date", {position:"right middle",autoHideDelay:2000});
        return false;
    }
    return true;
}
