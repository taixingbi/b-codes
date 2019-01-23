<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/',[
    'uses' => 'BigBike\Agent\RentController@getRent',
    'as' => 'agent.rentOrder',
    'middleware' => 'auth'
]);

Route::group(['prefix' => 'invoice'],function() {

    Route::get('/conf', [
        'uses' => 'BigBike\Agent\AgentController@put_conf',
        'as' => 'invoice.conf'
    ]);
    Route::get('/cart={num_get}', [
        'uses' => 'BigBike\Agent\AgentController@put_conf',
        'as' => 'invoice.cart'
    ]);
    Route::post('/checkout', [
        'uses' => 'BigBike\Agent\AgentController@checkoutInvoice',
        'as' => 'invoice.checkout'
    ]);
//    Route::get('/email', function() {
//        $data = array('name' => 'Jordan');
//
//        Mail::send('emails.dailymail', $data, function($message)
//        {
//            $message->to('s.tcukanov@gmail.com')
//                ->subject('Email by Alex');
//});

//        });
    Route::get('/email', [
        'uses' => 'BigBike\Agent\AgentController@sendDailyEmail',
        'as' => 'invoice.email'
    ]);



});

Route::get('/404',[
    'uses' => 'BigBike\Agent\AgentController@go404',
    'as' => 'agent.404'
//    'middleware' => 'auth'
]);

//Route::get('/ekiosk-index',[
//    'uses' => 'APITEST\EkioskController@getRentIndex',
//    'as' => 'agent.404'
////    'middleware' => 'auth'
//]);

//Route::get('/ekiosk-pricelist',[
//    'uses' => 'APITEST\EkioskController@getRentIndex',
//    'as' => 'agent.404'
////    'middleware' => 'auth'
//]);



Route::group(['prefix' => 'user'], function(){
    Route::group(['middleware' => 'guest'], function(){
        Route::get('/signup',[
            'uses' => 'UserController@getSignup',
            'as' => 'user.signup'
        ]);

        Route::post('/signup',[
            'uses' => 'UserController@postSignup',
            'as' => 'user.signup'
        ]);

        Route::get('/signin',[
            'uses' => 'UserController@getSignin',
            'as' => 'user.signin'
        ]);

        Route::post('/signin',[
            'uses' => 'UserController@postSignin',
            'as' => 'user.signin'
        ]);
    });


    Route::group(['middleware' => 'auth'], function(){
        Route::get('/profile',[
            'uses' => 'UserController@getProfile',
            'as' => 'user.profile'
        ]);

        Route::get('/logout',[
            'uses' => 'UserController@getLogout',
            'as' => 'user.logout'
        ]);
    });

});


Route::group(['prefix' => 'bigbike/admin'],function(){

    Route::group(['middleware' => 'auth'], function(){


        Route::get('countSightseeing',[
            'uses' => 'BigBike\Agent\AgentController@countSightseeing',
            'as' => 'admin.countSightseeing'
        ]);

        Route::get('main',[
            'uses' => 'BigBike\Admin\AdminController@getMainPage',
            'as' => 'admin.main'
        ]);

        Route::get('report',[
            'uses' => 'BigBike\Admin\AdminController@getReport',
            'as' => 'admin.report'
        ]);

        Route::post('agent',[
            'uses' => 'BigBike\Admin\AdminController@getAgentReport',
            'as' => 'admin.agent'
        ]);

        Route::post('agent/detail',[
            'uses' => 'BigBike\Admin\AdminController@getAgentMonthlyDetail',
            'as' => 'admin.agentDetail'
        ]);

        Route::get('agent/monthly',[
            'uses' => 'BigBike\Admin\AdminController@getMonthForm',
            'as' => 'admin.monthly'
        ]);

        Route::post('agent/month',[
            'uses' => 'BigBike\Admin\AdminController@getMonthReport',
            'as' => 'admin.monthlyReport'
        ]);

    });
});


