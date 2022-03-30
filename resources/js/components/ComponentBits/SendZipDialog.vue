<template>
    <v-dialog
        v-model="openSendZipDialog"
        max-width="500px"
      >
        <v-card>
          <v-card-title>
            Dialog 2
          </v-card-title>
          <v-card-text>
            <v-text-field
                v-model="zipName"
                label="Zip Name"
                outlined
                dense
          ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              @click="sendZipEmail"
              
            >
              Send
            </v-btn>
            <v-btn
              color="primary"
              text
              @click="zipDialogClose"
            >
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
        <loading-progress :loading="loading" :progressvalue="progressvalue" />
      </v-dialog>
</template>
<script>
import html2canvas from "html2canvas";
import JSZip from "jszip";
import JSZipUtils from "jszip-utils";
import { flattenDeep, uniq } from 'lodash';


export default {
  name: "zip-dialog",
  props: {
    openSendZipDialog: Boolean,
    selected: Array
  },
  data: () => ({
      loading: false,
      progressvalue: 0,
      zipName: ""
  }),
  methods: {
      zipDialogClose(){
          this.$emit('zipDialogClose', false)
      },
         async sendZipEmail(){
            let statusArenas = [];
    
            this.loading = true

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
                    // const blob = await zip.generateAsync({ type: "blob" });
                    const base64 = await zip.generateAsync({ type: "base64" });

                    const operatorsEmail = this.selected.map(selected => {
                        return selected.arena_details.email_details.map(email => email.email)
                    })


                    axios.post('api/sendZipEmail', {
                            link: base64,
                            emails: uniq(flattenDeep(operatorsEmail)),
                            date: this.selected[0].date_of_soa,
                            operator: this.zipName
                        },
                        formData,
                        {
                        headers: {
                            'accept': 'application/json',
                            'Accept-Language': 'en-US,en;q=0.8',
                            "Content-Type": "multipart/form-data"
                        }
                        }).then(({data}) => {
                            console.log(data);
                    });



                    if (this.progressvalue === 100) {
                        setTimeout(async () => {
                          this.loading = false
                        }, 1000);
                    }
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
  },
};
</script>
<style lang="">
</style>