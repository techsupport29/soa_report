<style scoped>
 .nav-tabs .nav-link.active{
    background-color:#00C4F5!important ;
    color:white !important
}
</style>

<template>
    <v-app>
        <v-container >
            
            <v-row class="mt-5">
                <v-col class="col-md-12">
                    <v-card>
                        <v-card-title class="card-header">
                            <strong>Summary Report</strong>
                            <v-spacer></v-spacer>
                        </v-card-title>

                        <div
                            class="card card-primary card-outline card-tabs"
                            style="overflow: auto; !important"
                        >
                            <div class="card-header p-0 pt-1 border-bottom-0">
                                <ul
                                    class="nav nav-tabs"
                                    id="custom-tabs-three-tab"
                                    role="tablist"
                                >
                                    <li class="nav-item">
                                        <a
                                            class="nav-link active"
                                            id="custom-tabs-three-home-tab"
                                            data-toggle="pill"
                                            href="#custom-tabs-three-home"
                                            role="tab"
                                            aria-controls="custom-tabs-three-home"
                                            aria-selected="true"
                                            >Deposit</a
                                        >
                                    </li>
                                    <li class="nav-item">
                                        <a
                                            class="nav-link"
                                            id="custom-tabs-three-profile-tab"
                                            data-toggle="pill"
                                            href="#custom-tabs-three-profile"
                                            role="tab"
                                            aria-controls="custom-tabs-three-profile"
                                            aria-selected="false"
                                            >Replenish</a
                                        >
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div
                                    class="tab-content"
                                    id="custom-tabs-three-tabContent active show"
                                >
                                    <v-row>
                                       
                                        <v-spacer></v-spacer>
                                        <v-col>
                                            <v-text-field
                                                v-model="search"
                                                append-icon="mdi-magnify"
                                                label="Search"
                                                color="primary darken-2"

                                            ></v-text-field>
                                        </v-col>
                                    </v-row>

                                    <div
                                        class="tab-pane fade active show"
                                        id="custom-tabs-three-home"
                                        role="tabpanel"
                                        aria-labelledby="custom-tabs-three-home-tab"
                                    >
                                        <v-data-table
                                            :headers="headers"
                                            :items="deposit"
                                            :search="search"
                                            :items-per-page="-1"
                                            sort-by="date_of_soa"
                                            group-by="date_of_soa"
                                            :group-desc="true"
                                            class="elevation-1 text-center"
                                             single-expand
                                             :footer-props="{
                                                'items-per-page-options': [
                                                    10,
                                                    20,
                                                    30,
                                                    40,
                                                    -1
                                                ],
                                            }"
                                            ref="tableDeposit"
                                        >
                                      <template v-slot:[`group.header`]="{ group, headers, toggle, isOpen }">
                  
                                        <td :colspan="headers.length">
                                                <v-row>
                                                    <v-col class="mt-2 ">
                                                        <div class=" float-left">
                                                            <!-- {{isOpening(isOpen)}} -->
                                                            <v-btn @click="toggle" x-small icon :ref="group" class="test"  >
                                                                <v-icon v-if="isOpen">mdi-plus</v-icon>
                                                                <v-icon v-else>mdi-minus</v-icon>
                                                            </v-btn>
                                                            <span class="mx-5 font-weight-bold">{{ group | myDateSummary  }} </span>
                                                        </div>  
                                                     
                                                    </v-col>
                                                    <v-col>

                                                      <div class=" float-right">
                                                        <v-tooltip top color="green">
                                                            <template v-slot:activator="{ on, attrs }"> 
                                                           <v-btn 
                                                            small  
                                                            @click="convertToExcel(group, 'Deposit')"
                                                            outlined
                                                            color="green" 
                                                            v-bind="attrs"
                                                            v-on="on"
                                                           >
                                                               <v-icon left>
                                                                    mdi-download
                                                                </v-icon>  Download
                                                            </v-btn>
                                                          </template>
                                                        <span>Convert to Excel</span>
                                                        </v-tooltip>
                                                      </div>
                                                    </v-col>
                                                </v-row>
                                              
                                        </td>
                                        </template>
                                        
                                             <template v-slot:[`item.areaCode`]="{ item }">
                                               <span class="font-weight-bold">{{item.areaCode }}</span>
                                            </template>
                                           <template v-slot:[`item.totalCommission`]="{ item }">
                                               <span>{{moneyFormat(parseFloat(item.totalCommission)) }}</span>
                                            </template>
                                           <template v-slot:[`item.otherCommissionIntel05`]="{ item }">
                                               <span>{{moneyFormat(parseFloat(item.otherCommissionIntel05)+parseFloat(item.otherCommIntMob)) }}</span>
                                            </template>
                                             <template v-slot:[`item.consolidatorsCommission`]="{ item }">
                                               <span>{{moneyFormat(parseFloat(item.consolidatorsCommission)+parseFloat(item.consolCommMob))}}</span>
                                            </template>
                                             <template v-slot:[`item.safetyFund`]="{ item }">
                                               <span>{{moneyFormat(parseFloat(item.safetyFund)+parseFloat(item.safetyFundMob))}}</span>
                                            </template>
                                             <template v-slot:[`item.for_total`]="{ item }">
                                               <span class="font-weight-bold">{{moneyFormat(parseFloat(item.for_total))}}</span>
                                            </template>
                                        </v-data-table>
                                    </div>

                                    <div
                                        class="tab-pane fade"
                                        id="custom-tabs-three-profile"
                                        role="tabpanel"
                                        aria-labelledby="custom-tabs-three-profile-tab"
                                    >
                                        <v-data-table
                                            :headers="headers"
                                            :items="reflenish"
                                            :items-per-page="-1"
                                            :search="search"
                                            sort-by="date_of_soa"
                                            group-by="date_of_soa"
                                            :group-desc="true"
                                            class="elevation-1 text-center"
                                             single-expand
                                             :footer-props="{
                                                'items-per-page-options': [
                                                    10,
                                                    20,
                                                    30,
                                                    40,
                                                    -1
                                                ],
                                            }"
                                        >

                                        <template v-slot:[`group.header`]="{ group, headers, toggle, isOpen }">
                                            <td :colspan="headers.length">
                                                    <v-row>
                                                        <v-col class="mt-2 ">
                                                            <div class=" float-left">
                                                                
                                                                <v-btn @click="toggle" x-small icon :ref="group" class="test" >
                                                                    <v-icon v-if="isOpen">mdi-plus</v-icon>
                                                                    <v-icon v-else>mdi-minus</v-icon>
                                                                </v-btn>
                                                                <span class="mx-5 font-weight-bold">{{ group | myDateSummary}}</span>
                                                            </div>  
                                                        
                                                        </v-col>
                                                        <v-col>
                                                        <div class=" float-right">
                                                            <v-btn 
                                                                small  
                                                                @click="convertToExcel(group, 'Replenish')"
                                                                outlined
                                                                color="green" 
                                                            >
                                                                <v-icon left>
                                                                        mdi-download
                                                                    </v-icon>  Download
                                                                </v-btn>
                                                        </div>
                                                        </v-col>
                                                    </v-row>
                                                
                                            </td>
                                        </template>
                                            <template v-slot:[`item.areaCode`]="{ item }">
                                               <span class="font-weight-bold">{{item.areaCode }}</span>
                                            </template>
                                           <template v-slot:[`item.totalCommission`]="{ item }">
                                               <span>{{moneyFormat(parseFloat(item.totalCommission)) }}</span>
                                            </template>
                                           <template v-slot:[`item.otherCommissionIntel05`]="{ item }">
                                               <span>{{moneyFormat(parseFloat(item.otherCommissionIntel05)+parseFloat(item.otherCommIntMob)) }}</span>
                                            </template>
                                             <template v-slot:[`item.consolidatorsCommission`]="{ item }">
                                               <span>{{moneyFormat(parseFloat(item.consolidatorsCommission)+parseFloat(item.consolCommMob))}}</span>
                                            </template>
                                             <template v-slot:[`item.safetyFund`]="{ item }">
                                               <span>{{moneyFormat(parseFloat(item.safetyFund)+parseFloat(item.safetyFundMob))}}</span>
                                            </template>
                                             <template v-slot:[`item.for_total`]="{ item }">
                                               <span class="font-weight-bold">{{moneyFormat(parseFloat(item.for_total))}}</span>
                                            </template>
                                        </v-data-table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-card>
                </v-col>

              
                <!-- </v-col> -->
            </v-row>
            <loading-progress :loading="loading"></loading-progress>
        </v-container>
    </v-app>
