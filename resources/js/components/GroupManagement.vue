<template>
    <v-app>
        <v-container>
            <v-row
                class="mt-5 is-blurred flex-column flex-md-row"
                v-if="$gate.isAdminTech()"
            >
                <v-col class="col-md-6">
                    <v-card>
                        <v-card-title class="card-header">
                            Group Management
                            <v-spacer></v-spacer>
                            <v-card-actions class="card-tools">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="success"
                                            dark
                                            v-bind="attrs"
                                            v-on="on"
                                            class="mx-2"
                                            @click="openModal"
                                        >
                                            <v-icon
                                                >mdi-account-multiple-plus</v-icon
                                            >Group Name
                                        </v-btn>
                                    </template>
                                    <span>Add group name</span>
                                </v-tooltip>
                            </v-card-actions>
                        </v-card-title>

                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Search"
                            class="mx-4"
                        ></v-text-field>

                        <v-data-table
                            :headers="headers"
                            :items-per-page="10"
                            :search="search"
                            :items="operatorGroups"
                            class="elevation-1 text-center"
                        >
                            <template v-slot:[`item.count`]="{ item }">
                                <strong> {{ item.hasgroup.length }}</strong>
                            </template>
                            <template v-slot:[`item.actions`]="{ item }">
                                <v-tooltip color="primary" bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="primary"
                                            icon
                                            dark
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="openViewGroup(item)"
                                        >
                                            <v-icon>mdi-view-column</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>View {{ item.name }}</span>
                                </v-tooltip>

                                <v-tooltip color="yellow darken-4" bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="yellow darken-4"
                                            icon
                                            dark
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="updateGroupModal(item)"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </v-btn>
                                    </template>
                                    <span>Edit {{ item.name }}</span>
                                </v-tooltip>

                                <v-tooltip color="error" bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="error"
                                            dark
                                            icon
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="deleteGroupModal(item.id)"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </v-btn>
                                    </template>
                                    <span>Delete {{ item.name }}</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-col>
                <v-col class="col-md-6">
                    <v-banner
                        v-if="!viewGroup"
                        single-line
                        transition="slide-y-transition"
                        >No Group selected yet
                    </v-banner>

                    <v-card v-else>
                        <v-card-title class="card-header">
                            {{ this.selectedGroup.name }} Arena List
                            <v-spacer></v-spacer>
                            <v-tooltip bottom color="green">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="green"
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        class="mx-2"
                                        @click="openEmail()"
                                    >
                                        <v-icon>mdi-at</v-icon> Pick Date
                                    </v-btn>
                                </template>
                                <span
                                    >Send Email Zip to
                                    {{ this.selectedGroup.email }}</span
                                >
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="primary"
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        class="mx-2"
                                        @click="openArenaModal"
                                    >
                                        <v-icon>mdi-plus-thick</v-icon> Add
                                        Arena
                                    </v-btn>
                                </template>
                                <span
                                    >Add Arena to
                                    {{ this.selectedGroup.name }}</span
                                >
                            </v-tooltip>
                        </v-card-title>

                        <v-text-field
                            v-model="searchgroup"
                            append-icon="mdi-magnify"
                            label="Search"
                            class="mx-4"
                        ></v-text-field>

                        <v-data-table
                            :headers="Groupheaders"
                            :items-per-page="10"
                            :search="searchgroup"
                            :items="groupHasArena"
                            class="elevation-1 text-center"
                        >
                            <template v-slot:[`item.actions`]="{ item }">
                                <v-tooltip color="error" bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="error"
                                            dark
                                            icon
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="RemoveToGroup(item.id)"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </v-btn>
                                    </template>
                                    <span>Delete {{ item.name }}</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-col>
            </v-row>

            <!-- create group -->
            <v-dialog
                transition="dialog-bottom-transition"
                max-width="400"
                v-model="openDialog"
            >
                <v-card>
                    <v-toolbar elevation="0">
                        <v-toolbar-title class="text-h6 pl-0">
                            {{
                                editMode === true
                                    ? "Update Team"
                                    : "Add New Group"
                            }}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    medium
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="closeDialog()"
                                >
                                    <v-icon medium>mdi-close</v-icon>
                                </v-btn>
                            </template>
                            <span>Close Modal</span>
                        </v-tooltip>
                    </v-toolbar>

                    <v-card-text>
                        <v-text-field
                            v-model="group.name"
                            label="Group Name"
                            placeholder="GOFW"
                            outlined
                            required
                            :error-messages="errors.name"
                            prepend-inner-icon="mdi-account-group"
                        ></v-text-field>
                        <v-text-field
                            v-model="group.email"
                            label="Email Address"
                            placeholder="johndoe@gmail.com"
                            outlined
                            required
                            :error-messages="errors.email"
                            prepend-inner-icon="mdi-email"
                        ></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn
                            :color="editMode === true ? 'primary' : 'success'"
                            @click="
                                editMode === true
                                    ? updateGroups()
                                    : createGroups()
                            "
                            ><v-icon>{{
                                editMode === true ? "mdi-update" : "mdi-plus "
                            }}</v-icon>
                            {{ editMode === true ? "Update" : "Add" }}</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- add arena -->
            <v-dialog
                transition="dialog-bottom-transition"
                max-width="600"
                v-model="openAddArenaDialog"
            >
                <v-card>
                    <v-toolbar elevation="0">
                        <v-toolbar-title class="text-h6 pl-0">
                            {{
                                editMode === true
                                    ? "Update Group"
                                    : "Add New Group"
                            }}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    medium
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="openAddArenaDialog = false"
                                >
                                    <v-icon medium>mdi-close</v-icon>
                                </v-btn>
                            </template>
                            <span>Close Modal</span>
                        </v-tooltip>
                    </v-toolbar>
                    <v-text-field
                        v-model="searchArena"
                        append-icon="mdi-magnify"
                        label="Search Arena"
                        class="mx-4"
                    ></v-text-field>
                    <v-data-table
                        v-model="selectedArena"
                        :headers="SelectArenaheaders"
                        :items="arena"
                        :search="searchArena"
                        show-select
                        :loading="false"
                        class="elevation-1"
                        @toggle-select-all="selectAllToggle"
                    >
                        <template v-slot:item="{ item, isSelected, select }">
                            <tr>
                                <td>
                                    <v-simple-checkbox
                                        :value="isSelected"
                                        :readonly="item.disabled"
                                        :disabled="item.bank_details.length == 0 || item.email_details.length == 0 ? true : false"
                                        @input="select($event)"
                                    ></v-simple-checkbox>
                                </td>
                                <td>
                                    {{item.arena}}
                                </td>
                            </tr>
                        </template>
                    </v-data-table>

                    <v-card-actions class="justify-end">
                        <v-btn
                            color="red darken-1"
                            text
                            @click="
                                () => {
                                    openAddArenaDialog = false;
                                    addNewArenaItem = false;
                                    this.selectedArena = [];
                                }
                            "
                            >Cancel</v-btn
                        >
                        <v-btn
                            :disabled="selectedArena < 1 ? true : false"
                            color="blue darken-1"
                            text
                            @click="addarenaintoGroup"
                            >ADD</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <!-- SOA DATES -->
            <v-dialog
                transition="dialog-bottom-transition"
                max-width="800"
                v-model="openEmailDialog"
            >
                <v-expansion-panels>
                    <v-expansion-panel
                        v-for="item in dates"
                        :key="item.i"
                        @click="handleExpand(item)"
                    >
                        <v-expansion-panel-header>
                            {{ moment(item, 'YYYY-MM-DD').format("LL") }}
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-col>
                                <v-row>
                                    <v-spacer></v-spacer>
                                    <v-btn @click="sendingEmail">EMAIL</v-btn>
                                </v-row>
                                <v-row>
                                    <v-data-table
                                        :headers="headerImports"
                                        :items="groupOperatorsArena"
                                    >
                                        <template
                                            v-slot:[`item.actions`]="{ item }"
                                        >
                                            <v-btn
                                                color="primary"
                                                class="mx-2"
                                                dark
                                                @click="viewItemImport(item)"
                                            >
                                                View
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                </v-row>
                            </v-col>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
                <!-- <v-data-table :headers="headerDialogEmail" :items="dates">
                    <template v-slot:item="{ item }">
                        <tr>
                            <td>
                                <h6 class="ma-0 font-weight-black">
                                    {{ moment(item.date_of_soa).format("LL") }}
                                </h6>
                            </td>
                            <td>
                                <div class="d-flex justify-end">
                                    <v-tooltip top color="white">
                                        <template
                                            v-slot:activator="{
                                                on,
                                                attrs,
                                                hover,
                                            }"
                                        >
                                            <v-btn
                                                color="green"
                                                dark
                                                small
                                                v-bind="attrs"
                                                v-on="on"
                                                @click="
                                                    sendingEmail(
                                                        item.date_of_soa
                                                    )
                                                "
                                                :class="{
                                                    'on-hover': hover,
                                                }"
                                            >
                                                Send
                                            </v-btn>

                                        </template>
                                        <div class="text-dark">
                                             <label>Send Email to : </label><br>
                                            <div style="border:1px solid black;" >
                                                <p class="ma-4"> <strong>{{selectedEmail}}</strong></p>
                                            </div>
                                             <label>CC Email : </label><br>

                                            <div style="border:1px solid black;" >
                                                <span class="mr-1" v-for="cc in emailcc" :key="cc"> "{{cc}}",</span>
                                            </div>

                                            <label> Arena List : </label><br>

                                            <div style="border:1px solid black;">
                                                <span v-if="areaCodesname.length == 0 "> <i></i></span>
                                                <span v-else v-for="code in areaCodesname" :key="code">  {{code }}, </span><br>
                                            </div>

                                        </div>
                                    </v-tooltip>

                                </div>
                            </td>
                        </tr>
                    </template>
                </v-data-table> -->
            </v-dialog>
            <v-dialog
                v-model="soaDialogItem"
                transition="dialog-bottom-transition"
                content-class="my-custom-dialog"
                scrollable
                persistent
                width="800px"
            >
            <v-card>
                 <v-col>
                    <v-row>
                        <v-spacer></v-spacer>
                        <v-chip
                            small
                            class="ma-2"
                            close
                            color="red"
                            text-color="white"
                        
                            @click="soaDialogItem = false"
                        >
                            Close
                        </v-chip>
                    </v-row>
                    <v-row>
                        <soa-card :item="soaItem"></soa-card>
                    </v-row>
                </v-col>
            </v-card>
               
            </v-dialog>
            <soa-cards :selected="groupOperatorsArena"></soa-cards>
            <loading-progress
                :loading="loading"
                :progressvalue="progressvalue"
                :downloadingReport="downloadingReport"
                progressText="Sending Email.. Don't Touch me Not.."
            />
        </v-container>
    </v-app>
