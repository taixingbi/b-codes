<?php
/**
 * Created by PhpStorm.
 * User: xdrm
 * Date: 4/7/17
 * Time: 11:14 AM
 */

class SHAHashServiceProvider extends Illuminate\Support\ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app['hash'] = $this->app->share(function () {
            return new SHAHasher();
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('hash');
    }

}