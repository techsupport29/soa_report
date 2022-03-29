<style scoped>
.nav-tabs .nav-link.active {
    background-color: #00c4f5 !important ;
    color: white !important;
}
</style>

<template>
    <v-app>
        <v-container>
            <h1 class="h3">Summary Report</h1>
            <v-row class="mt-5">
                <v-col class="col-md-12">
                    <v-row>
                        <!-- DATE RANGE -->
                        <v-col class="col-md-4">
                            <!-- <v-text-field
                                outlined
                                dense
                                label="Select Date Range"
                                prepend-icon="mdi-calendar"
                                readonly
                                clearable
                            ></v-text-field> -->
                        </v-col>
                        <v-col class="col-md-4">
                            <!-- <v-text-field
                                outlined
                                dense
                                append-icon="mdi-magnify"
                                label="Search"
                                color="primary darken-2"
                                clearable
                            ></v-text-field> -->
                        </v-col>
                        <!-- Search Input -->

                        <v-spacer></v-spacer>
                        <v-col class="col-md-2">
                            <!-- <v-btn>Export</v-btn> -->
                        </v-col>
                    </v-row>
                    <v-card>
                        <v-tabs
                            v-model="tab"
                            align-with-title
                            @change="handleChangeTab"
                        >
                            <v-tabs-slider color="primary"></v-tabs-slider>
                            <v-tab
                                v-for="item in items"
                                :key="item.tabItem"
                                :href="`#${item.tabItem}`"
                            >
                                {{ item.text }}
                            </v-tab>
                        </v-tabs>
                        <v-card-text>
                            <v-tabs-items v-model="tab">
                                <v-tab-item id="deposit">
                                    <!-- Table for ongoing soa -->

                                    <v-data-table
                                        :headers="headers"
                                        :items="summaryData"
                                    >
                                        <template v-slot:item="{ item }">
                                            <tr>
                                                <td>
                                                    <h6
                                                        class="ma-0 font-weight-black"
                                                    >
                                                        {{
                                                            moment(
                                                                item.date_of_soa
                                                            ).format("LL")
                                                        }}
                                                    </h6>
                                                </td>
                                                <td>
                                                    <div
                                                        class="d-flex justify-end"
                                                    >
                                                        <v-tooltip
                                                            top
                                                            color="primary"
                                                        >
                                                            <template
                                                                v-slot:activator="{
                                                                    on,
                                                                    attrs,
                                                                    hover,
                                                                }"
                                                            >
                                                                <v-btn
                                                                    color="success"
                                                                    dark
                                                                    small
                                                                    v-bind="
                                                                        attrs
                                                                    "
                                                                    v-on="on"
                                                                    @click="
                                                                        convertToExcel(
                                                                            item.date_of_soa
                                                                        )
                                                                    "
                                                                    :class="{
                                                                        'on-hover':
                                                                            hover,
                                                                    }"
                                                                >
                                                                    Export
                                                                </v-btn>
                                                            </template>
                                                            <span
                                                                >Export to
                                                                Excel</span
                                                            >
                                                        </v-tooltip>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </v-data-table>
                                    <!-- <v-row class="pa-1">
                                                <v-col class="col-md-6 pa-1 d-flex align-center">
                                                     <p class="ma-0 font-weight-black">{{moment(item.date_of_soa).format('LL')}}</p>
                                                </v-col>
                                                <v-col class="col-md-6 pa-1 d-flex justify-end">
                                                    <v-btn dense @click="convertToExcel(item.date_of_soa)">Export</v-btn>
                                                </v-col>
                                            </v-row> -->

                                    <!-- <v-data-table
                                        :headers="headers"
                                        :items="summaryData"
                                        :server-items-length="total"
                                        :options="paginationOption"
                                        @pagination="handlePaginate"
                                        @update:options="handlePageOptions"
                                    ></v-data-table> -->
                                </v-tab-item>
                                <v-tab-item id="replenish">
                                    <!-- Table for converted soa -->
                                    <v-data-table
                                        :headers="headers"
                                        :items="summaryData"
                                    >
                                        <template v-slot:item="{ item }">
                                            <tr>
                                                <td>
                                                    <h6
                                                        class="ma-0 font-weight-black"
                                                    >
                                                        {{
                                                            moment(
                                                                item.date_of_soa
                                                            ).format("LL")
                                                        }}
                                                    </h6>
                                                </td>
                                                <td>
                                                    <div
                                                        class="d-flex justify-end"
                                                    >
                                                        <v-tooltip
                                                            top
                                                            color="primary"
                                                        >
                                                            <template
                                                                v-slot:activator="{
                                                                    on,
                                                                    attrs,
                                                                    hover,
                                                                }"
                                                            >
                                                                <v-btn
                                                                    color="success"
                                                                    dark
                                                                    small
                                                                    v-bind="
                                                                        attrs
                                                                    "
                                                                    v-on="on"
                                                                    @click="
                                                                        convertToExcel(
                                                                            item.date_of_soa
                                                                        )
                                                                    "
                                                                    :class="{
                                                                        'on-hover':
                                                                            hover,
                                                                    }"
                                                                >
                                                                    Export
                                                                </v-btn>
                                                            </template>
                                                            <span
                                                                >Export to
                                                                Excel</span
                                                            >
                                                        </v-tooltip>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </v-data-table>
                                    <!-- <v-card dense class="pa-1 mb-2 d-flex justify-between align-center" outlined v-for="item in summaryData" :key="item.date_of_soa">
                                        <v-card-text class="px-4 py-2 d-flex justify-between align-center">
                                            <v-row class="pa-1">
                                                <v-col class="col-md-6 pa-1 d-flex align-center">
                                                     <p class="ma-0 font-weight-black">{{moment(item.date_of_soa).format('LL')}}</p>
                                                </v-col>
                                                <v-col class="col-md-6 pa-1 d-flex justify-end">
                                                    <v-btn dense @click="convertToExcel(item.date_of_soa)">Export</v-btn>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-card> -->
                                    <!-- <v-data-table
                                        :headers="headers"
                                        :items="summaryData"
                                        :server-items-length="total"
                                        :options="paginationOption"
                                        @pagination="handlePaginate"
                                        @update:options="handlePageOptions"
                                    ></v-data-table> -->
                                </v-tab-item>
                            </v-tabs-items>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            <loading-progress :loading="loading"></loading-progress>
        </v-container>
    </v-app>
