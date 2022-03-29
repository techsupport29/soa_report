<template lang="">
    <v-col class="col-md-6 d-flex justify-end align-center">
     
        <v-btn
            color="red lighten-1 text-white"
            class="mr-4"
            v-show="showClear"
            @click="clearDatabyDate"
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
                        >
                            <v-icon
                                light
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
                <v-list-item class="d-flex justify-center" >
                    <v-btn
                        :loading="downloadingReport"
                        :disabled="downloadingReport"
                        color="green lighten-1"
                        class="ma-2 white--text allbtn"
                        @click="multiDownloads"
                    >
                        <v-icon
                            light
                        >mdi-download</v-icon>
                        PNG
                    </v-btn>
                </v-list-item>
                <v-list-item class="d-flex justify-center">
                    <v-btn
                        :loading="downloadingReport"
                        :disabled="downloadingReport"
                        color="yellow darken-3"
                        class="ma-2 white--text allbtn"
                        @click="downloadZip"
                    >
                        <v-icon
                            light
                        >mdi-zip-box</v-icon>
                            Zip
                    </v-btn>
                </v-list-item>
                <hr color="green lighten-2" class="ma-2">
                <v-list-item class="d-flex justify-center">
                    <v-btn
                        :disabled="printReadyProgress < 100 ? true : false"
                        color="red accent-1"
                        class="ma-2 white--text allbtn"
                        @click="printSoa('report-soa_container')"
                    >
                        <v-icon
                            light
                        >mdi-printer</v-icon>
                            {{ printReadyProgress >= 100 ? 'PRINT' : `Print ready at ${printReadyProgress}%`}}
                    </v-btn>
                </v-list-item>
            </v-list>
        </v-menu>
        <loading-progress :loading="loading" :downloadingReport="downloadingReport" :progressvalue="progressvalue" />                         
    </v-col>
</template>
<script>
import html2canvas from "html2canvas";
import JSZip from "jszip";
import JSZipUtils from "jszip-utils";
import { saveAs } from "file-saver";
import moment from "moment";
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
        printReadyProgress: Number
    },
    data: () => ({
        loading: false,
        downloadingReport: false,
        progressvalue: 0,
        printSoa
    }),
    methods: {
        clearDatabyDate() {

            const from = this.dates[0];
            const to = moment(this.dates[1], "YYYY-MM-DD")
                .add(1, "days")
                .format("YYYY-MM-DD");

            const tab = this.tab
            console.log(`${tab} - ${from} - ${to}`)
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
                            console.log('delete',data)
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                        
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
        },
        async downloadZip() {
            console.log(this.tab)
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
                    console.log("zip generated");
                    await axios.put("api/arenaStatus", statusArenas);
                    // const c = this.arenaData.filter(
                    //     (arena) =>
                    //         !this.selected.find(
                    //             (select) => select.areaCode === arena.areaCode
                    //         )
                    // );

                    if (this.progressvalue === 100) {
                        setTimeout(async () => {
                            this.downloadingReport = false;
                            this.loading = false;

                            console.log("done");
                            // this.selected = [];
                          
                        }, 1000);
                    }

                    
                    // if(this.dates.length !== 0) {
                    //     await this.loadDateRange(this.tab)
                    // }else{
                        // this.tab === 'ongoing' ? this.soaLists() : this.importWithStatus();
                        await this.fetchLists()
                    // }

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
       
    }
}
</script>
<style lang="">
    
</style>