</template>
<script>
import moment from "moment";
import XLSX from "xlsx";
import {
    moneyFormat
} from "../utility";
export default {
    
    data() {
        return {
            headers: [
                { text: "Code", value: "areaCode" },
                { text: "Reference", value: "refNo" },
                { text: "Date", value: "date_of_soa"},
                { text: "OCBS/Arena Name", value: "arena_name" },
                { text: "Total Commission", value: "totalCommission" },
                { text: "Othe Commission -M", value: "otherCommissionIntel05" },
                { text: "Consolidator's Commission", value: "consolidatorsCommission" },
                { text: "Safety Fund", value: "safetyFund" },
                { text: "Amount", value: "for_total" },
         
            ],
            deposit:[],
            reflenish:[],
            search:'',
            myBlob:'',
            filteredData:{},
            moneyFormat,
            loading: false
        };  
    },
    methods: {
      
        loadSummary() {
            this.loading = true
            axios
                .get("api/depositeandreflenish")
                .then(({ data }) => {
                    
                    this.deposit = data.dp;
                    this.reflenish = data.rf;
                     this.loading = false
                   
                });
        },
        convertToExcel(data,deprep){

            const value = moment(data).format("YYYY-MM-DD LTS");
            const date = moment(data).format("MMMM-DD-YYYY");
            console.log(value);
            let workbooks =  XLSX.utils.book_new();
            let worksheet = '';
            let aray = [];
            axios.get("api/convertToExcel/"+deprep+"/"+ value).then(({ data }) => (
               
                data.forEach((val) => {
                    console.log(typeof val.for_total)
                    const objVal = {
                        'ID': val.id,
                        'Ref Number': val.refNo,
                        'OCBS Name': val.arena_name,
                        'Total Commission': moneyFormat(parseFloat(val.totalCommission)),
                        'Other Commission -M': moneyFormat(parseFloat(val.otherCommissionIntel05)+parseFloat(val.otherCommIntMob)),
                        'Consolidators commission': moneyFormat(parseFloat(val.consolidatorsCommission)+parseFloat(val.consolCommMob)),
                        'Safety Fund' : moneyFormat(parseFloat(val.safetyFund)+parseFloat(val.safetyFundMob)), 
                        'Payment For O/Standing Balance' : moneyFormat(parseFloat(val.paymentForOutstandingBalance)+parseFloat(val.payOutsBalMob)),
                        'Amount': moneyFormat(parseFloat(val.for_total))
                    }
                    aray.push(objVal);

                }),

               worksheet =  XLSX.utils.json_to_sheet(aray),
               
                XLSX.utils.book_append_sheet(workbooks,worksheet,date),

                XLSX.write(workbooks,{bookType:'xlsx',type:'buffer'}),
                XLSX.write(workbooks,{bookType:'xlsx',type:'binary'}),
                
                XLSX.writeFile(workbooks,`${deprep}-${moment(date).format('MMDDYY')}.xlsx`)

                ));
            
        },

        isOpening(item){
            let open = item
            open = false
            console.log(open)
            return open
        }
  
        
    },
    created() {

        this.loadSummary();
    },
     
};
</script>
