$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#inventory_barcode").change(function(){

        $.ajax({
            url: '/bigbike/agent/sports/barcode',
            data: {'inventory_barcode': $('#inventory_barcode').val()},
            type: 'POST',
            datatype: 'JSON',
            success: function (data) {
                var jsonData = JSON.parse(data);
                // console.log("data: "+jsonData);
                if(jsonData==null){
                    $('#inventory_barcode').notify("No Such Barcode", {position:"right middle",autoHideDelay:2000});
                }else {
                    $('#inventory_name').val(jsonData['name']);
                    $('#inventory_size').val(jsonData['size']);
                    $('#inventory_price').val(jsonData['price']);
                    $('#inventory_total_price').val(parseFloat(jsonData['price'])*parseInt(jsonData['quantity']));
                    $('#inventory_total_price_tax').val((parseFloat(jsonData['price'])*1.08875).toFixed(2));
                    $('#inventory_cat').val(jsonData['category']);
                    $('#max_qua').val(jsonData['quantity']);
                    $('#inventory_id').val(jsonData['id']);
                }
            },
            error: function (data) {
                // console.log('data: '+data);
                $('#inventory_barcode').notify("No Such Barcode", {position:"right middle",autoHideDelay:2000});
            }
        });
    });

    $('.inventory-btn').click(function (e) {
        // idClicked = 'add';
        idClicked = e.target.id;
        console.log('button: '+idClicked);
        e.preventDefault();

        if(idClicked=='add' && $('#inventory_barcode').val().trim().length==0){
            $('#addWithoutBarcode').notify("click this button if it has no barcode", {position:"right middle",autoHideDelay:2000});
            return false;
        }

        if($('#inventory_name').val().trim().length==0){
            // console.log('no name');
            $('#inventory_name').notify("Please input Product Name", {position:"right middle",autoHideDelay:2000});
            return false;
        }

        var numberRegex = /^\d{0,2}(\.\d{0,2}){0,1}$/;

        if($('#inventory_price').val().trim().length==0 ){
            $('#inventory_price').notify("Please input valid price", {position:"right middle",autoHideDelay:2000});
            return false;
        }

        if($('#inventory_qua').val().trim().length==0 || parseInt($('#inventory_qua').val().trim())==0){
            $('#inventory_qua').notify("Please input valid quantity", {position:"right middle",autoHideDelay:2000});
            return false;
        }

        if(parseInt($('#inventory_qua').val().trim())>parseInt($('#max_qua').val().trim())){
            $('#inventory_qua').notify("Please input valid quantity", {position:"right middle",autoHideDelay:2000});
            return false;
        }




        $.ajax({
            type: 'post',
            url: '/bigbike/agent/sports/form-sub',
            // data: $('#payment-form').serialize(),
            data: {'button': idClicked,
                'inventory_id':$('#inventory_id').val(),
                'price':$('#inventory_price').val(),
                'name':$('#inventory_name').val(),
                'inventory_cat':$('#inventory_cat').val(),
                'inventory_size':$('#inventory_size').val(),
                'quantity':$('#inventory_qua').val()

            },

            success: function (data) {
                // console.log('form was submitted: '+data);
                setTimeout(function(){ window.location.reload(); }, 500);
            },
            error: function (data) {
                console.log('data: '+data);
                // $('#rent_agent').val('No such member!');
            }
        });
    });

    calculate();

    $('#rent_rendered').focus(function (e) {

        if(parseInt($('#rent_rendered').val().trim())==0){
            $('#rent_rendered').val(null);
        }

    });

    $('#rent_rendered').keyup(function (e) {

        var customer = parseFloat($( "#rent_rendered" ).val());
        var agent = parseFloat($( "#rent_total_after_tax" ).val());
        if(customer>=agent){
            $("#rent_change").val((customer-agent).toFixed(2)).css('color','green');
        }else{
            $("#rent_change").val(null);
        }
    });

    var numOrPercent = false;

    $('#rent_adjust').keyup(function() {

        if(!$('#rent_adjust').val()){
            $('#rent_discount').val(null);
        }else{
            //var total = calculate();
            // console.log("adjust: "+$('#rent_adjust').val());
            // console.log("sum: "+totalsum);
            // originalPriceBefore = parseFloat($('#rent_adjust').val());
            // if(totalsum!=0){
            $('#rent_discount').val((100*(1-parseFloat($('#rent_adjust').val())/parseFloat(originalPriceBefore))).toFixed(0));
            // }
            // $('#rent_discount').val((100*(parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(0)+'%');
            // $('#rent_discount').val((100*(1-parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(0));
            // if(parseFloat($('#rent_discount').val())==0){
            //     $('#rent_discount').val(0);
            // }
            $('#rent_total_label').val($('#rent_adjust').val());
            // $('#rent_tax').val((parseFloat($('#rent_adjust').val())*(tax-1)).toFixed(2));
            // $("#rent_total_after_tax").val((parseFloat($('#rent_adjust').val()*tax).toFixed(2))).css('color','green');
        }
        calculate();
    });

    $('#rent_discount').keyup(function() {
        numOrPercent = false;

        if(!$('#rent_discount').val()){
            // calculate();
            $('#rent_adjust').val(null);
        }else{
            //var total = calculate();

            $('#rent_adjust').val(((1-(parseFloat($('#rent_discount').val())/100))*parseFloat(originalPriceBefore)).toFixed(2));
            // $('#rent_total_label').val($('#rent_adjust').val());
        }
        calculate();
    });

    $('.inventory').keyup(function() {

        if($('#inventory_price').val().trim().length!=0 && $('#inventory_qua').val().trim().length!=0){
            // calculate();
            $('#inventory_total_price').val((parseFloat($('#inventory_price').val().trim())*$('#inventory_qua').val()).toFixed(2));
            $('#inventory_total_price_tax').val((parseFloat($('#inventory_total_price').val().trim())*1.08875).toFixed(2));

        }else{

            $('#inventory_total_price').val(0)
        }

    });



});

tax = 0.08875;
function calculate(){
    if($('#rent_adjust').val()){
        $('#rent_tax').val((parseFloat($('#rent_adjust').val()) * tax).toFixed(2));
        $('#rent_total_after_tax').val((parseFloat($('#rent_adjust').val()) * (1 + tax)).toFixed(2));

    }else {
        console.log('after: '+originalPriceBefore * (1 + tax));
        $('#rent_total_label').val((originalPriceBefore));
        $('#rent_tax').val((originalPriceBefore * tax).toFixed(2));
        $('#rent_total_after_tax').val((originalPriceBefore * (1 + tax)).toFixed(2));
    }
}

function ccSubmitCheck(){
    //if(!checkName()) return false;

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