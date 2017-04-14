<template>
    <transition name="fade">
    <div class="container" id="filter">
        <div class="col-group">

            <header class="col-12">
                    <h1>Allgemeine Frigo Ersatzteile</h1>
            </header>   

            <section class="col-12">
            <span @click="toggle" class="dropDown">Ergebnis filtern nach: <img class="dropDown" v-bind:class="{ show: showFilters}" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAsAAAAGCAYAAAAVMmT4AAAAa0lEQVQYlX3OLQ6DYBCE4YefkKAqeoUKzoGohYNwBBytb09QX1uF7b1IMB/JlxW8Zncys5ktDB+BCRc8o1EHfccLFQo8crPSjcfeYUWbdI8N/yNQpnnFL9XnLJjzcIMvbvHHeFDjnSrPWGAHgY8LvS8CXdcAAAAASUVORK5CYII="></span>

                <span v-for="filter in filters" class="filter noPrint" v-if="showFilters">
                    <input type="checkbox" v-model="filters[filter.id].status" v-bind:value="filters[filter.id].status" v-bind:id="'filter-'+filter.id" v-on:click="checkFilters">
                    <label v-bind:for="'filter-'+filter.id">
                        <span></span>{{ filter.name }}
                    </label>
                </span>

            </section>
            <hr class="col-12 noPrint">
            <section class="col-12" v-for="list in filters" v-if="showAll||list.status">
                <h2>{{list.name}}</h2>
                <table>
                    <tr>
                        <th>Id Nummer</th>
                        <th>Applicationsteil</th>
                    </tr>
                    <tr v-for="part in parts" v-if="part.category == list.name">
                        <td>{{ part.id }}</td>
                        <td>{{ part.application_parts }}</td>
                    </tr>
                </table>
            </section>

            <section class="col-12 topSeparated noPrint">
                <div class="col-min">
                    <a class="blue" href="#" @click="print">Ergebnis drucken</a>
                </div>
                <div class="col-min">
                    <button class="button start blue" id="show-modal" @click="backToStart">Zurück zur Startseite</button>
                </div>
            </section>        

        </div>
    </div> 
    </transition>
</template>

<script>
export default {
  name: 'SpareList',
  data () {
    return {
        currentPage: this.$parent.currentPage,
        filters: [],
        parts: [],
        showAll: true,
        showFilters: false
    }
  },
  methods: {
    backToStart: function() {
        this.$parent.currentPage = 'Start';
        this.currentPage = this.$parent.currentPage;
    },
	toggle: function() {
        if(this.showFilters) { this.showFilters = false; } else { this.showFilters = true; }
    },
    print: function() {
        window.print();
    },
    checkFilters: function() {
        var vmFilter = this;
        for (var i = 0; i < vmFilter.filters.length; i++) {
            if(vmFilter.filters[i].status) {
                vmFilter.showAll = false;
                break;
            } else {
                vmFilter.showAll = true;
            }
            
        }
    },
    getParts: function() {
                var vmFilter = this;
                this.$http.post('/de/general-spares')
                .then(function (response) {
                    var bulkFilters = [];
                    vmFilter.parts = response.data;
                    
                    //this is for get filter names from excel file and generate filter checkbox list
                    for (var index in response.data) {
                        bulkFilters.push(response.data[index].category);
                    }
                    var uniqFilters = bulkFilters.filter(function (x, i, a) { return a.indexOf(x) == i; }); //uniq filter names
                    for (var u = 0; u < uniqFilters.length; u++) {
                        vmFilter.filters.push({id: u, name: uniqFilters[u], status: false});
                    }

                })
                .catch(function (error) {
                    console.log(error);
                }); 
    }
  },
    mounted: function () {
        this.getParts();
    }
}
</script>

<style>

</style>
