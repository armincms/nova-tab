<template>    
    <field-wrapper>   
        <p  
            class="remove-last-margin-bottom leading-normal py-4 px-8 text-center cursor-pointer font-bold" 
            v-for="tab in tabs"  
            @click.prevent="setActiveTab(tab)"
            :class="[current == tab.name ? 'border-primary border-b-2 -mb-px' : '', fullwidth === true ? 'w-full' : '']"
        >{{ tab.label }}</p> 
    </field-wrapper> 
</template>

<script> 
export default {
    props : {
        tabs : {
            type: Array, required: true
        }, 
        fullwidth : {
            type: Boolean, default: false
        }
    },
    data() {
        return {
            current: {
                type: String, default: null
            }
        }
    },
    mounted() {   
        this.setActiveTab(this.getActiveTab());

        setTimeout(() => {this.setActiveTab(this.getActiveTab())}, 500);  
    },  
    methods: {
        getActiveTab() { 
            let tab = this.tabs.find(function(tab) {
                return tab.active; 
            }); 

            return tab ? tab : this.tabs[0];
        },
        setActiveTab(tab) {  
            this.current = tab.name;

            this.$emit('tab-changed', tab) 
            Nova.$emit('tab-changed', tab) 
        }, 
    } 
}
</script> 