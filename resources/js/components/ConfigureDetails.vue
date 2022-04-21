<template lang="">
    <v-app>
        <h1 class="h2 font-weight-bold pa-8 custom-text">Configuration Details</h1>
        <v-row dense >
            <v-col class="col-lg-6 sm-12">
                <position></position>
            </v-col>
            <v-col class="col-lg-6 sm-12">
                <bank-account></bank-account>
            </v-col>
             
        </v-row>
        <v-row>
            <v-col class="col-lg-12" style="margin-top:-100px">
                <email-cc></email-cc>
            </v-col>              
        </v-row>
    </v-app>
</template>
<script>
import Position from './Position.vue'
import BankAccount from './BankAccount.vue'
import Signatory from './Signatory.vue'
import EmailCc from './EmailCC.vue'
export default {
    components: {
        EmailCc,
        Position,
        BankAccount,
        Signatory,
    }
}
</script>
<style scoped>
.custom-text{
    color:#8DA90B;
}
</style>