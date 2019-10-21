# Nova Tab

Field's Grouping by the tab.

##### Table of Contents   
* [Install](#install)  
* [Usage](#usage)    
* [Using With Panel](#using-with-panel)    
* [Relations](#relations)    

## Install
```bash
composer require armincms/nova-tab
``` 
 
## Usage
First create your tab like follow. Then; for define tab `field's`, you can use the `group` method.

```
Tab::make('tab-name', function($tab) {

    // pass fields by callback
    $tab->group('first-tab-name', function($group) {  
      return [

        // define your tab fields

      ];
    });
    
    // pass fields by array
    // active your tab by `active` method
    $tab->group('active-tab', [ 

        // define your tab fields

    ])->active();

    // custom label tab
    $tab->group('custom-label-tab', function($group) {  
      // or inside the group
      $group->label('My Custom Label');
      
      return [

        // define your tab fields

      ];
    })->label('My Custom Label');
  
  // full width tab by call `fullwidth` method
})->fullwidth();
```


### Ative Tab:
By default we'll active the first tab. if you want customize the `active tab`, 
  it's availabe by call the `active` method on the tab `group`.

### Tab Label 
It's possible to customize the `tab label's` by passing the label string through the 
`label` method on the `group`. 

### Full Width Tab  
If you want jsutify the tab for fill screen; you can call `fullWidth` method on the `Tab::class` 

#### Attention 1: you can add any `field` and `relation-field` into the tab. 
#### Attention 2: it's impossible to add `Panel` into the tab.
#### Attention 3: It's possible to add the `tab` into the `Panel`.
 
* base example:

``` 
    use Armincms\Tab\Tab;    



    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [          
            Tab::make('general', function($tab) {
                $tab->group('general', [
                    ID::make()->sortable(),  

                    Select::make('Tag')->options(function() {
                        return ['*' => 'all'];
                    })->default('*'),     
                ])->label(__('General')); 

                $tab->group('SEO', [ 
                    Text::make('Title'), 
                ])->active();  

                $tab->group('Relations', [
                    MorphToMany::make('Tag'),  
                ])->label('Relations');  
            }),  
        ]; 
    }
```

* Fullwidth tab example:

``` 
    use Armincms\Tab\Tab;    



    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [          
            Tab::make('general', function($tab) {
                $tab->group('general', [
                    ID::make()->sortable(),  

                    Select::make('Tag')->options(function() {
                        return ['*' => 'all'];
                    })->default('*'),     
                ])->label(__('General')); 

                $tab->group('SEO', [ 
                    Text::make('Title'), 
                ])->active();  

                $tab->group('Relations', [
                    MorphToMany::make('Tag'),  
                ])->label('Relations');  
            }),  
        ]; 
    }
```

## Using With Panel
You can add tab into `Panel` but you never can add `Panel` into tab.

```

    use Armincms\Tab\Tab;    



    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [     
            new Panel('My Panel', [  
                Tab::make('general', function($tab) {
                    $tab->group('general', [
                        ID::make()->sortable(),  

                        Select::make('Tag')->options(function() {
                            return ['*' => 'all'];
                        })->default('*'),     
                    ])->label(__('General')); 

                    $tab->group('SEO', [ 
                        Text::make('Title'), 
                    ])->active();  

                    $tab->group('Relations', [
                        MorphToMany::make('Tag'),  
                    ])->label('Relations');  
                }), 
            ])   
        ]; 
    }
```


## Relations

```

    use Armincms\Tab\Tab;    



    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [     
            new Panel('My Panel', [  
                Tab::make('general', function($tab) {
                    $tab->group('Author', [
                        ID::make()->sortable(),  

                        BelogsTo::make('User'),   
                    ])->label(__('General'));  

                    $tab->group('Relations', [
                        MorphToMany::make('Tag'),  
                    ])->label('Relations');  
                }), 
            ])   
        ]; 
    }
```