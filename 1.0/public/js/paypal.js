// (function($){
//
//     var $cc_num = $("#cc_number");
//     var $cc_exp = $("#cc_expiration");
//     var $cc_cvv = $("#cc_cvc");
//     var card_type = null;
//
//     $cc_num.payment("formatCardNumber");
//     $cc_exp.payment("formatCardExpiry");
//     $cc_cvv.payment("formatCardCVC");
//
//     $cc_num.on("input", function(){
//         var current_num = $(this).val();
//         card_type = $.payment.cardType(current_num);
//         $('#cc_type').val(card_type);
//         var current_card = $("img[src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/"+ card_type +".png']");
//         var card = current_card.parent().parent().parent();
//
//         if($(".show_card").length){
//             $(".show_card").removeClass("show_card");
//         }
//
//         card.addClass("show_card");
//     });
//
//     $cc_num.on("blur", function(){
//         var _ = $(this);
//         if(_.val() !== ""){
//             if($.payment.validateCardNumber(_.val())){
//                 _.parent().removeClass("error").addClass("valid");
//             } else {
//                 _.parent().removeClass("valid").addClass("error");
//             }
//         }
//     });
//
//
//     // $cc_exp.on("blur", function(){
//     //     var _ = $(this);
//     //     if(_.val() !== ""){
//     //         var exp = _.val().split(" / ");
//     //         if($.payment.validateCardExpiry(exp[0], exp[1])){
//     //             _.parent().removeClass("error").addClass("valid");
//     //         } else {
//     //             _.parent().removeClass("valid").addClass("error");
//     //         }
//     //     }
//     // });
//
//     $cc_exp.on("blur", function(){
//         var _ = $(this);
//         if(_.val() !== ""){
//             var exp = _.val().split(" / ");
//             if($.payment.validateCardExpiry(exp[0], exp[1])){
//                 _.parent().removeClass("error").addClass("valid");
//             } else {
//                 _.parent().removeClass("valid").addClass("error");
//             }
//         }
//     });
//
//     $cc_cvv.on("blur", function(){
//         var _ = $(this);
//         if(card_type && _.val() !== ""){
//             if($.payment.validateCardCVC(_.val(), card_type)){
//                 _.parent().removeClass("error").addClass("valid");
//             } else {
//                 _.parent().removeClass("valid").addClass("error");
//             }
//         }
//     });
//
//     $cc_cvv.on("focus", function(){
//         var current_card = $(".show_card");
//         if(current_card.length){
//             current_card.addClass("show_cvc");
//         }
//         $(this).one("blur", function(){
//             if($(".show_cvc").length){
//                 $(".show_cvc").removeClass("show_cvc");
//             }
//         });
//     });
//
// })(jQuery);

//
function showCCImg(){
    var current_num = $('#cc_number').val();
    card_type = $.payment.cardType(current_num);
    $('#cc_type').val(card_type);
    var current_card = $("img[src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/"+ card_type +".png']");
    var card = current_card.parent().parent().parent();

    if($(".show_card").length){
        $(".show_card").removeClass("show_card");
    }

    card.addClass("show_card");
}


function checkValid(){
    var num_pattern = /^\d{16}$/;
    var month_pattern = /^\d{2}$/;
    var year_pattern1 = /^\d{2}$/;
    var year_pattern2 = /^\d{4}$/;


    console.log('check');
    var cvc_pattern = /^\d{3,4}$/;

    var cc = $('#cc_number').val().replace(/ /g, "");
    // var cc_expiration = $('#cc_expiration').val().split("/");

    if(!cc.match(num_pattern)){
        console.log('false');
        $('#cc_number').notify("Please input valid number", {position: "right middle"});
        return false;
    }
    // if(cc_expiration.length!=2){
    //     $('#cc_expiration').notify("Please input valid epiration data", {position: "right middle"});
    //     return false;
    // }
    // var month = cc_expiration[0].trim();
    // var year = cc_expiration[1].trim();
    var month = $('#cc_exp_month').val().trim();
    var year = parseInt($('#cc_exp_year').val().trim());
    if(year<100) year += 2000;


    if((parseInt(month)<1|| parseInt(month)>12) ||!month.toString().match(month_pattern)){
        $('#cc_exp_month').notify("Please input valid month", {position: "right middle"});
        return false;
    }

    console.log('year: '+year);
    if( parseInt(year)<2017||(!year.toString().match(year_pattern1)&&!year.toString().match(year_pattern2))){
        $('#cc_exp_year').notify("Please input valid year", {position: "right middle"});
        return false;
    }


    console.log('cvc: '+$('#cc_cvc').val());
    if($('#cc_cvc').val().length<=0 || !$('#cc_cvc').val().match(cvc_pattern)){
        $('#cc_cvc').notify("Please input valid cvc", {position: "right middle"});
        console.log('cvc false');
        return false;
    }

    $('#ppBtn').attr("disabled", true);
    $('body').css('cursor', 'wait');
    var form = document.getElementById('payment-form');

    form.submit();


    return true;
}