Route::group(['prefix' => 'bigbike/agent'],function(){


    Route::get('/showBTPage',[
        'uses' => 'BigBike\Agent\BrainTreeController@showBTPage',
        'as' => 'agent.showBTPage'
    ]);


    Route::get('/removeOld',[
        'uses' => 'BigBike\Agent\AgentController@removeOld',
        'as' => 'agent.removeOld'
    ]);

    Route::get('/get-reset-page',[
        'uses' => 'BigBike\Agent\AgentController@getResetPage',
        'as' => 'agent.getResetPage'
    ]);

    Route::post('/get-email',[
        'uses' => 'BigBike\Agent\AgentController@getEmail',
        'as' => 'agent.getEmail'
    ]);

    Route::get('/show-reset-password-page',[
        'uses' => 'BigBike\Agent\AgentController@getResetPage',
        'as' => 'agent.getGetResetPage'
    ]);

    Route::post('/show-reset-password-page',[
        'uses' => 'BigBike\Agent\AgentController@showResetPasswordPage',
        'as' => 'agent.showResetPasswordPage'
    ]);

//    Route::get('/send-reset-email',[
//        'uses' => 'BigBike\Agent\AgentController@sendResetEmail',
//        'as' => 'agent.sendResetEmail'
//    ]);

    Route::post('/reset-password',[
        'uses' => 'BigBike\Agent\AgentController@resetPassword',
        'as' => 'agent.resetPassword'
    ]);

    Route::get('/contact',[
        'uses' => 'BigBike\Agent\AgentController@contact',
        'as' => 'agent.contact'
    ]);

    Route::group(['middleware' => 'auth'], function(){

        Route::get('/update',[
            'uses' => 'BigBike\Agent\AgentController@posAgentUpdate',
            'as' => 'agent.posAgentUpdate'
        ]);

        Route::post('/update-com',[
            'uses' => 'BigBike\Agent\AgentController@posAgentUpdateCom',
            'as' => 'agent.posAgentUpdateCom'
        ]);


        Route::get('/add',[
            'uses' => 'BigBike\Agent\AgentController@posAgentAdd',
            'as' => 'agent.posAgentAdd'
        ]);

        Route::post('/add',[
            'uses' => 'BigBike\Agent\AgentController@posAgentAddPost',
            'as' => 'agent.posAgentAddPost'
        ]);

        Route::get('/show-agents',[
            'uses' => 'BigBike\Agent\AgentController@posAgentComPage',
            'as' => 'agent.posAgentComPage'
        ]);

        Route::post('/delete-agents',[
            'uses' => 'BigBike\Agent\AgentController@posAgentDelete',
            'as' => 'agent.posAgentDelete'
        ]);

        Route::get('/agent-report',[
            'uses' => 'BigBike\Agent\AgentController@showAgentSearchPage',
            'as' => 'agent.posAgentReport'
        ]);

        Route::get('/agent-showAgentComDetail/{id}',[
            'uses' => 'BigBike\Agent\AgentController@showAgentComDetail',
            'as' => 'agent.showAgentComDetail'
        ]);

//        Route::get('pos/month',[
//            'uses' => 'BigBike\Agent\AgentController@getPosMonthReport',
//            'as' => 'agent.posMonthReport'
//        ]);

        Route::get('pos/cashier',[
            'uses' => 'BigBike\Agent\AgentController@getPosCashierReport',
            'as' => 'agent.getPosCashierReport'
        ]);

        Route::get('pos/daily',[
            'uses' => 'BigBike\Agent\AgentController@getPosDailyReport',
            'as' => 'agent.getPosDailyReport'
        ]);

        Route::post('pos/daily/detail',[
            'uses' => 'BigBike\Agent\AgentController@getPosDailyReportDetail',
            'as' => 'agent.getPosDailyReportDetail'
        ]);

        Route::get('pos/daily/rent/{id}',[
            'uses' => 'BigBike\Agent\AgentController@posDailyRent',
            'as' => 'agent.posDailyRent'
        ]);

        Route::get('pos/daily/tour/{id}',[
            'uses' => 'BigBike\Agent\AgentController@posDailyTour',
            'as' => 'agent.posDailyTour'
        ]);

        Route::get('pos/cashier-detail/{type}/{data}',[
            'uses' => 'BigBike\Agent\AgentController@getPosCashierDetail2',
            'as' => 'agent.getPosCashierDetail'
        ]);

        Route::get('pos/agent-detail',[
            'uses' => 'BigBike\Agent\AgentController@getPosAgentDetail',
            'as' => 'agent.getPosAgentDetail'
        ]);

        Route::post('pos/agent/paid',[
            'uses' => 'BigBike\Agent\AgentController@setAgentPaid',
            'as' => 'agent.setAgentPaid'
        ]);

        Route::get('pos/cashier-detail-detail/{email}/{type}/{data}',[
            'uses' => 'BigBike\Agent\AgentController@showPosCashierMoreDetail',
            'as' => 'agent.showPosCashierMoreDetail'
        ]);

        Route::group(['middleware' => ['role:admin']], function() {

            Route::get('pos/month', [
                'uses' => 'BigBike\Agent\AgentController@getPosMonthReport',
                'as' => 'agent.posMonthReport'
            ]);

            Route::post('pos/month-detail', [
                'uses' => 'BigBike\Agent\AgentController@getPosMonthDetail',
                'as' => 'agent.posMonthDetail'
            ]);

            Route::get('pos/month-detail-breakdown/{location}/{year}/{month}/{type?}', [
                'uses' => 'BigBike\Agent\AgentController@getMonthDetails',
                'as' => 'agent.getMonthDetails'
            ]);

        });

        Route::get('/main',[
            'uses' => 'BigBike\Agent\AgentController@loginAgent',
            'as' => 'agent.main'
        ]);

        //membership
        Route::get('/show-membership-register',[
            'uses' => 'BigBike\Agent\MemberController@showMemberPage',
            'as' => 'agent.showMemberPage'
        ]);

        Route::post('/membership-register-cash',[
            'uses' => 'BigBike\Agent\MemberController@registerMember',
            'as' => 'agent.registerMember'
        ]);

        Route::get('/membership-receipt',[
            'uses' => 'BigBike\Agent\MemberController@memberReceipt',
            'as' => 'agent.memberReceipt'
        ]);

        Route::post('/membership-register',[
            'uses' => 'BigBike\Agent\MemberController@ppMemberCheckout',
            'as' => 'agent.ppMemberCheckout'
        ]);

        Route::get('/return',[
            'uses' => 'BigBike\Agent\AgentController@showReturnPage',
            'as' => 'agent.showReturnPage'
        ]);

        Route::get('/reservation',[
            'uses' => 'BigBike\Agent\AgentController@showReservationPage',
            'as' => 'agent.showReservationPage'
        ]);

        Route::post('/barcode-scan',[
            'uses' => 'BigBike\Agent\AgentController@barcodeScan',
            'as' => 'agent.barcodeScan'
        ]);

        Route::get('/card/test',[
            'uses' => 'BigBike\Agent\AgentController@cardTest',
            'as' => 'admin.cardTest'
        ]);

        Route::get('/esignature/',[
            'uses' => 'BigBike\Agent\RentController@getEsignature',
            'as' => 'agent.esignature'
        ]);

        Route::post('/esignature/store',[
            'uses' => 'BigBike\Agent\RentController@storeEsignature',
            'as' => 'agent.storeEsignature'
        ]);

        Route::get('/clocksystem',[
            'uses' => 'BigBike\Agent\ClockController@clocksystemMain',
            'as' => 'agent.clockMain'
        ]);

        Route::get('/clocksystem/summary',[
            'uses' => 'BigBike\Agent\ClockController@getClockSummary',
            'as' => 'agent.getClockSummary'
        ]);

        Route::post('/clocksystem/add',[
            'uses' => 'BigBike\Agent\ClockController@add',
            'as' => 'agent.clockAdd'
        ]);

        Route::get('/searchCustomer/',[
            'uses' => 'BigBike\Agent\AgentController@searchCustomer',
            'as' => 'agent.searchCustomer'
        ]);

        Route::post('/getCustomerInfo/',[
            'uses' => 'BigBike\Agent\AgentController@getCustomerInfo',
            'as' => 'agent.getCustomerInfo'
        ]);



        Route::group(['prefix' => 'rent'],function(){

            Route::get('ticket',[
                'uses' => 'BigBike\Agent\RentController@printTicket',
                'as' => 'agent.rentTicket'
            ]);

            Route::get('test',[
                'uses' => 'BigBike\Agent\RentController@test',
                'as' => 'agent.test'
            ]);

            Route::get('order',[
                'uses' => 'BigBike\Agent\RentController@getRent',
                'as' => 'agent.rentOrder'
            ]);

            Route::get('order-cal',[
                'uses' => 'BigBike\Agent\RentController@calculate',
                'as' => 'agent.rentOrderCal'
            ]);

            Route::get('order-submit',[
                'uses' => 'BigBike\Agent\RentController@getCheckout',
                'as' => 'agent.rentOrderSubmitGet'
            ]);

            Route::post('order-submit',[
                'uses' => 'BigBike\Agent\RentController@submitForm',
                'as' => 'agent.rentOrderSubmit'
            ]);

            Route::get('order-checkout',[
                'uses' => 'BigBike\Agent\RentController@checkout',
                'as' => 'agent.rentOrderCheckout'
            ]);

            Route::post('cc-checkout',[
                'uses' => 'BigBike\Agent\RentController@postCCCheckout',
                'as' => 'agent.ccCheckout'
            ]);

            Route::post('pp-checkout',[
                'uses' => 'BigBike\Agent\RentController@postppCheckout',
                'as' => 'agent.ppCheckout'
            ]);

            Route::get('receipt',[
                'uses' => 'BigBike\Agent\RentController@printReceipt',
                'as' => 'agent.rentReceipt'
            ]);

            Route::get('receipt-return/{id}',[
                'uses' => 'BigBike\Agent\RentController@printReceiptFromReturn',
                'as' => 'agent.printReceiptFromReturn'
            ]);

            Route::post('addAgent',[
                'uses' => 'BigBike\Agent\RentController@addAgent',
                'as' => 'agent.addAgent'
            ]);

            //membership check
            Route::post('membership',[
                'uses' => 'BigBike\Agent\RentController@getMembership',
                'as' => 'agent.getMembership'
            ]);

            //reservation-serve
            Route::get('reservation-detail/{id}',[
                'uses' => 'BigBike\Agent\RentController@showReservationDetail',
                'as' => 'agent.showReservationDetail'
            ]);

            Route::post('reservation-update',[
                'uses' => 'BigBike\Agent\RentController@updateReservation',
                'as' => 'agent.updateReservation'
            ]);

            Route::get('return-detail/{id}',[
                'uses' => 'BigBike\Agent\RentController@showReturnDetail',
                'as' => 'agent.showReturnDetail'
            ]);

            Route::get('edit/{id}',[
                'uses' => 'BigBike\Agent\RentController@reserveShowEditPage',
                'as' => 'agent.reserveShowEditPage'
            ]);

            Route::post('edit',[
                'uses' => 'BigBike\Agent\RentController@showEditPage',
                'as' => 'agent.showEditPage'
            ]);

            Route::post('edit-submitForm',[
                'uses' => 'BigBike\Agent\RentController@editSubmitForm',
                'as' => 'agent.editSubmitForm'
            ]);

            Route::post('extra-service',[
                'uses' => 'BigBike\Agent\RentController@postReserveCheckout',
                'as' => 'agent.rentReserveCheckout'
            ]);

            Route::post('finish-return',[
                'uses' => 'BigBike\Agent\RentController@finishReturn',
                'as' => 'agent.finishReturn'
            ]);

            Route::post('return-checkout',[
                'uses' => 'BigBike\Agent\RentController@rentReturnCheckout',
                'as' => 'agent.rentReturnCheckout'
            ]);

            Route::post('edit-checkout',[
                'uses' => 'BigBike\Agent\RentController@rentEditCheckout',
                'as' => 'agent.rentEditCheckout'
            ]);

            Route::get('deposit-refund-receipt',[
                'uses' => 'BigBike\Agent\RentController@depoistRefundReceipt',
                'as' => 'agent.depoistRefundReceipt'
            ]);

            Route::get('release-deposit-receipt',[
                'uses' => 'BigBike\Agent\RentController@depoistReleaseReceipt',
                'as' => 'agent.depoistReleaseReceipt'
            ]);

            Route::get('return-receipt',[
                'uses' => 'BigBike\Agent\RentController@returnReceipt',
                'as' => 'agent.returnReceipt'
            ]);

            Route::post('deposit-checkout',[
                'uses' => 'BigBike\Agent\RentController@rentDepositCheckout',
                'as' => 'agent.rentDepositCheckout'
            ]);

            Route::post('delete/',[
                'uses' => 'BigBike\Agent\RentController@deleteRent',
                'as' => 'agent.deleteRent'
            ]);

            Route::post('editCheck/',[
                'uses' => 'BigBike\Agent\RentController@editCheck',
                'as' => 'agent.editCheck'
            ]);
            

        });


        Route::group(['prefix' => 'tour'],function(){

            Route::get('order',[
                'uses' => 'BigBike\Agent\TourController@getOrder',
                'as' => 'agent.tourOrder'
            ]);

            Route::get('order-cal',[
                'uses' => 'BigBike\Agent\TourController@calculate',
                'as' => 'agent.tourOrderCal'
            ]);


            Route::post('order-submit',[
                'uses' => 'BigBike\Agent\TourController@submitForm',
                'as' => 'agent.tourOrderSubmit'
            ]);

            Route::post('cc-checkout',[
                'uses' => 'BigBike\Agent\TourController@postCCCheckout',
                'as' => 'agent.tourccCheckout'
            ]);

            Route::get('receipt',[
                'uses' => 'BigBike\Agent\TourController@printReceipt',
                'as' => 'agent.tourReceipt'
            ]);

            Route::get('ticket',[
                'uses' => 'BigBike\Agent\TourController@printTicket',
                'as' => 'agent.tourTicket'
            ]);

            Route::post('pp-tour-checkout',[
                'uses' => 'BigBike\Agent\TourController@postppCheckout',
                'as' => 'agent.ppTourCheckout'
            ]);

            Route::get('edit/{id}',[
                'uses' => 'BigBike\Agent\TourController@reserveShowEditPage',
                'as' => 'agent.reserveTourShowEditPage'
            ]);

            Route::post('edit-submitForm',[
                'uses' => 'BigBike\Agent\TourController@editSubmitForm',
                'as' => 'agent.tourEditSubmitForm'
            ]);

            Route::post('edit-checkout',[
                'uses' => 'BigBike\Agent\TourController@postReserveCheckout',
                'as' => 'agent.tourReserveCheckout'
            ]);

            Route::get('return-detail/{id}',[
                'uses' => 'BigBike\Agent\TourController@showReturnDetail',
                'as' => 'agent.showTourReturnDetail'
            ]);

            Route::post('edit',[
                'uses' => 'BigBike\Agent\TourController@showEditPage',
                'as' => 'agent.showTourEditPage'
            ]);

            Route::post('delete/',[
                'uses' => 'BigBike\Agent\TourController@deleteTour',
                'as' => 'agent.deleteTour'
            ]);

            Route::get('receipt-tour-return/{id}',[
                'uses' => 'BigBike\Agent\TourController@printReceiptFromReturn',
                'as' => 'agent.printReceiptFromTourReturn'
            ]);

            Route::post('deposit-submit',[
                'uses' => 'BigBike\Agent\TourController@tourDepositCheckout',
                'as' => 'agent.tourDepositCheckout'
            ]);

            Route::get('tour-deposit-refund-receipt',[
                'uses' => 'BigBike\Agent\TourController@depoistRefundReceipt',
                'as' => 'agent.tourDepoistRefundReceipt'
            ]);

        });


        Route::get('/report',[
            'uses' => 'BigBike\Agent\AgentController@getReportForm',
            'as' => 'agent.report'
        ]);

        Route::post('/show-report',[
            'uses' => 'BigBike\Agent\AgentController@showReport',
            'as' => 'agent.showReport'
        ]);

        Route::post('/show-agent-report',[
            'uses' => 'BigBike\Agent\AgentController@showAgentReport',
            'as' => 'agent.showAgentReport'
        ]);

        Route::group(['prefix' => 'sports'],function(){


            Route::get('/main',[
                'uses' => 'BigBike\Agent\SportsSaleController@sportsSale',
                'as' => 'agent.sportsSale'
            ]);

            Route::post('/barcode',[
                'uses' => 'BigBike\Agent\SportsSaleController@barcodeSearch',
                'as' => 'agent.barcodeSearch'
            ]);

            Route::post('/form-sub',[
                'uses' => 'BigBike\Agent\SportsSaleController@sportsForm',
                'as' => 'agent.sportsForm'
            ]);

            Route::get('/updateCart/{id}/{num}',[
                'uses' => 'BigBike\Agent\SportsSaleController@updateCart',
                'as' => 'agent.updateCart'
            ]);

            Route::post('/pmt-form-sub',[
                'uses' => 'BigBike\Agent\SportsSaleController@pmtForm',
                'as' => 'agent.pmtForm'
            ]);

            Route::post('/inventoryCheckout',[
                'uses' => 'BigBike\Agent\SportsSaleController@inventoryCheckout',
                'as' => 'agent.inventoryCheckout'
            ]);

            Route::get('/intReceipt',[
                'uses' => 'BigBike\Agent\SportsSaleController@intReceipt',
                'as' => 'agent.intReceipt'
            ]);

            Route::get('/add',[
                'uses' => 'BigBike\Agent\SportsSaleController@addPage',
                'as' => 'agent.add'
            ]);

            Route::post('/addToInt',[
                'uses' => 'BigBike\Agent\SportsSaleController@addToInt',
                'as' => 'agent.addToInt'
            ]);

        });

        Route::group(['prefix' => 'phoneReservation'],function(){


            Route::get('/main',[
                'uses' => 'BigBike\Agent\AgentController@phoneReservation',
                'as' => 'agent.phoneReservation'
            ]);


            Route::post('/form-sub',[
                'uses' => 'BigBike\Agent\AgentController@phoneReservationCheckout',
                'as' => 'agent.phoneReservationCheckout'
            ]);


            Route::get('/phonerentReceipt',[
                'uses' => 'BigBike\Agent\AgentController@phonerentReceipt',
                'as' => 'agent.phonerentReceipt'
            ]);
            Route::get('/createInvoice',[
                'uses' => 'BigBike\Agent\AgentController@createInvoice',
                'as' => 'agent.createInvoice'
            ]);

            Route::post('/form-inv',[
                'uses' => 'BigBike\Agent\AgentController@invoiceForm',
                'as' => 'agent.invoiceForm'
            ]);


        });


        Route::group(['prefix' => 'inventory'],function(){


            Route::get('/main',[
                'uses' => 'BigBike\Agent\InventoryController@main',
                'as' => 'agent.inventory.main'
            ]);

            Route::post('/updateCart',[
                'uses' => 'BigBike\Agent\InventoryController@updateCart',
                'as' => 'agent.inventory.updateCart'
            ]);

            Route::get('/checkout',[
                'uses' => 'BigBike\Agent\InventoryController@checkout',
                'as' => 'agent.inventory.checkout'
            ]);

            Route::post('/checkout',[
                'uses' => 'BigBike\Agent\InventoryController@postppCheckout',
                'as' => 'agent.inventory.inv_cartCheckout'
            ]);

            Route::post('/checkout-cash',[
                'uses' => 'BigBike\Agent\InventoryController@updateDBCash',
                'as' => 'agent.inventory.inv_cartCheckout_cash'
            ]);

            Route::get('/receipt',[
                'uses' => 'BigBike\Agent\InventoryController@receipt',
                'as' => 'agent.inventory.receipt'
            ]);

            Route::post('/update-qty',[
                'uses' => 'BigBike\Agent\InventoryController@updateQTY',
                'as' => 'agent.inventory.update_qty'
            ]);

            Route::get('/purchase',[
                'uses' => 'BigBike\Agent\InventoryController@purchase',
                'as' => 'agent.inventory.purchase'
            ]);

            Route::get('/clear-cart',[
                'uses' => 'BigBike\Agent\InventoryController@clearCart',
                'as' => 'agent.inventory.clear_cart'
            ]);


//            update_qty

//            Route::post('/form-sub',[
//                'uses' => 'BigBike\Agent\AgentController@phoneReservationCheckout',
//                'as' => 'agent.phoneReservationCheckout'
//            ]);
//
//
//            Route::get('/phonerentReceipt',[
//                'uses' => 'BigBike\Agent\AgentController@phonerentReceipt',
//                'as' => 'agent.phonerentReceipt'
//            ]);

        });



    });

    Route::group(['prefix' => 'bikeInventory'],function(){


        Route::get('/main',[
            'uses' => 'BigBike\Agent\BikeController@main',
            'as' => 'agent.bikeInventory'
        ]);

        Route::get('/update/{qrcode}',[
            'uses' => 'BigBike\Agent\BikeController@update',
            'as' => 'agent.bikeUpdate'
        ]);

        Route::post('/form-sub',[
            'uses' => 'BigBike\Agent\SportsSaleController@sportsForm',
            'as' => 'agent.sportsForm'
        ]);

        Route::get('/updateCart/{id}/{num}',[
            'uses' => 'BigBike\Agent\SportsSaleController@updateCart',
            'as' => 'agent.updateCart'
        ]);

        Route::post('/pmt-form-sub',[
            'uses' => 'BigBike\Agent\SportsSaleController@pmtForm',
            'as' => 'agent.pmtForm'
        ]);

        Route::post('/inventoryCheckout',[
            'uses' => 'BigBike\Agent\SportsSaleController@inventoryCheckout',
            'as' => 'agent.inventoryCheckout'
        ]);

        Route::get('/intReceipt',[
            'uses' => 'BigBike\Agent\SportsSaleController@intReceipt',
            'as' => 'agent.intReceipt'
        ]);

        Route::get('/add',[
            'uses' => 'BigBike\Agent\SportsSaleController@addPage',
            'as' => 'agent.add'
        ]);

        Route::post('/addToInt',[
            'uses' => 'BigBike\Agent\SportsSaleController@addToInt',
            'as' => 'agent.addToInt'
        ]);

    });


    Route::group(['prefix' => 'clock'],function(){


        Route::get('/main',[
            'uses' => 'BigBike\Agent\ClockController@main',
            'as' => 'agent.clockInventory'
        ]);

        Route::get('/update/in/{name}',[
            'uses' => 'BigBike\Agent\ClockController@clockin',
            'as' => 'agent.clockIn'
        ]);

        Route::get('/update/out/{name}',[
            'uses' => 'BigBike\Agent\ClockController@clockout',
            'as' => 'agent.clockOut'
        ]);

    });

    Route::get('/tripAdvisorReport',[
        'uses' => 'BigBike\Agent\AgentController@tripAdvisorReport',
        'as' => 'agent.tripAdvisorReport'
    ]);


});

//Route::get('/bigbike/agent/tour-order-cal',[
//    'uses' => 'ProductController@calculateTour',
//    'as' => 'agent.tourOrderCal'
//]);
