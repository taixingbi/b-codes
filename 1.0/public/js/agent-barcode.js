$('#rent_barcode').change(function(){

    var barcode = $("#rent_barcode").val();
    // console.log("scan: "+barcode);

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
                // swal(data['response']);
                // setTimeout(function(){ window.location.replace(data['url']); }, 500);
                window.location.replace(data['url']);
            }else if(data['type']=='returnDel'){
                swal(data['response']);
            }

        }
    });
});