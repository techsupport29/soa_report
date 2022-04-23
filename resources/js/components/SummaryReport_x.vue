<style scoped>
.nav-tabs .nav-link.active {
    background-color: #00c4f5 !important ;
    color: white !important;
}
</style>

<template>
    <v-app>
        <v-container>
            <h1 class="h3 custom-color">Summary Report</h1>
            <v-row class="mt-5">
                <v-col class="col-md-12">
                    <v-row >
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
                    <v-card class="custom-tbl pa-5"    >
                        <div >
                        <v-tabs
                            v-model="tab"
                            align-with-title
                            @change="handleChangeTab"    
                            class="vtabs"
                        >
                            <v-tabs-slider color="#8DA90B"></v-tabs-slider>
                            <v-tab
                                v-for="item in items"
                                :key="item.tabItem"
                                class="custom-tabs"
                                :href="`#${item.tabItem}`"
                                
                            >
                               <span style="font-size:12px; ">{{ item.text }}</span>
                            </v-tab>
                        </v-tabs>
                        </div>
                     
                        <v-card-text>
                            <v-tabs-items v-model="tab" >
                                <v-tab-item id="deposit">
                                    <!-- Table for ongoing soa -->

                                    <v-data-table
                                        :headers="headers"
                                        :items="summaryData"
                                    >
                                        <template  style="font-size:16px;"  v-for="headerData in headers" v-slot:[`header.${headerData.value}`]="{ header }" >
                                            <v-icon medium color="#8DA90B">{{ header.icon }}</v-icon>
                                            <span style="color:#8DA90B"> {{ header.text }} </span>
                                        </template>
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
                                                             <!-- convertToExcel(item.date_of_soa) -->
                                                                <v-btn
                                                                    color="#0B2EA9"
                                                                    rounded
                                                                    dark
                                                                    small
                                                                    v-bind="
                                                                        attrs
                                                                    "
                                                                    v-on="on"
                                                                    @click="
                                                                       convertToExcelSummary( item.date_of_soa)
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
                                        <template  style="font-size:16px;"  v-for="headerData in headers" v-slot:[`header.${headerData.value}`]="{ header }">
                                            <v-icon medium color="#8DA90B">{{ header.icon }}</v-icon>
                                            <span style="color:#8DA90B"> {{ header.text }} </span>
                                        </template>
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
                                                                    color="#0B2EA9"
                                                                    outlined
                                                                    rounded
                                                                    dark
                                                                    small
                                                                    v-bind="
                                                                        attrs
                                                                    "
                                                                    v-on="on"
                                                                    @click="
                                                                         convertToExcelSummary( item.date_of_soa)
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
import { moneyFormat, convertStringToNumber } from "../utility";
import ExcelJS from 'exceljs';
import { saveAs } from 'file-saver'
export default {
    data() {
        return {
            headers: [
                { text: "Date of Event", value: "date_of_soa", icon: "mdi-calendar", sortable: false },
                { text: "", value: "action", sortable: false }
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
            // console.log("loadSummary>>>>>>", page);
            this.loading = true;
            const { data } = await axios.get(
                `api/summaryReport?group=${group}`
            );

            this.summaryData = data;
            this.total = data.total;
            this.loading = false;
        },

        async handlePaginate(e) {
            // console.log("handlePaginate>>>", e.page);
            this.pageNumber = e.page;
            await this.loadSummary(this.group, e.page);
        },

        async handlePageOptions(e) {
            localStorage.setItem("itemsPerPage", e.itemsPerPage);
            this.$emit("pageOption", e);
            // console.log("handlePageOptions>>>", e);
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
            // console.log("Change Tab", this.pageNumber, item);
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

        async convertToExcelSummary(item) {
            const value = moment(item).format("YYYY-MM-DD LTS");
            const date = moment(item).format("MMMM-DD-YYYY");

            const fontColor = (color = 'FFFFFFFF', name = 'Arial', family = 4, size = 9) => {
                return {
                    name,
                    color: { argb: color },
                    family,
                    size,
                    bold: true
                }
            }

            const fillColor = (color = 'FF000000') => {
                return {
                    type: 'pattern',
                    pattern:'solid',
                    fgColor:{argb: color}
                }
            }



            const { data } = await axios.get(
                "api/convertToExcel/" + this.tab + "/" + value
            );
            const workbook = new ExcelJS.Workbook();
            const worksheet = await workbook.addWorksheet("Summary Report",{properties:{tabColor:{argb:'FFC0000'}}});
            const converted = convertStringToNumber(data);

            const convertedResult = converted.map((val, index) => ({
                count:index + 1,
                otherCommissionIntel05:parseFloat(val.otherCommissionIntel05) + parseFloat(val.otherCommIntMob),
                consolidatorsCommission:parseFloat(val.consolidatorsCommission) + parseFloat(val.consolCommMob),
                safetyFund: parseFloat(val.safetyFund) + parseFloat(val.safetyFundMob),
                paymentForOutstandingBalance: parseFloat(val.paymentForOutstandingBalance) +parseFloat(val.payOutsBalMob),
                ...val

            }));

            const tabTitle = this.tab === 'deposit' ? 'SOA' : 'FR';
            // console.log()
            const siteTitle = convertedResult[0].refNo.charAt(1) === 'B' ? 'BRAVO' : 'ALPHA'
           worksheet.columns = [
                        {header:'#' , key: 'count', width: 10 },
                        {header:'Date of Event' , key: 'date_of_soa', width: 20 },
                        {header:'Reference Number' , key: 'refNo', width: 20 },
                        {header:'Arena Name / OCBS Name' , key: 'arena_name', width: 100 },
                        {header:'Other Commission -M' , key: 'otherCommissionIntel05',width: 20},
                        {header:'Consolidators commission' , key: 'consolidatorsCommission',width: 20},
                        {header:'Safety Fund' , key: 'safetyFund',width: 20},
                        {header:'Payment For Outstanding Balance' , key: 'paymentForOutstandingBalance',width: 20},
                        {header:'Amount', key: 'for_total', width: 30 },
            ]
             // Custom design excel
            const internalCheckCells = ['A1', 'B1','C1','D1','E1','F1','G1','H1','I1']

            worksheet.getRow(1).height = 30;
            internalCheckCells.forEach(cell => {
                worksheet.getCell(cell).font = fontColor('FFFFFFFF')
                worksheet.getCell(cell).fill = fillColor('FF000000')
                worksheet.getCell(cell).alignment = {vertical:'middle',horizontal:'center'}
            }),

            worksheet.addRows(convertedResult);

            worksheet.columns.forEach(function (column, i) {
                column["eachCell"]({ includeEmpty: true }, function (cell) {
                    cell.border = {
                        top: {style:'thin'},
                        left: {style:'thin'},
                        bottom: {style:'thin'},
                        right: {style:'thin'}
                    }

                });

                // if(column.letter !== 'A' || !isNaN(column.key)) column.numFmt = '#,##0.00;[Red]\-#,##0.00'
                if(column.letter !== 'A' || !isNaN(column.key)) column.numFmt = '_-* #,##0.00_-;[Color3]-* #,##0.00_-;_-* "-"??_-;_-@_-'

            });

            const endRow = worksheet.lastRow._number + 1;
            worksheet.getCell(`D${endRow+1}`).value = 'TOTAL';
            worksheet.getCell(`D${endRow+1}`).font = fontColor('FF000000', 'Arial Black', 2, 11);
            worksheet.getCell(`I${endRow+1}`).value = { formula: `SUM(I2:I${endRow-1})`, date1904: false };
            worksheet.getCell(`I${endRow+1}`).font = fontColor('FFFFFFFF', 'Arial', 2, 12);
            worksheet.getCell(`I${endRow+1}`).fill = fillColor('FF09140D', 'Arial', 2, 12);
            worksheet.getCell(`I${endRow+1}`).border = { bottom: { style:'double', color: {argb:'FF000000'} } };


            const buf = await workbook.xlsx.writeBuffer()
            saveAs(new Blob([buf]), `${tabTitle} Summary Report_${siteTitle}-${moment(date).format("MMMM DD YYYY")}.xlsx`)


            // console.log('check',data);
        }

    },
};
</script>
<style scoped>
    .custom-color{
    color:#8DA90B;
    }

    .add{
        background-color: #8DA90B;
        color:white;
    }

    .view{
        background-color: #3a89e8;
    }

    .edit{
        background-color: #f0bb00;
    }

    .delete{
        background-color: red;
    }

    .custom-btn{
        width:100px;
        border-radius: 20px;
    }

    .custom-tbl{
        border: 2px solid #8DA90B;
        border-radius: 20px;
    }

    .vtabs{
        width: 320px;
        margin-left:80%;
    }
</style>