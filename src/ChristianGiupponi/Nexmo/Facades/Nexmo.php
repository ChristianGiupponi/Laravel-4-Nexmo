<?php namespace ChristianGiupponi\Nexmo\Facades;

use Illuminate\Support\Facades\Facade;

class Nexmo extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'nexmo'; }

}