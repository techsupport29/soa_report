<template lang="">
      <v-col class="col-md-2">
                    <v-text-field
                        v-model="search"
                       
                        outlined
                        dense
                        label="Search"
                        color="#8DA90B"
                        clearable
                        @click:append="handleSearch"
                        @click:clear="handleClear"
                        
                        v-on:keyup.enter="handleSearch"
                    >
                    <template v-slot:prepend-inner>        
                        <v-icon outlined dark color="#8DA90B">mdi-magnify</v-icon> 
                    </template>
                    </v-text-field>
                        </v-col>
</template>
<script>
import axios from 'axios'
import {toOrderBy} from '../../utility'
export default {
    name: 'search-soa',
    props: {
        soaLists: Function,
        importWithStatus: Function,
        tab: String,
        page: Number
    },
    data:()=>({
        search: ''
    }),
    methods: {
        async handleSearch(tabItem){
            // console.log('searching....',this.search)
            console.log('searching....',tabItem)
            console.log('searchingTAB....',this.tab)
            const status =  tabItem === 'ongoing' || (!tabItem && this.tab ==='ongoing') ? null : 'done'
            // const status =  this.tab === 'ongoing' ? null : 'done'
            const {data} = await axios.get(`api/searchSoa?search=${this.search}&status=${status}&page=${this.page}&per_page=${parseInt(localStorage.getItem('itemsPerPage'))}`)
            this.$emit('searchData', {
                searchData: toOrderBy(data.data),
                total: data.total,
                page: data.current_page,
                search: this.search
            })

            return {
                searchData: toOrderBy(data.data),
                total: data.total,
                page: data.current_page,
                numberOfPages: data.last_page
            }
        },
        async handleClear(){
            this.tab === 'ongoing' ? await this.soaLists() : await this.importWithStatus()
        }
    }
}
</script>
<style lang="">
    
</style>