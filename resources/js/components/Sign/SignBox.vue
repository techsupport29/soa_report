<template lang="">
    <v-col>
        <div class="d-flex flex-column flex-md-row">
            <div class="d-flex mr-2">
                <p class="font-weight-medium text-md-caption text-lg-body-1">{{ titleKey }}:</p>
            </div>

            <div class="d-flex justify-center align-center">

            <div
                class="d-flex flex-column justify-center align-center flex-grow-1 px-4"
            >
                <span v-if="!check" class="px-2 font-weight-bold text-md-caption text-lg-body-1">{{
                    formatArrayToNameStr() || "No user assign"
                }}</span>
                <div v-else class="mt-2 mx-auto" style="width: 90%">
                    <v-combobox
                        v-model="model"
                        :items="userItems"
                        full-width
                        :hint="`Maximum of ${noOfSign} user${
                            noOfSign > 1 ? 's' : ''
                        }`"
                        label="Add some tags"
                        multiple
                        persistent-hint
                        small-chips
                        solo
                        deletable-chips
                        item-text="name"
                        item-value="name"
                        return-object
                        dense
                        value="name"
                        @blur="saveSignatory(assignedSign)"
                    >
                    </v-combobox>
                </div>

                <hr class="signatory-line" />
                <span class="px-2 font-weight-bold text-md-caption text-lg-body-1">{{
                    formatArrayToPositionStr()
                }}</span>
            </div>
            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        small
                        icon
                        dark
                        v-bind="attrs"
                        v-on="on"
                        @click="
                            !check
                                ? editSignatory(assignedSign, noOfSign)
                                : saveSignatory(assignedSign)
                        "
                    >
                        <v-icon small :color="!check ? 'primary' : 'success'">{{!check ? 'mdi-pencil' : 'mdi-content-save' }}</v-icon>
        
                    </v-btn>
                </template>
                <span>{{!check ? 'Update signatory' : 'Save signatory' }}</span>
            </v-tooltip>
            </div>
        </div>
    </v-col>
</template>
<script>
import { uniq } from "lodash";
export default {
    name: "sign-box",
    props: {
        assignedSign: String,
        noOfSign: Number,
        check: Boolean,
        titleKey: String,
    },
    data() {
        return {
            userItems: [],
            signedUsers: [],
            model: [],
            checking: this.check,
        };
    },
    methods: {
        async availableSignatory(signatory) {
            const users = await axios.get(
                `api/getAvailableSignatory/${signatory}`
            );
            this.userItems = users.data;
        },

        async usersSignatory(signatory) {
            const signedUsers = await axios.get(
                `api/getUsersSignatory/${signatory}`
            );
            this.signedUsers = signedUsers.data;
            this.model = signedUsers.data;
        },

        async editSignatory(signatory, number) {
            this.checking = true;
            this.noOfSign = number;
           
            this.$emit(this.assignedSign, {
                [`${this.assignedSign}`]:this.checking
            });
            await this.availableSignatory(signatory);
        },
        async saveSignatory(assigned) {
           
            this.checking = false;
            const signatoryChecked = await axios.put("api/updateSignatory", {
                assigned,
                users: this.model,
                noOfSign: this.noOfSign,
            });

             this.$emit(this.assignedSign, {
                [`${this.assignedSign}`]:false
            });
          
            await this.usersSignatory(assigned);
            await this.availableSignatory(assigned);
        },

        formatArrayToNameStr() {
            const formatSignName = this.signedUsers.map((sign) => sign["name"]);

            return formatSignName.length > 1
                ? formatSignName.join("/")
                : formatSignName.toString();
        },

        formatArrayToPositionStr() {
            const formatSignPosition = this.signedUsers.map(
                (sign) => sign.position_details.position
            );
            const uniqArrPosition = uniq(formatSignPosition);

            return uniqArrPosition.length > 1
                ? uniqArrPosition.join("/")
                : uniqArrPosition.toString();
        },
    },
    watch: {
        model(val) {
            if (val.length > this.noOfSign) {
                this.$nextTick(() => this.model.pop());
            }
        },
    },
    created() {
        this.usersSignatory(this.assignedSign);
    },
};
</script>
<style lang=""></style>
