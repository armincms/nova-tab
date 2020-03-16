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
     * List of availabel groups.
     * 
     * @var array
     */
    protected $groups = [];  

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
     * @param  array|\Closure  $builder
     * @return void
     */
    public function __construct($name, $builder)
    { 
        $this->name = $name;  

        is_array($builder) ? $this->arrayBuilder($builder) : $builder($this);    

        array_unshift($this->data, $this->getNavigator());
    } 

    /**
     * Build tab groups by array.
     * 
     * @param  array  $builder 
     * @return $this
     */
    protected function arrayBuilder(array $builder)
    {
        array_walk($builder, function($label, $name) { 
            if(! $this->isCallableOrArray($label)) { 
                $name = is_numeric($name) ? $label : $name;
                $builder = [];
            } else {
                $builder = $label;
                $label = $name;
            }

            $group = $this->group($name, $builder);

            $name === $label || $group->label($label);
        });

        return $this;
    } 

    /**
     * Detect if builder is array or is callable.
     * 
     * @param  array|callable $builder 
     * @return boolean          
     */
    protected function isCallableOrArray($builder)
    {
        return is_callable($builder) || is_array($builder);
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

        return TabNavigator::make($this->name, array_map($callback, $this->groups)); 
    } 

    /**
     * Create a new tab.
     *
     * @param  string $name
     * @param  array|\Closure  $fields
     * @return void
     */
    public function group(string $name, $fields = [])
    {   
        return tap(new Group($name, $this->name, $fields), function($group) {
            $this->data = array_merge((array) $this->data, $group->fields());
            $this->groups[] = $group; 
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
     * Active tab heading.
     * 
     * @return $this             
     */
    // public function heading()
    // {  
    //     $this->data[0]->withMeta(['heading' => true]);

    //     return $this;
    // } 

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

    /**
     * Get all of the fields in the array.
     * 
     * @return array
     */
    public function toArray() : array
    {
        return $this->data;
    }
}
