<template lang="">
    <v-col class="col-md-2">
        <v-select
            :items="siteItemsSelection"
            color="#8DA90B"
            label="Select Site"
            dense
            outlined
            item-text="text"
            item-value="key"
            v-model="selectChange"
            @change="handleSelectSite"
        >
        <template v-slot:prepend-inner>        
                <v-icon outlined dark color="#8DA90B">mdi-checkbox-multiple-marked</v-icon> 
        </template>
        </v-select>
        <loading-progress :loading="loading" />
    </v-col>
</template>
<script>
export default {
    name: "filter-site",
    props: {
        page: Number,
        fetchLists: Function,
        soaLists: Function,
        importWithStatus: Function,
        tab: String,
        dates: Array,
        searchCentralize: Function,
    },
    data: () => ({
        defaultSelected: {
            key: "all",
            text: "All",
        },
        selectChange: null,
        siteItemsSelection: [
            {
                key: "all",
                text: "All",
            },
            {
                key: "A",
                text: "Site: A",
            },
            {
                key: "B",
                text: "Site: B",
            },
        ],
        loading: false,
    }),
    methods: {
        async handleSelectSite(item) {
            item && (await this.fetchLists(item));
            this.$emit("filteredSite", item);
            localStorage.setItem("site", item);
        },
    },
    created() {
        this.selectChange =
            localStorage.getItem("site") !== null
                ? localStorage.getItem("site")
                : "all";
        // this.selectSite(this.selectChange)
    },
};
</script>
<style lang=""></style>
