$(document).ready(function() {


    $( ".datepicker" ).datepicker({
        minDate: new Date()
    });

    $(".datepicker").datepicker().datepicker("setDate", new Date());


    $('.spinner').spinner({
        min: 0,
        max: 20,
        step: 1
    });

    $('.tour-spinner').spinner({
        min: 0,
        max: 15,
        step: 1
    });

    $('.timepicker').timepicker({
        timeFormat: 'H:mm',
        interval: 60,
        minTime: '8',
        maxTime: '19:00',
        defaultTime: '11',
        startTime: '10:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

    $( "#datepicker" ).value = new Date();


    $( ".tour-spinner" ).change(function () {
        console.log("change");
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#rent_barcode').change(function(){

        var barcode = $("#rent_barcode").val();
        console.log("scan: "+barcode);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            cache: false,
            url: '/bigbike/agent/barcode-scan',
            data: "barcode=" + barcode,
            success: function(data) {
                // swal(data);
                // console.log(data['id']);
                console.log(data['url']);
                if(data['type']=='error'){
                    swal(data['response']);
                } else if(data['type']=='reservation' || data['type']=='return'){
                    window.location.replace(data['url']);
                }

            }
        });
    });

    $('#customer_date').change(function(){

        updateDate();

    });

    $('#member_type').change(function(){

        updateDate();

    });

    $('#customer_expire_date').val($("#customer_date").val());

});

function updateDate(){
    var barcode = $("#customer_date").val();
    if($('#member_type').val().trim()=='Day Pass/$5'){
        $('#customer_expire_date').val(barcode);
    }else if($('#member_type').val().trim()=='Month Pass/$45'){
        barcode = barcode.split('/');
        // console.log("arr: "+barcode);
        if(parseInt(barcode[0])<11) {
            var d = new Date(barcode[2], barcode[0], barcode[1]);
            d.setMonth(d.getMonth() + 1);
            var month = d.getMonth()<10?('0'+d.getMonth()):d.getMonth();
            $('#customer_expire_date').val(month+'/'+d.getDate()+'/'+d.getFullYear());
        }
        else {
            barcode[0] = '01';
            barcode[2] = parseInt(barcode[2])+1;
            $('#customer_expire_date').val(barcode[0]+'/'+barcode[1]+'/'+barcode[2]);
        }

    }else if($('#member_type').val().trim()=='Annual Pass/$129'){
        barcode = barcode.split('/');
        var d = new Date(barcode[2], barcode[0], barcode[1]);
        d.setFullYear(d.getFullYear() + 1);
        var month = d.getMonth()<10?('0'+d.getMonth()):d.getMonth();
        $('#customer_expire_date').val(month+'/'+d.getDate()+'/'+d.getFullYear());
    }
}

