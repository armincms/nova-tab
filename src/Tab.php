<?php

namespace Armincms\Tab;

use Illuminate\Http\Resources\MergeValue;
use Laravel\Nova\Metable;
use JsonSerializable;
use Closure; 

class Tab extends MergeValue 
{
    use Metable; 

    /**
     * List of availabel tabs.
     * 
     * @var array
     */
    protected $tabs = [];  

    /**
     * The data to be merged.
     *
     * @var array
     */
    public $data = [];  

    /**
     * Create a new panel instance.
     *
     * @param  string    $name
     * @param  \Closure  $builder
     * @return void
     */
    public function __construct($name, Closure $builder)
    { 
        $this->name = $name;  

        $builder($this);    

        array_unshift($this->data, $this->getNavigator());
    }  

    /**
     * Tab's navigator field.
     * 
     * @return \Laravel\Nova\Fields\Field
     */
    public function getNavigator()
    { 
        $callback = function($tab) {
            return $tab->JsonSerialize();
        }; 

        return TabNavigator::make($this->name, array_map($callback, $this->tabs)); 
    } 

    /**
     * Create a new tab.
     *
     * @param  string $name
     * @param  array|\Closure  $fields
     * @return void
     */
    public function group(string $name, $fields)
    {   
        return tap(new Group($name, $this->name, $fields), function($tab) {
            $this->data = array_merge((array) $this->data, $tab->fields());
            $this->tabs[] = $tab; 
        }); 
    }

    /**
     * Set `w-full` class for tab's.
     *  
     * @return [type]             
     */
    public function fullwidth()
    { 
        $this->data[0]->withMeta(['fullwidth' => true]);

        return $this;
    } 

    /**
     * Create a new element.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }    

    /**
     * When the panel attached to the tab, we'll attach it to fields.
     * 
     * @param string $key   
     * @param mixed $value 
     */
    public function __set($key, $value)
    {  
        if($key === 'panel') {
            $this->data = array_map(function($field) use ($value) {
                $field->panel = $value;

                return $field;
            }, $this->data);
        }   
 
        $this->{$key} = $value; 
    }
}
