<template>    
    <tab-navigator
        :tabs="field.tabs" 
        v-on:tab-changed="handleTabChanges"
        :fullwidth="field.fullwidth" 
    /> 
</template>

<script> 
import Tab from './Tab.vue' 

export default {
    mixins: [Tab], 
    data() {
        return { 
            components : {
                type: Array, default: []
            } 
        }
    },
    methods: {   
        walkThroughComponents(callback) { 
            if(! this.components.length) {
                this.components = this.deepSearch(this.$root);
            }  

            return this.components.forEach(callback);
        },
        deepSearch($vue) {
            let  $this = this, $components = [];

            $vue.$children.forEach(function(component) {   
                if(component.field) { 
                    $components.push(component); 
                } else if(component.$children.length) { 
                    $components = $components.concat($this.deepSearch(component))
                }  
            });

            return $components;
        }, 
    }, 
}
</script> 