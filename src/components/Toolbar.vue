<template>
	
	<ui-toolbar
        brand="Indiza"
        :loading="loadingBar"
        progress-position="top"
        title="lalala"
        type="clear"
        :raised="false"
    >

	    <div slot="actions">
	        <ui-icon-button
	            color="black avatar"
	            icon=" "
	            size="large"
	            type="secondary"
	            v-bind:style="{ background:'radial-gradient(ellipse at center, rgba(255,255,255,0) 36%,rgb(255,255,255) 38%),url(' + image + ') no-repeat center / 2em' }"
	        ></ui-icon-button>
	
            <ui-icon-button
                color="black"
                icon="arrow_back"
                size="large"
                type="secondary"
            ></ui-icon-button>

            <ui-icon-button
                color="black"
                icon="search"
                size="large"
                type="secondary"
                @click="toggleBar()"
            ></ui-icon-button>

            <ui-icon-button
                color="black"
                has-dropdown
                icon="more_vert"
                ref="dropdownButton8"
                size="large"
                type="secondary"
            >
                <ui-menu
                    contain-focus
                    has-icons
                    slot="dropdown"
                    :options="menuOptions"
                    @close="$refs.dropdownButton8.closeDropdown()"
                ></ui-menu>
        	</ui-icon-button>
	    </div>
	</ui-toolbar>

</template>

<script>

export default {
	name: 'Toolbar',
	data () {
	return {
		loadingBar: false,
		image:'https://pbs.twimg.com/profile_images/1278521940/domokun_400x400.png',
		menuOptions: [
		    {
		        label: 'Settings'
		    },
		    {
		        label: 'About'
		    },
		    {
		        label: 'Help'
		    }
		]
	}
	},
	methods: {
		toggleBar: function() {
		this.loadingBar = !this.loadingBar;
		console.log(this.loadingBar);
		}
	
	},
	created: function(){
		var vm = this;
		this.$parent.$on('loadingBar', function(data){
			vm.loadingBar = data;
		})
	},
    watch: {

    }
}
</script>