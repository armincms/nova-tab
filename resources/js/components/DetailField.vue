<template>    
    <tab-navigator :tabs="field.tabs"  v-on:tab-changed="handleTabChanges" :full-width="field.fullwidth" /> 
</template>

<script>
import { FormField } from 'laravel-nova'

export default {
    mixins: [FormField], 
    components: {
        tabNavigator: require('./Navigator.vue')
    },  
    data() {
        return { 
            components : {
                type: Array, default: []
            }
        }
    },
    mounted() {    
        Nova.$on('tab-changed', (tab) => {this.handleTabChanges(tab)})     
    },   
    methods: {  
        handleTabChanges(tab) {
            let last = null;   

            this.walkThroughComponents(function(component) {
                if(component.field.tabName == tab.name) { 
                    component.$el.classList.remove('tab-hidden') 
                    last = component; 
                } else if(component.field.tabName) { 
                    component.$el.classList.add('tab-hidden')
                }
            }) 

            last !== null || last.$el.classList.add('remove-bottom-border'); 
        },  
        walkThroughComponents(callback) {
            if(typeof this.components != 'array') {
                this.components = this.searchComponents(this.$root);
            }  

            return this.components.forEach(callback);
        },
        searchComponents($vue) {
            let  $this = this, $components = [];

            $vue.$children.forEach(function(component) {     
                if(component.field && component.field.tabGroup === $this.field.name) { 
                    $components.push(component); 
                } else if(component.$children.length) { 
                    $components = $components.concat($this.searchComponents(component))
                } 
            });

            return $components;
        }
    } 
}
</script>
<style> 
.tab-hidden {
    display: none !important;
}  
</style>