<?php

namespace Armincms\Tab;

use Illuminate\Http\Resources\MergeValue;
use Laravel\Nova\Metable;
use JsonSerializable;
use Closure; 

class Group  
{    
    /**
     * The tab content name.
     *
     * @var string
     */
    protected $name;

    /**
     * The tab content label.
     *
     * @var string
     */
    protected $label = null;

    /**
     * The tab name.
     *
     * @var string
     */
    protected $tab;

    /**
     * The tab content activation.
     *
     * @var bool
     */
    protected $active = false;

    /**
     * The tab field's.
     *
     * @var array
     */
    protected $fields = [];  

    /**
     * Create a new tab group instance.
     *
     * @param  string    $name
     * @param  string    $tab
     * @param  array|\Closure  $fields
     * @return void
     */
    public function __construct($name, $tab, $fields)
    { 
        $this->name = $name; 
        $this->tab = $tab; 
        $this->fields = $this->prepareFields($fields); 

        $this->markFields();
    }    

    /**
     * Preaparing array of fields.
     * 
     * @param  array|\Closure $fields
     * @return array         
     */
    public function prepareFields($fields)
    {
        $fields = is_callable($fields) ? $fields() : $fields;

        return $fields instanceof MergeValue ? $fields->data : $fields;
    }

    /**
     * Set the label for tab.
     * 
     * @param  string $label 
     * @return $this        
     */
    public function label(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the tab content field's.
     * 
     * @return array
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Mark every fields by the tab nad the group name.
     * 
     * @return $this
     */
    public function markFields()
    {
        return $this->wlakThroughFields([$this, 'markField']); 
    }

    /**
     * Wlakin through field's.
     * 
     * @param  callable $callback 
     * @return $this             
     */
    public function wlakThroughFields(callable $callback)
    {
        array_walk($this->fields, function($field) use ($callback) {
            $fields = $field instanceof MergeValue ? array_merge($field->data, [$field]) : [$field];

            array_walk($fields, $callback);
        });

        return $this;  
    } 

    /**
     * Add the tab name and the tab group to field.
     * 
     * @param  string $name    
     * @param  array  $fields 
     * @return $this         
     */
    public function markField($field)
    { 
        if($this->isMetable($field)) {
            $field->withMeta([
                'tabName' => $this->tab, 
                'groupName' => $this->name
            ]);
        } else { 
            $field->tabName = $this->tab;
            $field->groupName = $this->name;
        }  

        return $this;  
    }

    /**
     * Detect if the field using `Metabale`.
     * 
     * @param  object  $item 
     * @return boolean       
     */
    public function isMetable($item)
    {   
        return in_array(Metable::class, class_uses_recursive($item));
    }   

    /**
     * Set tab activation.
     * 
     * @return $this
     */
    public function active()
    {
        $this->active = true;

        return $this;
    }  

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'name'  => $this->name,
            'label' => $this->label ?: $this->name,
            'tab'   => $this->tab,
            'active'=> $this->active, 
        ];
    }
}
