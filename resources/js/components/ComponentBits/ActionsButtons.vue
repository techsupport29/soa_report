<template lang="">
    <v-col class="col-md-6 d-flex justify-end align-center">

        <v-btn
            color="red lighten-1 text-white"
            class="mr-4"
            v-show="showClear"
            @click="clearDatabyDate"
            rounded
            outlined
        >
            <v-icon light>mdi-backspace-outline</v-icon>
            &nbsp;Clear {{tab}}
        </v-btn>
        <v-menu
            class="flex-end"
            origin="center center"
            transition="slide-x-transition"
            v-if="selected.length > 0"
            rounded="rounded"
            :loading="downloadingReport"
            :disabled="downloadingReport"
        >
            <template
                v-slot:activator="{on: menu,attrs}"
            >
                <v-tooltip bottom>
                    <template v-slot:activator="{ on: tooltip }">
                        <v-btn
                            color="primary lighten-1"
                            v-bind="attrs"
                            v-on="{ ...tooltip, ...menu }"
                            :loading="downloadingReport"
                            :disabled="downloadingReport"
                            rounded
                            outlined
                        >
                            <v-icon
                                light
                                 class="ma-2  all"
                            >mdi-view-grid-outline</v-icon>
                                Menu
                            <template
                                v-slot:loader
                            >
                                <span>Downloading...</span>
                            </template>
                        </v-btn>
                    </template>
                    <span>Action buttons menu</span>
                </v-tooltip>
            </template>
            <v-list >
            <v-tooltip left>
                <template v-slot:activator="{ on, attrs }">
                    <v-list-item class="d-flex justify-center" >
                        <v-btn
                            :loading="downloadingReport"
                            :disabled="downloadingReport"
                            color="cyan accent-3"
                            class="ma-2 white--text allbtn"
                            @click="OpenManageCCdialog"
                            elevation="2"
                            rounded
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon
                                light
                                class="ma-2 white--text all"
                            >mdi-arrange-send-to-back</v-icon>
                            Manage CC
                        </v-btn>
                    </v-list-item>
                </template>
            <span><v-icon class="text-white">mdi-chat-alert</v-icon>  Manage CC in Email </span>
            </v-tooltip>

            <hr color="green lighten-2" class="ma-2">

            <v-tooltip left>
                <template v-slot:activator="{ on, attrs }">
                <v-list-item class="d-flex justify-center" >
                    <v-btn
                        :loading="downloadingReport"
                        :disabled="downloadingReport"
                        color="green lighten-1"
                        class="ma-2 white--text allbtn"
                        @click="multiDownloads"
                        elevation="2"
                        rounded
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon
                            light
                             class="ma-2 white--text all"
                        >mdi-download</v-icon>
                        PNG
                    </v-btn>
                </v-list-item>
                </template>
                <span><v-icon class="text-white">mdi-chat-alert</v-icon>  Multiple download of Image/PNG </span>
            </v-tooltip>

            <v-tooltip left>
                <template v-slot:activator="{ on, attrs }">
                    <v-list-item class="d-flex justify-center">
                        <v-btn
                            :loading="downloadingReport"
                            :disabled="downloadingReport"
                            color="yellow darken-3"
                            class="ma-2 white--text allbtn"
                            @click="downloadZip"
                            elevation="2"
                            rounded
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon
                                light
                                class="ma-2 white--text all"
                            >mdi-zip-box</v-icon>
                                Zip
                        </v-btn>
                    </v-list-item>
                </template>
                <span><v-icon class="text-white">mdi-chat-alert</v-icon>  Compress selected image into Zip File  </span>
            </v-tooltip>

            <v-tooltip left>
                <template v-slot:activator="{ on, attrs }">
                <v-list-item class="d-flex justify-center" >
                    <v-btn
                        color="#FF1313"
                        class="ma-2 white--text allbtn"
                        @click="clearDatabyDate"
                        elevation="2"
                        rounded
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon
                            light
                             class="ma-2 white--text all"
                        >mdi-delete</v-icon>
                        Delete
                    </v-btn>
                </v-list-item>
                </template>
                <span><v-icon class="text-white">mdi-chat-alert</v-icon>  Multiple delete of selected reports </span>
            </v-tooltip>

                <!-- email send via zip or single send  -->
            <hr color="green lighten-2" class="ma-2">

            <v-tooltip left>
                <template v-slot:activator="{ on, attrs }">
                <v-list-item class="d-flex justify-center">
                    <v-btn
                        :loading="downloadingReport"
                        :disabled="downloadingReport"
                        color="blue darken-3"
                        class="ma-2 white--text allbtn"
                        @click="multisendEmail"
                        elevation="2"
                        rounded
                        v-bind="attrs"
                        v-on="on"
                    >
                          <v-icon
                                light
                                class="ma-2 white--text all"
                        >mdi-email-multiple</v-icon>
                                Send Selected Email
                    </v-btn>
                </v-list-item>
                </template>
                <span><v-icon class="text-white">mdi-chat-alert</v-icon>  Send selected Email to specific operators  </span>
            </v-tooltip>


            <hr color="green lighten-2" class="ma-2">
                <!-- multi print -->
            <v-tooltip left>
                <template v-slot:activator="{ on, attrs }">
                <v-list-item class="d-flex justify-center">
                    <v-btn
                        :disabled="printReadyProgress < 100 ? true : false"
                        color="red accent-1"
                        class="ma-2 white--text allbtn"
                        @click="printSoa('report-soa_container')"
                        elevation="2"
                        rounded
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon light class="ma-2 white--text all">mdi-printer</v-icon>
                            {{ printReadyProgress >= 100 ? 'PRINT' : `Print ready at ${printReadyProgress}%`}}
                    </v-btn>
                </v-list-item>
                </template>
                <span><v-icon class="text-white">mdi-chat-alert</v-icon>  Print Selected/multiple image </span>
            </v-tooltip>
            </v-list>
        </v-menu>
        <!-- Dialog for Zip -->
        <zip-dialog  :openSendZipDialog="openSendZipDialog"  :selected="selected" @zipDialogClose="handleZipDialogClose" :receiverEmails="receiverEmails" :handleSendingEmail="handleSendingEmail" ref="dialogZip"></zip-dialog>

        <!-- manageCC -->
        <manage-cc :openCCdialog="openCCdialog" :selected="selected" @closeCCdialog="handlecloseCCdialog" :receiverEmails="receiverEmails" ref="manageCC"></manage-cc>
        <loading-progress :loading="loading" :downloadingReport="downloadingReport" :progressvalue="progressvalue" />
    </v-col>