</template>

<script>
import axios from "axios";
import moment from "moment";
import html2canvas from "html2canvas";
import JSZip from "jszip";
import JSZipUtils from "jszip-utils";
import { saveAs } from "file-saver";
import { flattenDeep, uniq } from "lodash";
import SoaCards from "./SoaComponents/SoaCards.vue";
import SoaCard from "./SoaComponents/SoaCard.vue";

export default {
    components: {
        SoaCards,
        SoaCard,
    },
    data() {
        return {
            headerDialogEmail: [
                { text: "Date of Event", value: "date_of_soa" },
                { text: "", value: "action", sortable: false },
            ],
            headers: [
                { text: "Group Name", value: "name" },
                { text: "Email", value: "email" },
                { text: "No. of Arenas", value: "count" },
                { text: "", value: "actions", sortable: false },
            ],
            headerImports: [
                { text: "#", value: "id" },
                { text: "Area Code", value: "areaCode", sortable: false },
                { text: "Reference #", value: "refNo", sortable: false },
                { text: "Arena Name", value: "arena_name", sortable: false },
                { text: "", value: "actions", sortable: false },
            ],
            Groupheaders: [
                { text: "Arena/OCBS Name", value: "area_code" },
                { text: "", value: "actions", sortable: false },
            ],
            SelectArenaheaders: [
                { text: "Arena/OCBS Name", value: "arena_name" },
            ],
            search: "",
            searchgroup: "",
            searchArena: "",
            openDialog: false,
            openAddArenaDialog: false,
            editMode: false,
            viewGroup: false,
            addNewArenaItem: false,
            arena: [],
            selectedGroup: [],
            selectedArena: [],
            operatorGroups: [],
            groupHasArena: [],
            errors: {
                name: "",
                email: "",
            },
            group: {
                id: "",
                name: "",
                email: "",
            },
            dates: [],
            openEmailDialog: false,

            moment,
            areaCodesname: [],
            groupOperatorsArena: [],
            progressvalue: 0,
            loading: false,
            downloadingReport:false,
            emailcc:[],
            selectedItems: [],
            selectedEmail:'',
        };
    },
    methods: {
        // Select all imports with arena details
        selectAllToggle(props) {

            let dis = 0;

            this.selectedItems = props.items;

            props.items.map((x) => {

                if (x.bank_details.length == 0 || x.email_details.length == 0) dis += 1;
            });
            console.log(props.items.length - dis);

            if (this.selectedArena != props.items.length - dis) {

                this.selectedArena = [];

                const self = this;

                props.items.forEach((item) => {

                    if (item.bank_details.length !== 0  && item.email_details.length !== 0 ) {
                        self.selectedArena.push(item);
                    }
                });
                // this.$emit('selectedSoa', this.selectedArena)
            } else {
                console.log('false');
                this.selectedArena = []
                this.$emit('selectedArena', [])
            };
        },
        resetTable(){
            console.log('xxxRESETxxxx')
            this.selected = []
        },
        singleSelected(item){
            this.$emit('selectedSoa', item)
        },

        async handlePaginate(e){
              this.pagePosition  = e.page
                // console.log('Tabbbbb>>>>', this.tab)
                  this.$emit('loading', true)
                    await this.fetchLists(localStorage.getItem('site'));
                    this.$emit('loading', false)




        },
        ManageCC(){
            axios.get('api/emails').then(({data})=>{
                // this.emailcc = data.data;
                this.emailcc = data.map((sCc) => {
                    return sCc.email_cc;
                });
            });
        },
        closeDialog() {
            this.openDialog = false;
            this.addNewArenaItem = false;
            this.selectedArena = [];
        },
        addarenaintoGroup() {
            let arenasTeam = [];

            this.selectedArena.forEach((a) => {
                const { bank_details, contact_details, ...arenas } = a;
                arenasTeam.push({ ...arenas });
            });

            axios
                .post("api/addtogroup", {
                    id: this.selectedGroup.id,
                    data: arenasTeam,
                })
                .then(({ data }) => {
                    Toast.fire({
                        icon: "success",
                        title: "Arena Added in successfully",
                    });
                    this.openAddArenaDialog = false;
                    this.viewGroup = false;
                    Fire.$emit("AfterCreate");
                });
        },
        getAllArena() {
            axios.get("api/arena").then(({ data }) => {
                console.log(data);
                this.arena = data;
            });
        },
        getallGroups() {
            axios.get("api/groups").then(({ data }) => {
                this.operatorGroups = data;
            });
        },

        openModal() {
            this.group.name = null;
            this.editMode = false;
            this.openDialog = true;
        },
        openArenaModal() {
            this.openAddArenaDialog = true;
        },
        createGroups() {
            axios
                .post("api/groups", this.group)
                .then((data) => {
                    Toast.fire({
                        icon: "success",
                        title: "Group Created in successfully",
                    });
                    this.openDialog = false;
                    Fire.$emit("AfterCreate");
                })
                .catch((error) => {
                    this.errors.name = error.response.data.errors?.name;
                    this.errors.email = error.response.data.errors?.email;
                });
        },
        updateGroupModal(item) {
            this.editMode = true;
            this.openDialog = true;
            this.group.name = item.name;
            this.group.email = item.email;
            this.group.id = item.id;
        },
        updateGroups() {
            axios
                .put("api/groups/" + this.group.id, {
                    name: this.group.name,
                    email: this.group.email,
                })
                .then((data) => {
                    Toast.fire({
                        icon: "success",
                        title: "Team Updated in successfully",
                    });
                    this.openDialog = false;
                    Fire.$emit("AfterCreate");
                })
                .catch((error) => {
                    this.errors.name = error.response.data.errors?.name;
                    this.errors.email = error.response.data.errors?.email;
                });
        },
        deleteGroupModal(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    axios
                        .delete("api/groups/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                            Fire.$emit("AfterCreate");
                        })
                        .catch(() => {
                            swal.fire(
                                "Failed!",
                                "There was something wrong.",
                                "warning"
                            );
                        });
                }
            });
        },
        openViewGroup(item) {
            this.viewGroup = true;
            this.selectedGroup = item;
            this.selectedEmail = item.email;
            axios.get("api/getselectedgroup/" + item.id).then(({ data }) => {
                data.forEach((element) => {
                    this.groupHasArena = element.hasgroup;
                });
            });
        },
        async openEmail() {
            this.openEmailDialog = true;
            const { data } = await axios.get(`api/groupSoaSummaryReport`);
            const areaCodes = this.groupHasArena.map((item) => item.area_code);
            this.areaCodesname = areaCodes;
            const uniqDate = data.map(item => moment(item.date_of_soa).format('YYYY-MM-DD'))
            console.log(uniqDate)
            this.dates = uniq(uniqDate);
        },

        RemoveToGroup(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    axios
                        .get("api/deletegroupsselectedarena/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                            Fire.$emit("AfterCreate");
                            this.viewGroup = false;
                        })
                        .catch(() => {
                            swal.fire(
                                "Failed!",
                                "There was something wrong.",
                                "warning"
                            );
                        });
                }
            });
        },
        async downloadZip(selected) {
            // if
            this.loading = true;
            this.downloadingReport = true;

            // // -----------ZIP--------------- // // //
            const divsss = document.querySelectorAll(".reportsoaoutput");

            const zip = new JSZip();

            const urlToPromise = async (url) => {
                return new Promise(function (resolve, reject) {
                    JSZipUtils.getBinaryContent(url, function (err, data) {
                        if (err) {
                            reject(err);
                        } else {
                            resolve(data);
                            console.log(data);
                        }
                    });
                });
            };

            const generateZipFile = async (zip) => {
                const formData = new FormData();
                const base64 = await zip.generateAsync({ type: "base64" });
                const blob = await zip.generateAsync({ type: "blob" });
                await saveAs(
                    blob,
                    `report-${moment(selected[0].date_of_soa).format(
                        "MMDYY"
                    )}.zip`
                );

                const send_date = moment(selected[0].date_of_soa).format(
                    "MMMM D, YYYY"
                );
                axios
                    .post(
                        "api/sendZipEmail",
                        {
                            link: base64,
                            email: this.selectedEmail,
                            date: send_date,
                            arena: this.selectedGroup.name,
                            cc: this.emailcc,
                        },
                        formData,
                        {
                            headers: {
                                accept: "application/json",
                                "Accept-Language": "en-US,en;q=0.8",
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    )
                    .then(({ data }) => {
                        console.log(data);
                    });

                if (this.progressvalue === 100) {
                    setTimeout(async () => {
                        this.loading = false;
                        this.downloadingReport = false;

                        console.log("done");
                    }, 1000);
                }
            };
            // start benchmark
            const t = new Date();
            // some xml processing

            for (let i = 0; i < selected.length; i++) {
                console.log(
                    `Currently at ${i}, ${(new Date() - t) / 1000} secs`
                );

                this.progressvalue = Math.ceil(
                    (parseInt(i + 1) / parseInt(selected.length)) * 100
                );

                const canvas = await html2canvas(divsss[i], {
                    onclone: function (clonedDoc) {
                        const elems =
                            clonedDoc.getElementsByClassName("reportsoaoutput");
                        for (let i = 0; i < elems.length; i++) {
                            elems[i].style.display = "block";
                        }
                    },
                    type: "dataURL",
                    backgroundColor: "#ffffff",
                    scale: 0.9,
                });
                console.log(selected[i]);
                const link = document.createElement("a");
                link.download = `${
                    selected[i].arena_details
                        ? selected[i].arena_details.arena
                        : selected[i].arena_name
                }.png`;
                link.href = await canvas.toDataURL("image/png");
                const url = link.href;

                const folderName =
                    parseFloat(selected[i].for_total) < 0
                        ? "Replenishment"
                        : "Deposit";

                const arenaName =
                    selected[i].arena_name.indexOf("/") > -1
                        ? selected[i].arena_name.replace(/\//g, "-")
                        : selected[i].arena_name;
                const filename = `${folderName}/${arenaName}(${selected[i].refNo}).png`;

                await zip.file(filename, await urlToPromise(url), {
                    binary: true,
                }); //Create new zip file with filename and content
            }

            //Generate zip file
            await generateZipFile(zip);
        },
        async handleExpand(item) {
            console.log("Hello", item);
            const from = item;
            const to = moment(item, "YYYY-MM-DD")
                .add(1, "days")
                .format("YYYY-MM-DD");

            const areaCodes = this.groupHasArena.map((item) => item.area_code);

            this.areaCodesname = areaCodes;
            const { data } = await axios.post(`api/fetchSoaByOperatorGroup`, {
                areaCodes,
                from,
                to,
            });
            console.log(data);
            this.groupOperatorsArena = data;
        },
        async viewItemImport(item) {
            this.soaDialogItem = true;
            this.soaItem = item;
        },

        async sendingEmail(item) {
            this.loading = true;
            // const from = item
            // const to = moment(item, "YYYY-MM-DD LTS")
            //     .add(1, "days")
            //     .format("YYYY-MM-DD LTS");

            // const areaCodes = this.groupHasArena.map((item) => item.area_code);

            // this.areaCodesname = areaCodes;
            // const {data} = await axios.post(`api/fetchSoaByOperatorGroup`, {
            //      areaCodes,
            //      from,
            //      to
            //  })
            // this.groupOperatorsArena = data

            process.nextTick(() => {
                this.downloadZip(this.groupOperatorsArena);
            });
        },
    },
    created() {
        this.getallGroups();
        this.getAllArena();
        this.ManageCC();
        Fire.$on("AfterCreate", () => {
            this.getallGroups();
        });

        Fire.$on("GetallArena", () => {
            this.getAllArena();
        });
    },
};
</script>
<style scoped>
ul {
    list-style: none;
    height: 100px;
    overflow-x: hidden;
    overflow-y: scroll;
}

ul li:before {
    content: "✓";
}
</style>
