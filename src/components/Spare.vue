<template>
    <transition name="fade">
    <div class="container" id="filter">
        <div class="col-group">

            <header class="col-12">
                    <h1>Ergebnis Ihrer Ersatzteilsuche für das {{ byText }}</h1>
                <p>Ihre Suche nach Ersatzteilen für das Frigo-Einbaukit mit der Artikelnummer {{ idNumber }} liefert folgendes Ergebnis:</p>
            </header>   

            <section class="col-12">
                <table>
                    <tr>
                        <th>Einbauteil</th>
                        <th>Artikelnummer</th>
                    </tr>
                    <tr v-for="option in optionList" v-if="option.value != null">
                        <td>{{ option.name }}</td>
                        <td>{{ option.value }}</td>
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
  name: 'Spare',
  data () {
    return {
        spare: this.$parent.spare,
        currentPage: this.$parent.currentPage,
        idNumber: this.$parent.idNumber,
        by: this.$parent.by,
        byText: '',
        optionList: [
            {id: 'compressor_kit_number', name: 'Kompressor Kit', value: ''},
            {id: 'collector_dryer', name: 'Filter Trockner', value: ''},
            {id: 'pressure_switch', name: 'Druckventil', value: ''},
            {id: 'first_belt_size', name: 'Riemen 1 (Größe)', value: ''},
            {id: 'first_belt_part_no', name: 'Riemen 1', value: ''},
            {id: 'first_pulley', name: 'Umlenkrolle 1', value: ''},            
            {id: 'second_belt_size', name: 'Riemen 2 (Größe)', value: ''},
            {id: 'second_belt_part_no', name: 'Riemen 2 Artikelnr.', value: ''},
            {id: 'second_pulley', name: 'Umlenkrolle 2', value: ''},
            {id: 'third_belt_size', name: 'Riemen 3 (Größe)', value: ''},
            {id: 'third_belt_part_no', name: 'Riemen 3 Artikelnr.', value: ''},
            {id: 'additional', name: 'Zusätzliche Komponenten', value: ''},
            {id: 'pn_additional', name: 'Artikelnummer zusätzliche Komponenten', value: ''},
            {id: 'nozzle', name: 'Düse', value: ''},
            {id: 'compressor', name: 'Kompressor', value: ''},
            {id: 'evaporator_housing', name: 'Verdampfer Gehäuse', value: ''},
            {id: 'evaporator_blower', name: 'Verdampfergebläse', value: ''},
            {id: 'evaporator_with_txv_and_probe_without_cover', name: 'Verdampfer mit TXV und Fühler ohne Gehäuse', value: ''},
            {id: 'expansion_valve_txv', name: 'Expansionsventil (TXV)', value: ''},
            {id: 'condenser_fan', name: 'Kondensatorlüfter', value: ''},
        ],
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
    getParts: function() {
                var vmParts = this;
                for (var result in vmParts.spare) {
                    for (var index in vmParts.optionList) {
                        vmParts.optionList[index].value = vmParts.spare[result][vmParts.optionList[index].id];
                    }
                    break; //as the excel file does, it only returns the first value
                }
    }
  },
    mounted: function () {
        this.getParts();
        if(this.by == 'compressor_kit_number') this.byText ="Kompressor-Einbaukit";
        else if(this.by == 'frigo_kit_number') this.byText ="Frigo-Einbaukit";
    }
}
</script>

<style>

</style>
