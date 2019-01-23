function checkGeneral(){
    console.log('gen check');
    if($('#customer_first').val().length<=0){
        $('#customer_first').notify("Please input valid name", {position:"bottom center"});
        return false;
    }
    if($('#customer_last').val().length<=0){
        $('#customer_last').notify("Please input valid name", {position:"bottom center"});
        return false;
    }

    var numReg = /^\d+$/;
    if(!$('#customer_phone').val().match(numReg)){
        $('#customer_phone').notify("Please input valid phone number", {position:"bottom center"});
        return false;
    }
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;


    if($('#customer_email').val().length<=0 || !re.test($('#customer_email').val())){
        $('#customer_email').notify("Please input valid email", {position:"bottom center"});
        return false;
    }

    var dateReg = /^\d{2}[/]\d{2}[/]\d{4}$/;
    if(!$('#customer_date').val().match(dateReg)){
        $('#customer_date').notify("Please input valid date", {position: "bottom center"});
        return false;
    }

    return true;
}


function cashSubmitCheck(){

    return checkGeneral();
}

function ccSubmitCheck() {

    return checkGeneral();
}