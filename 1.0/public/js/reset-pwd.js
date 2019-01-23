function checkPwd(){
    if($('#password').val()!=$('#password2').val()){
        //console.log('not match');
        $('#password2').notify("Please input the same password to confirm", {position: "right middle"});
        return false;
    }else{
        return true;
    }

}