</template>
<script>
import moment from "moment";
import XLSX from "xlsx";
import { moneyFormat } from "../utility";
export default {
    data() {
        return {
            headers: [
                { text: "Date of Event", value: "date_of_soa" },
                { text: "", value: "action", sortable: false },
                // { text: "Code", value: "areaCode" },
                // { text: "Reference", value: "refNo" },
                // { text: "Date", value: "date_of_soa" },
                // { text: "OCBS/Arena Name", value: "arena_name" },
                // { text: "Total Commission", value: "totalCommission" },
                // { text: "Othe Commission -M", value: "otherCommissionIntel05" },
                // {
                //     text: "Consolidator's Commission",
                //     value: "consolidatorsCommission",
                // },
                // { text: "Safety Fund", value: "safetyFund" },
                // { text: "Amount", value: "for_total" },
            ],
            items: [
                {
                    text: "For Deposit",
                    tabItem: "deposit",
                },
                {
                    text: "For Replenish",
                    tabItem: "replenish",
                },
            ],
            tab: null,
            summaryData: [],
            deposit: [],
            reflenish: [],
            search: "",
            myBlob: "",
            filteredData: {},
            moneyFormat,
            moment,
            loading: false,
            total: 0,
            pageNumber: 1,
            group: "",
            tab: null,
            paginationOption: {
                itemsPerPage:
                    parseInt(localStorage.getItem("itemsPerPage")) || 10,
            },
        };
    },
    methods: {
        async loadSummary(group, page) {
            console.log("loadSummary>>>>>>", page);
            this.loading = true;
            const { data } = await axios.get(
                `api/summaryReport?group=${group}`
            );

            this.summaryData = data;
            this.total = data.total;
            this.loading = false;
        },

        async handlePaginate(e) {
            console.log("handlePaginate>>>", e.page);
            this.pageNumber = e.page;
            await this.loadSummary(this.group, e.page);
        },

        async handlePageOptions(e) {
            localStorage.setItem("itemsPerPage", e.itemsPerPage);
            this.$emit("pageOption", e);
            console.log("handlePageOptions>>>", e);
            this.pageNumber = e.page;

            // this.paginationOption = {
            //     itemsPerPage: e.itemsPerPage,
            //     ...e,
            // };
        },
        async handleChangeTab(item) {
            // Swicth between menu tab: deposit and replenish
            this.pageNumber = 1;
            this.group = item;
            console.log("Change Tab", this.pageNumber, item);
            await this.loadSummary(item, this.pageNumber);
        },
        async convertToExcel(item) {

            const value = moment(item).format("YYYY-MM-DD LTS");
            const date = moment(item).format("MMMM-DD-YYYY");
            let workbooks = XLSX.utils.book_new();
            let worksheet = "";

            const { data } = await axios.get(
                "api/convertToExcel/" + this.tab + "/" + value
            );

            const arraySheet = data.map((val, index) => ({
                ID: index + 1,
                "Date of Event": date,
                "Ref Number": val.refNo,

                "OCBS Name": val.arena_name,
                "Total Commission": moneyFormat(
                    parseFloat(val.totalCommission)
                ),
                "Other Commission -M": moneyFormat(
                    parseFloat(val.otherCommissionIntel05) +
                        parseFloat(val.otherCommIntMob)
                ),
                "Consolidators commission": moneyFormat(
                    parseFloat(val.consolidatorsCommission) +
                        parseFloat(val.consolCommMob)
                ),
                "Safety Fund": moneyFormat(
                    parseFloat(val.safetyFund) + parseFloat(val.safetyFundMob)
                ),
                "Payment For O/Standing Balance": moneyFormat(
                    parseFloat(val.paymentForOutstandingBalance) +
                        parseFloat(val.payOutsBalMob)
                ),
                Amount: moneyFormat(parseFloat(val.for_total)),
            }));


            const tabTitle = this.tab === 'deposit' ? 'SOA' : 'FR'
            const siteTitle = arraySheet[0]['Ref Number'].charAt(1) === 'B' ? 'BRAVO' : 'ALPHA'
            console.log()
            worksheet =  XLSX.utils.json_to_sheet(arraySheet);
            delete worksheet["E2"].w;
             XLSX.utils.format_cell(worksheet["E2"]);
            // console.log(worksheet['E2'].v),
             XLSX.utils.format_cell(workbooks, worksheet);
             XLSX.utils.book_append_sheet(workbooks, worksheet, date);
             XLSX.write(workbooks, { bookType: "xlsx", type: "buffer" });
             XLSX.write(workbooks, { bookType: "xlsx", type: "binary" });
             XLSX.writeFile(
                workbooks,
                `${tabTitle} Summary Report_${siteTitle}-${moment(date).format("MMMM DD YYYY")}.xlsx`
            );

        },
    },
};
</script>
