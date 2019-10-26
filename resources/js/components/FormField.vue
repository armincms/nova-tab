<template>    
    <tab-navigator :tabs="field.tabs"  v-on:tab-changed="handleTabChanges" :fullwidth="field.fullwidth" />
</template>

<script>
import { FormField } from 'laravel-nova' 

export default {
    mixins: [FormField],  
    components: {
        tabNavigator: require('./Navigator.vue') 
    }, 
    mounted() {   
        Nova.$on('tab-changed', (tab) => {this.handleTabChanges(tab)}) 
    },  
    methods: {  
        handleTabChanges(tab) { 
            let $this = this, last = null;  

            this.$parent.$children.filter(function(component) {
                return component.field.tabName === tab.tab
            }).forEach(function(component) {   
                if(component.field.groupName == tab.name) { 
                    component.$el.classList.remove('tab-hidden') 
                    last = component; 
                } else if(component.field.groupName) { 
                    component.$el.classList.add('tab-hidden')
                }  
            })  

            last == null || last.$el.classList.add('remove-bottom-border'); 
        },   
    } 
}
</script>
<style> 
.tab-hidden {
    display: none !important;
}  
</style>