</template>
<script>
import html2canvas from "html2canvas";
import JSZip from "jszip";
import JSZipUtils from "jszip-utils";
import { saveAs } from "file-saver";
import moment from "moment";
import { flattenDeep, uniq } from 'lodash';
import ZipDialog from "./SendZipDialog.vue";
import ManageCc from "./ManageCc.vue"
import {
    printSoa,
} from "../../methods";


export default {
    name: 'action-buttons',
    props:{
        selected: Array,
        showClear: Boolean,
        dates: Array,
        tab: String,
        arenaData: Array,
        soaLists: Function,
        importWithStatus: Function,
        fetchLists: Function,
        loadDateRange: Function,
        handleEmptySelect: Function,
        printReadyProgress: Number,
        handleSendingEmail: Function
    },
    data: () => ({
        loading: false,
        downloadingReport: false,
        openSendZipDialog: false,
        openCCdialog: false,
        progressvalue: 0,
        printSoa,
        receiverEmails: [],
        selectedCc:[]
    }),
    components:{
        ZipDialog,
        ManageCc
    },
    methods: {
        handleZipDialogClose(item){
            console.log(item);
            this.openSendZipDialog = item;
        },
        handlecloseCCdialog(item){
            console.log(item);
            this.openCCdialog = item;
        },
        handleZipDialogOpen(){
                this.openSendZipDialog = true
                this.$refs.dialogZip.fetchEmailsCC()
                const operatorsEmail = this.selected.map(selected => {
                        return selected.arena_details.email_details.map(email => email.email)
                });

                this.receiverEmails = uniq(flattenDeep(operatorsEmail))
        },
        OpenManageCCdialog(){
              this.openCCdialog = true
                this.$refs.manageCC.fetchEmailsCC()
                const operatorsEmail = this.selected.map(selected => {
                        return selected.arena_details.email_details.map(email => email.email)
                });

                this.receiverEmails = uniq(flattenDeep(operatorsEmail))
        },
        clearDatabyDate() {
            const from = this.dates[0];
            const to = moment(this.dates[1], "YYYY-MM-DD")
                .add(1, "days")
                .format("YYYY-MM-DD");

            const tab = this.tab
            // console.log(`${tab} - ${from} - ${to}`)
            const fromSwal = moment(this.dates[0]).format('LL');
            const toSwal = moment(this.dates[1]).format('LL');
            swal.fire({
                title: "Are you sure?",
                text: `The SOA that range from ${fromSwal} to  ${toSwal} will be remove!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, remove it!",
            }).then( (result) => {
                if (result.isConfirmed) {
                    axios
                        .post("api/clearfilterbydate", {
                            from: from,
                            to: to,
                            tab: tab
                        })
                        .then((data) => {
                            // console.log('delete',data)
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success",
                            ).then(function(isConfirm) {
                                if (isConfirm) {
                                    location.reload();
                                } else {
                                    //if no clicked => do something else
                                }
                                });

                            // this.tab === 'ongoing' ? this.soaLists() : this.importWithStatus();
                            if(this.dates.length !== 0) {
                                this.loadDateRange(this.tab)
                            }else{
                                this.tab === 'ongoing' ? this.soaLists() : this.importWithStatus();
                            }


                        })
                        .catch((error) => {});
                }
            });
        },
        async multiDownloads() {

            let statusArenas = [];
            this.downloadingReport = true;
            this.loading = true;
            const divsss = document.querySelectorAll(".reportsoaoutput");
            const start = new Date();

            for (let i = 0; i < this.selected.length; i++) {
                this.progressvalue = Math.ceil(
                    (parseInt(i + 1) / parseInt(this.selected.length)) * 100
                );
                // console.log(
                //     `Currently at ${i}, ${(new Date() - start) / 1000} s`
                // );
                statusArenas.push({
                    codeEvent: this.selected[i].codeEvent,
                    status: "done",
                });

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

                const link = document.createElement("a");
                link.download = `${this.selected[i].arena_name}(${this.selected[i].refNo}).png`;
                link.href = canvas.toDataURL("image/png");
                document.body.appendChild(link);
                link.click();

                await setTimeout(() => {
                    document.body.removeChild(link); // On modern browsers you can use `tempLink.remove();`
                }, 500);

                if (this.selected.length - 1 === i) {
                    await axios.put("api/arenaStatus", statusArenas);
                    const c = this.arenaData.filter(
                        (arena) =>
                            !this.selected.find(
                                (select) => select.areaCode === arena.areaCode
                            )
                    );

                    setTimeout(async () => {
                        this.downloadingReport = false;
                        this.loading = false;
                         this.handleEmptySelect()
                    }, 1000);

                                await this.fetchLists()

                }
            }

            const end = new Date();
            // console.log("Without promise.all ", (end - start) / 1000, " secs");
        },
        async downloadZip() {
            // console.log(this.tab)
            let statusArenas = [];
            this.downloadingReport = true;
            this.loading = true;

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

                    const blob = await zip.generateAsync({ type: "blob" });
                    await saveAs(
                        blob,
                        `report-${moment(this.selected[0].date_closed).format(
                            "MMDYY"
                        )}.zip`
                    );


                    await axios.put("api/arenaStatus", statusArenas);


                    if (this.progressvalue === 100) {
                        setTimeout(async () => {
                            this.downloadingReport = false;
                            this.loading = false;

                            console.log("done");

                        }, 1000);
                    }


                        await this.fetchLists()

                    this.handleEmptySelect()
            };
            // start benchmark
            const t = new Date();
            // some xml processing

            for (let i = 0; i < this.selected.length; i++) {
                statusArenas.push({
                    codeEvent: this.selected[i].codeEvent,
                    status: "done",
                });

                console.log(
                    `Currently at ${i}, ${(new Date() - t) / 1000} secs`
                );

                this.progressvalue = Math.ceil(
                    (parseInt(i + 1) / parseInt(this.selected.length)) * 100
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

                const link = document.createElement("a");
                link.download = `${this.selected[i].arena_details.arena}.png`;
                link.href = await canvas.toDataURL("image/png");
                const url = link.href;

                const folderName =
                    parseFloat(this.selected[i].for_total) < 0 ? "Replenishment" : "Deposit";
                console.log(this.selected[i])
                const arenaName =
                    (this.selected[i].arena_name.indexOf("/")) > -1
                        ? this.selected[i].arena_name.replace(/\//g, "-")
                        : this.selected[i].arena_name;
                const filename = `${folderName}/${arenaName}(${this.selected[i].refNo}).png`;

                await zip.file(filename, await urlToPromise(url), {
                    binary: true,
                }); //Create new zip file with filename and content
            }

            //Generate zip file
            await generateZipFile(zip);
        },



        async multisendEmail (){
            let statusArenas = [];
            this.handleSendingEmail()
            this.loading = true;
            const divsss = document.querySelectorAll(".reportsoaoutput");
            const start = new Date();
            console.log('multiprint',this.selected);


            for (let i = 0; i < this.selected.length; i++) {
                this.progressvalue = Math.ceil(
                    (parseInt(i + 1) / parseInt(this.selected.length)) * 100
                );
                console.log(
                    `Currently at ${i}, ${(new Date() - start) / 1000} s`
                );
                statusArenas.push({
                    codeEvent: this.selected[i].codeEvent,
                    status: "done",
                });

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

                const operatorsEmail = this.selected[i].arena_details.email_details.map(email => email.email);

                const link = document.createElement("a");
                link.download = `${this.selected[i].arena_name}(${this.selected[i].refNo}).png`;
                link.href = canvas.toDataURL("image/png");
                // console.log('image',this.selected[i]);
                const {data} = await axios.get('api/emails');
                this.selectedCc = data.filter(ec => ec.isUse !== 0);
                const EmailCC =  this.selectedCc.map(selectedCc => {
                        return selectedCc.email_cc
                    });

                axios.post('api/sendMultiEmail',{
                    link: link.href,
                    cc :   EmailCC,
                    emails: uniq(flattenDeep(operatorsEmail)),
                    date: this.selected[i].date_of_soa,
                    arena_name: this.selected[i].arena_details.arena,
                    group: this.selected[i].group
                    }).then(({data}) => {
                        console.log(data);
                });
                document.body.appendChild(link);
                link.click();

                await setTimeout(() => {
                    document.body.removeChild(link); // On modern browsers you can use `tempLink.remove();`
                }, 500);

                if (this.selected.length - 1 === i) {
                    await axios.put("api/arenaStatus", statusArenas);
                    const c = this.arenaData.filter(
                        (arena) =>
                            !this.selected.find(
                                (select) => select.areaCode === arena.areaCode
                            )
                    );

                    setTimeout(async () => {
                        this.downloadingReport = false;
                        this.loading = false;
                        this.handleEmptySelect();
                        Toast.fire({
                        icon: "success",
                        title: "Email Sending in successfully",
                        });
                    }, 1000);
                    //   if(this.dates.length !== 0) {
                    //             await this.loadDateRange(this.tab)
                    //         }else{
                                // this.tab === 'ongoing' ? this.soaLists() : this.importWithStatus();
                    await this.fetchLists()
                            // }
                }
            }

            const end = new Date();
            console.log("Without promise.all ", (end - start) / 1000, " secs");
        }

    }
}
</script>
<style lang="">

</style>