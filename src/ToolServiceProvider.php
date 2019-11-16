<?php

namespace Armincms\Tab;

use Laravel\Nova\Nova;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Events\ServingNova; 
use Illuminate\Support\ServiceProvider; 

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {  
        Nova::serving(function (ServingNova $event) {
            Nova::script('armincms-nova-tab', __DIR__.'/../dist/js/field.js');

            Field::macro('withTab', function(string $tabName, string $groupName) {
                return $this->withMeta(compact('tabName', 'groupName'));
            });
        });
    } 

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
