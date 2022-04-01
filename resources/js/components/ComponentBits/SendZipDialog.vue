<template>
    <v-dialog
        v-model="openSendZipDialog"
        max-width="500px"
        persistent
        overlay-color="grey darken-3"
      >
        <v-card >
        <v-form ref="form" class="mx-2" lazy-validation>
          <v-card-title>
            Sending Zip File
          </v-card-title>
          <v-card-text>
            <p class="caption">
              <span class="font-weight-bold">To: </span>
              <v-chip small v-for="receiverEmail in receiverEmails" :key="receiverEmail">{{receiverEmail}}</v-chip>
            </p>
             <p class="caption">
              <span class="font-weight-bold">CC: </span>
              <v-chip small v-for="cc in selectedCc" :key="cc.id">{{cc.email_cc}}</v-chip>
            </p>
            <v-text-field
                v-model="zipName"
                label="Enter Zip Name"
                :rules="nameRules"
                outlined
                dense
                required
          ></v-text-field>
           <v-combobox
              v-model="selectedCc"
              :items="items"
              item-value="email_cc"
              item-text="email_cc"
              label="Select CC"
              
              multiple
            >
              <template v-slot:item="{ item, on, attrs }">
                <v-list-item v-on="on" v-bind="attrs"  @click="handleSelectedEmail(item)">
                  <v-list-item-icon
                    >
                    <v-icon>{{
                      selectedCc.includes(item) ? 'mdi-checkbox-marked' : 'mdi-checkbox-blank-outline'
                    }}</v-icon>
                  </v-list-item-icon
                  >
                  <v-list-item-content>
                    <v-list-item-title
                      v-text="item.email_cc"
                      class="text-left"
                    ></v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </template>
              
            </v-combobox>
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
              color="red"
              text
              @click="zipDialogClose"
            >
              Close
            </v-btn>
          </v-card-actions>
        </v-form>
        </v-card>
        
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
    selected: Array,
    receiverEmails: Array,
    handleSendingEmail: Function
  },
  data: () => ({
      
      loading: false,
      progressvalue: 0,
      zipName: "",
      nameRules: [
        v => !!v || 'Zip Name is required'
      ],

      selectedCc: [],
       items: [],
   
  }),
  methods: {
    handleSelectedEmail(item){
      console.log(item)
    },
    zipDialogClose(){
          this.$emit('zipDialogClose', false)
    },
    async sendZipEmail(){

        if (this.$refs.form.validate()){
            let statusArenas = [];
            // this.loading = true
             this.handleSendingEmail()

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
                    const EmailCC =  this.selectedCc.map(selectedCc => {
                            return selectedCc.email_cc
                        });
                    const emails = uniq(flattenDeep(operatorsEmail))
                    axios.post('api/sendZipEmail', {
                            link: base64,
                            emails,
                            date: this.selected[0].date_of_soa,
                            operator: this.zipName,
                            cc: EmailCC
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



                    // if (this.progressvalue === 100) {
                    //     setTimeout(async () => {
                    //       this.loading = false
                    //     }, 1000);
                    // }
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
            //close modal
            this.$emit('zipDialogClose', false)
            this.loading = false
        }

    },

    async fetchEmailsCC(){
        const {data} = await axios.get('api/emails')
        this.selectedCc = data.filter(ec => ec.isUse !== 0)
        // console.log('hey',this.items)
        this.items = data
    },


  },
};
</script>
<style lang="">
</style>