<template>
    <transition name="fade">
    <div class="container" id="configurator">
        <div class="col-group">

            <header class="col-12">
                    <h1>Konfigurieren Sie ein integriertes Transportkältesystem</h1>
                    <p>Auf Basis der Fahrzeugdaten und Ihrer spezifischen Anforderungen finden Sie die optimale Transportkälte-Lösung.</p>
            </header>   

            <section class="col-12">
                    <h2>Folgende Angaben werden benötigt</h2>
                    <div class="column2">

                        <div class="form-group" v-for="config in configurator">
                            <label>{{ config.name }}</label>
                            <select v-model="config.selected" v-on:change="fetch(config.order)" :disabled="config.options.length > 0 ? false : true">
                                <option value="" disabled :hidden="config.options.length > 0 ? true : false">Bitte wählen</option>
                                <option v-for="(option, index) in config.options" v-bind:value="index+1">
                                {{ option }}
                                </option>
                            </select>
                        </div>

                    </div>     
            </section>

            <section class="col-12 topSeparated">
                <div class="col-min">
                    <a class="grey" @click="showModal = true" href="#">Konfiguration abbrechen</a>
                    <a class="grey" @click="reset" href="#">Angaben zurücksetzen</a>
                </div>
                <div class="col-min">
                    <button class="button start" v-bind:class="{ grey: !configuratorDone}" :disabled="!configuratorDone" id="show-modal" @click="showReport">Ergebnis anzeigen</button>
                </div>


            </section>        

            <section class="col-12">
                    <span class="error" v-if="false">Bitte wählen sie einen Punkt aus</span>
            </section>   

        </div>

        <transition name="modal">
        <div id="modal"  v-if="showModal">
            <div class="modal-mask" @click="showModal = false">
              <div class="modal-wrapper">
                <div class="modal-container" @click.stop>
                  <div class="modal-header">
                    <div class="closeModal" @click="showModal = false"></div>
                    <h3>Wollen Sie den Frigo Kit Konfigurator wirklich beenden?</h3>
                  </div>
                  <div class="modal-body">
                  </div>
                  <div class="modal-footer">
                      <button class="button start grey" @click="backToStart">Konfigurator beenden</button>
                  </div>
                </div>
              </div>
            </div>
        </div>        
    </transition>        
    </div>
    </transition>
</template>

<script>
export default {
  name: 'Configurator',
  data () {
    return {
        showModal: false,
        viewConfigurator: false,
        configurator: [
            {order : 1, id: 'brand', name: 'Hersteller', selected: '', options: []},
            {order : 3, id: 'engine', name: 'Motor', selected: '', options: []},
            {order : 5, id: 'orig_ac', name: 'Werksklima', selected: '', options: []},
            {order : 7, id: 'application', name: 'Applikation', selected: '', options: []},            
            {order : 9, id: 'condenser_position', name: 'Kondensator Einbauort', selected: '', options: []},
            {order : 2, id: 'model', name: 'Baureihe', selected: '', options: []},
            {order : 4, id: 'euro_class', name: 'Euro Kategorie', selected: '', options: []},
            {order : 6, id: 'wheel_drive_position', name: 'Antriebsart', selected: '', options: []},
            {order : 8, id: 'evaporator', name: 'Verdampfer', selected: '', options: []}
        ],
        configuratorDone: false,
        configuratorSubmitted: false,
        currentPage: this.$parent.currentPage
    }
  },
  methods: {
      backToStart: function() {
            this.$parent.currentPage = 'Start';
            this.currentPage = this.$parent.currentPage;
      },
      reset: function() {
            for (var index in this.configurator) {
                this.configurator[index].selected = '';
                this.configurator[index].options = '';
            }
            this.fetchBrand();
            this.configuratorDone = false;
      },
      fetchBrand: function() {
            var confVm = this;
            this.$http.post('/de/kits')
            .then(function (response) {
                confVm.configurator.find(arr => arr.id === 'brand').options = response.data.options.brand;
            })
            .catch((error) => {
                console.log(error);
            });
      },
      fetch: function(order) {
            var allOptions = {};
            var confVm = this;
          
            for (var index in confVm.configurator) {
                var opt = confVm.configurator[index];
                if(opt.order <= order)  { 
                    allOptions[opt.id] = opt.options[opt.selected-1]; //this -1 is related to a vuejs select bug, selected can't be 0
                }
            }
          
            this.$http.post('/de/kits', allOptions)
            .then(function (response) {
                
                for (var index in confVm.configurator) {
                    var opt = confVm.configurator[index];
                    if(response.data.options[opt.id] != null) {
                        
                        if(opt.order > order)  { 
                            opt.options = response.data.options[opt.id];
                            if(response.data.options[opt.id].length == 1) {  //if there is only 1 result, select it
                                opt.options = response.data.options[opt.id];
                                opt.selected = 1;
                            } else {
                                opt.selected = '';
                            }
                        }
                        
                    } else {
                        opt.options = null; 
                        opt.selected = null;
                    }
                }
                
                if(Object.keys(response.data.data).length == 1) {
                    for (var first in response.data.data) break; //dirty hack to get the first and only object and pass it to report array
                    confVm.$parent.report = response.data.data[first];
                    confVm.configuratorDone = true;
                } else {
                    confVm.configuratorDone = false;
                }
            })
            .catch((error) => {
                console.log(error);
            }); 
              
        },
        showReport: function() {
            this.$parent.currentPage = 'Report';
            this.currentPage = this.$parent.currentPage;
        }
  },
    mounted: function () {
        this.reset();
        this.fetchBrand();
    }
}
</script>

<style>

</style>
