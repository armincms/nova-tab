<?php

namespace Armincms\Tab; 
 
use Laravel\Nova\Fields\Field; 

class TabNavigator extends Field
{    
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'tab-navigator';  

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var \Closure|bool
     */
    public $showOnIndex = false;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @param  callable|null  $resolveCallback
     * @return void
     */
    public function __construct($name, array $tabs)
    {
        parent::__construct($name, null, null);

        $this->withMeta(['tabs' => $tabs])->fillUsing(function() {});
    } 
}

