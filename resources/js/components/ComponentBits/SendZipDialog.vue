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
                label="Zip Name"
                outlined
                dense
          ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-btn
              color="primary"
              text
              @click="openSendZipDialog = false"
            >
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
</template>
<script>

export default {
  name: "SendZipDialog",
  props: {
    openSendZipDialog: Boolean,
  },
  data: () => ({

  }),
  methods: {
      async openSendZipDialogBox(){

      }
  },
};
</script>
<style lang="">
</style>