<script> 
import { FormField } from 'laravel-nova'

export default { 
    mixins: [FormField],  
    components: {
        tabNavigator: require('./Navigator.vue') 
    }, 
    data: () => {
        return {
            lastComponent : null
        }
    },
    mounted() {    
        Nova.$on('tab-changed', (tab) => {this.handleTabChanges(tab)})   
    }, 
    methods: {  
        handleTabChanges(tab) {  
            this.walkThroughComponents((component) => { 
                if(this.containsComponent(component, tab)) { 
                    if(this.isComment(component)) {
                        this.handleCommentComponent(component, tab);
                    } else {
                        this.handleFieldComponent(component, tab);
                    } 
                }
            }) 

            this.prettifyComponent(this.lastComponent); 
        }, 
        walkThroughComponents(callback) {
            return [].forEach(callback);
        },
        containsComponent(component, tab) {
            return component.field.tabName === tab.tab
        },
        isComment(component) {
            return component.$el.nodeName == '#comment';
        },
        handleCommentComponent(component, tab) {
            if(component.$options.updated == undefined) {
                component.$options.updated = []; 
            }

            component.$options.updated.push(() => {this.handleFieldComponent(component, tab)})
        },
        handleFieldComponent(component, tab) {
            if(this.isComment(component)) {
                return;
            } else if(component.field.groupName != tab.name) {
                component.$el.classList.add('tab-hidden'); 
            } else {
                component.$el.classList.remove('tab-hidden');
                this.lastComponent = component;
            } 
        },
        prettifyComponent(component) {
            if(component) {
               component.$el.classList.add('remove-bottom-border');
            } 
        }   
    }, 
}
</script>
<style> 
.tab-hidden {
    display: none !important;
}  
</style>