$(document).ready(function(){$(".datepicker").datepicker({minDate:new Date}),$(".datepicker").datepicker().datepicker("setDate",new Date),$(".spinner").spinner({min:0,max:20,step:1}),$(".tour-spinner").spinner({min:0,max:15,step:1}),$(".timepicker").timepicker({timeFormat:"H:mm",interval:60,minTime:"8",maxTime:"19:00",defaultTime:"11",startTime:"10:00",dynamic:!1,dropdown:!0,scrollbar:!0}),$("#datepicker").value=new Date,$(".tour-spinner").change(function(){console.log("change")}),$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),$("#rent_barcode").change(function(){var e=$("#rent_barcode").val();console.log("scan: "+e),$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),$.ajax({type:"POST",cache:!1,url:"/bigbike/agent/barcode-scan",data:"barcode="+e,success:function(e){console.log(e.url),"error"==e.type?swal(e.response):"reservation"!=e.type&&"return"!=e.type||window.location.replace(e.url)}})})});