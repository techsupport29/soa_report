<template lang="">
    <v-app>
      
        <h1 class="h2 font-weight-bold pa-8">CONFIGURE DETAILS</h1>
        <v-row dense >
            <v-col class="col-lg-6 sm-12">
                <position></position>
            </v-col>
            <v-col class="col-lg-6 sm-12">
                <bank-account></bank-account>
            </v-col>
        </v-row>
    </v-app>
</template>
<script>
import Position from './Position.vue'
import BankAccount from './BankAccount.vue'
import Signatory from './Signatory.vue'
export default {
    components: {
        Position,
        BankAccount,
        Signatory,
    }
}
</script>
<style lang="">
    
</style>