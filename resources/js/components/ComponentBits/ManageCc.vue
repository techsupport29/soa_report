<template>
    <v-dialog
        v-model="openCCdialog"
        max-width="500px"
        persistent
        overlay-color="grey darken-3"
      >
        <v-card >
        <v-form ref="form" class="mx-2" lazy-validation>
          <v-card-title>
            Manage CC in Email
          </v-card-title>
          <v-card-text>
             <p class="caption">
              <span class="font-weight-bold">CC: </span>
              <span v-if="selectedCc == ''" cclasslass="font-weight-italic"> <em> No CC email selected yet.. </em></span>
              <v-chip v-else small v-for="cc in selectedCc" :key="cc.id" class="ma-1 text-white" color="blue">{{cc.email_cc}}</v-chip>
            </p>
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
              color="red"
              text
              @click="closeCCdialog"
            >
              Close
            </v-btn>
          </v-card-actions>
        </v-form>
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
    openCCdialog: Boolean,
    selected: Array,
    receiverEmails: Array
  },
  data: () => ({

      loading: false,
      progressvalue: 0,
      selectedCc: [],
       items: [],
  }),
  methods: {
    closeCCdialog(){
        this.$emit('closeCCdialog', false);
    },
    async fetchEmailsCC(){
        const {data} = await axios.get('api/emails')
        this.selectedCc = data.filter(ec => ec.isUse !== 0)

        this.items = data

    },
    handleSelectedEmail(item){
     axios.get('api/isUsed/' + item.id).then((data)=> {
            console.log(data);
            this.fetchEmailsCC();
        });
    },

  },
};
</script>
<style lang="">
</style>