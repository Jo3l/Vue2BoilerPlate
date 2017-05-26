<template>
    <transition name="fade">
    <div class="container" id="start">
        <div class="col-group">

		<carousel>
		  <slide>
		    Slide 1 Content
		  </slide>
		  <slide>
		    Slide 2 Content
		  </slide>
		   <slide>
		    Slide 3 Content
		  </slide>
		  <slide>
		    Slide 4 Content
		  </slide>
		    <slide>
		    Slide 5 Content
		  </slide>
		  <slide>
		    Slide 6 Content
		  </slide>
		    <slide>
		    Slide 7 Content
		  </slide>
		  <slide>
		    Slide 8 Content
		  </slide>
		    <slide>
		    Slide 9 Content
		  </slide>
		  <slide>
		    Slide 10 Content
		  </slide>
		    <slide>
		    Slide 11 Content
		  </slide>
		  <slide>
		    Slide 12 Content
		  </slide>
		</carousel>

            <section class="col-12">
                <div class="fieldset">
                    <div v-for="(dates, index) in links">
                    	<p>{{ index }}</p>
    					<div v-for="link in dates">
	                    	<p><ui-button><!-- {{ users[link.LNK_USER].avatar }}  --> {{ users[link.LNK_USER].name }}</ui-button></p>
	                        <p><a :href="link.LNK_URL">{{ link.LNK_TEXT }}</a></p>
    					</div>
                    </div>
                </div>
            </section>
		
		
		
		<ui-button @click="post()">Hello world!</ui-button>

	        <ui-icon-button
	            color="white"
	            icon=" "
	            size="normal"
	            type="secondary"
	            v-bind:style="{ background:'radial-gradient(ellipse at center, rgba(68,126,185,0) 36%,rgb(33, 150, 243) 38%),url(' + image + ') no-repeat center / cover' }"
	            ></ui-icon-button>

	        <ui-icon-button
	            color="white avatar"
	            icon=" "
	            size="small"
	            type="secondary"
	            v-bind:style="{ background: 'url(' + image + ') center / cover' }"
	            
	            ></ui-icon-button>

	        <ui-icon-button
	            color="white"
	            icon=" "
	            size="large"
	            type="secondary"
	            v-bind:style="{ background: 'url(' + image + ') no-repeat center / cover' }"
	            ></ui-icon-button>
	            
        </div>

    </div>
    </transition>
</template>

<script>

export default {
  name: 'Start',
  data () {
    return {
    	image:'https://pbs.twimg.com/profile_images/1278521940/domokun_400x400.png',
        currentPage: this.$parent.currentPage,
        pagina: 1,
        links:{},
        tag: {},
        tagNames: {},
        users: {}
    }
  },
  methods: {
  	dayGroup: function(dateStr) {
  		
  		return dateStr.split(' ')[0]
  		
  		if (vm.day != dateStr.split(' ')[0]) {
  			//vm.day = dateStr.split(' ')[0];
  			//return true;
  		} else {
			//return false;
  		}
  	},
  	loadingBar: function(data){
			this.$parent.$emit('loadingBar', data);
	},
    getLinks: function() {

        var vm = this;
		vm.loadingBar(true);
        this.$http.get('')
        .then(function (response) {
            
            console.log(response);
            vm.links = response.data.links;
            vm.tag = response.data.tag;
            vm.tagNames = response.data.tagNames;
            vm.users = response.data.users;
            vm.loadingBar(false);
            
            console.log(vm.links);
            
            
        })
        .catch(function (error) {
            console.log(error);
        });
        
    },
    post: function() {
    	console.log(this.links);
    	this.$http.post('', {'user':'joel', 'password':'cibermatch'} ).then(function (response) { console.log(response) });
    }
  },
    mounted: function () {
		this.getLinks();
    },
    watch: {

    }
}
</script>

<style>

</style>
