function checkForm(){

    if($('#location').val().length<=0){
        // console.log('not');
        $('#location').notify("Select a valid location", {position: "right middle"});
        return false;
    }
    // console.log('not out: '+$('#location').val());

    return true;
}
