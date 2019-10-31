<template>    
    <tab-navigator 
        :tabs="field.tabs"  
        v-on:tab-changed="handleTabChanges" 
        :fullwidth="field.fullwidth" 
    />
</template>

<script> 
import Tab from './Tab.vue'; 

export default {
    mixins: [Tab],    
    methods: {    
        walkThroughComponents(callback) {
            return this.$parent.$children.forEach(callback);
        },
    } 
} 
</script>