// $(document).ready(function() {
//
//     // $( ".datepicker" ).datepicker({
//     //     minDate: 0,
//     //     maxDate: 0
//     // });
//
//     $(".datepicker").datepicker().datepicker("setDate", new Date());
//
//
//
//
//     $( "#datepicker" ).value = new Date();
//
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });
//
//
//
// });

$(document).ready(function() {

    $( ".datepicker" ).datepicker({
        //minDate: new Date()-100
    });

    $(".datepicker").datepicker().datepicker("setDate", new Date());


    $( "#datepicker" ).value = new Date();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('tr').click(function() {
        var href = $(this).attr("href");
        if (href) {
            window.location = href;
        }

    });

    $('tr').on('click', 'td:last-child', function(e) {
        e.stopPropagation();
    });

    $(".checkbox-all").change(function() {
        if($(this).prop('checked') == true) {
            //console.log("Checked Box Selected");
            $('.checkbox-single').prop('checked',true);
        } else {
            //console.log("Checked Box deselect");
            $('.checkbox-single').prop('checked',false);
        }
    });

    $("#set-paid").on("click",function(){

        ids = "";
        tour_ids = '';
        $(".checkbox-single:checked").each(function(){

            id = $(this).attr("id");
            ids = ids+","+id;

            tour_id = $(this).attr("tour_id");
            tour_ids = tour_ids+","+tour_id;

        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: '/bigbike/agent/pos/agent/paid',
            data: {
                ids:ids,
                tour_ids: tour_ids
            },
            success: function(data){
                swal(data);
                setTimeout(function(){ window.location.reload(); }, 500);
            },error:function(e){
                var errors = e.responseJSON;
                console.log("error!!!! "+ e);
            }
        });

    });


});
