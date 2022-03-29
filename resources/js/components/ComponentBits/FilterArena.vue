<template lang="">
    <v-col class="col-md-2">
        <v-select
            :items="arenaItemsSelection"
            label="Filter arena"
            dense
            outlined
            item-text="text"
            item-value="key"
            @change="handleSelectionFilterArena"
        ></v-select>
        <loading-progress :loading="loading" />
    </v-col>
</template>
<script>

import {toOrderBy} from '../../utility'
export default {
    name: 'filter-arena',
    props: {
        fetchLists: Function,
        arenaData: Array,
        soaLists: Function,
        importWithStatus: Function,
        dates: Array,
        tab: String,
        page: Number,
        loadDateRange: Function
    },
    data: () => ({
           arenaItemsSelection: [
                {
                    key: "all",
                    text: "All",
                },
                {
                    key: "noArenaDetails",
                    text: "No Arena",
                },
            ],
            loading: false
    }),
    methods: {
        async filterNoArenaDetails(from, to) {
            console.log(this.page)
            let arenaData = []
            // let arenaNoDetais = [];
            const fromTo = this.dates.length > 1  && `from=${this.dates[0]}&to=${to}`
            this.loading = true
            const {data} = await axios.get(`api/filterNoArena?${fromTo}&page=${this.page}&per_page=${parseInt(localStorage.getItem('itemsPerPage'))}`)
            console.log('DATAXXXXXXXXXXXXXX', data)
            this.$emit('noArenaDetails', {noArenaData: toOrderBy(data.data), ...data})
            this.loading = false

            return data


        },

        async handleSelectionFilterArena(item, dates) {
            this.loading = true

            // if(this.dates.length !== 0 && item === 'all' && this.tab === 'ongoing') {
            //     await this.soaLists();
            // } else if (this.dates.length !== 0 && item === 'all' && this.tab === 'converted') {
            //     await this.importWithStatus()
            // } else if (this.dates.length !== 0 && item === "noArenaDetails") {
            //     await this.filterNoArenaDetails(dates[0], dates[1])
            // } else {
            //     await this.filterNoArenaDetails()
            // }
            item === "noArenaDetails" ? this.filterNoArenaDetails() :
            item === 'all' && (this.dates.length !== 0 ? this.loadDateRange(this.tab) : this.tab === 'ongoing' ? this.soaLists() : this.importWithStatus())

            // item === "noArenaDetails"
            //     ? this.filterNoArenaDetails()
            //     : this.dates.length !== 0 ? this.loadDateRange(this.tab) : this.tab === 'ongoing' ? this.soaLists() : this.importWithStatus();
            this.loading = false
            this.$emit('filterText', item)

        },
    },
};
</script>
<style lang=""></style>
