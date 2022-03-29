
<template>
    <div class="container">
        <div class="row justify-content-center">
                <div
                class="modal fade"
                id="addNew"
                data-backdrop="static"
                tabindex="-1"
                role="dialog"
                aria-labelledby="addNewLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5
                                class="modal-title"
                                v-show="!editmode"
                                id="addNewLabel"
                            >
                               Add New Arena
                            </h5>

                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form
                            @submit.prevent="
                                editmode ? updateArena() : createArena()
                            "
                        >
                    
                            <div class="modal-body">
                                <v-text-field
                                    label="Arena Name"
                                    outlined
                                    v-model="arenaNames"
                                    :rules="[
                                        () =>
                                            !!arenaNames ||
                                            'This field is required',
                                    ]"
                                    :error-messages="errors.areaCode"
                                ></v-text-field>

                                <v-text-field
                                    label="Arena Address"
                                    outlined
                                    v-model="address"
                                    :rules="[
                                        () =>
                                            !!address ||
                                            'This field is required',
                                    ]"
                                ></v-text-field>

                                <v-text-field
                                    label="Operator Name"
                                    placeholder="john Doe"
                                    outlined
                                    v-model="operator"
                                    :rules="[
                                        () =>
                                            !!operator ||
                                            'This field is required',
                                    ]"
                                ></v-text-field>

                                <v-combobox
                                    v-model="contactNos"
                                    :items="contacts"
                                    label="Contact Number"
                                    hint="Must be 11 digits"
                                    multiple
                                    outlined
                                    deletable-chips
                                    chips
                                    item-text="contact_number"   
                                >
                                </v-combobox>

                                <v-combobox
                                    v-model="emailsArr"
                                    :items="emails"
                                    label="Email Address"
                                    hint="Maximum of 5 Emails"
                                    multiple
                                    chips
                                    outlined
                                    deletable-chips
                                    item-text="email"
                                    
                                >
                                
                                </v-combobox>
                            </div>
                            <div class="modal-footer">
                                
                                <v-btn
                                    v-show="editmode"
                                    type="submit"
                                    color="primary"
                                    elevation="2"
                                    >Update</v-btn
                                >
                                <v-btn
                                    v-show="!editmode"
                                    type="submit"
                                    color="success"
                                    elevation="2"
                                    >Create</v-btn
                                >
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
         props: {
            arenaNames: String,
            soaLists: Function
            },
        data() {
            return {
                select: [],
                emailRules: [],
                errorMessages: "",
                formHasErrors: false,
                editmode: false,
                arena: [],
                emails: [],
                contacts: [],
                arenalogs:[],
                search: "",
                searchlogs:'',
                searchbank: "",
                input: "",
                loading: false,
                arenaLoading: false,
                show: false,
                bankDetails: [],
                // form: new Form({
                //     area_code: "",
                //     id: "",
                //     arena:"",
                //     address: "",
                //     operator: "",
                //     contact_number: [],
                //     email: []
                // }),
                Bankform: new Form({
                    id: "",
                    account_name: "",
                    arenas_id: "",
                    bank_name: "",
                    bank_number: "",
                }),
                 area_code: "",
                id: "",
                arena:"",
                address: "",
                operator: "",
                contactNumbers: [],
                contactNos: [], // to removed
                emailsArr: [],
                emailList: [],
                arenaList: [],
                bankList: [],
                fileUpload: null,
                isExcel: false,
                errors: {
                    areaCode: ''
                    }
                };
        },
        methods: {
           
            createArena() {
           
            this.$Progress.start();
                
                axios.post("api/arena" ,{ 
                    arena : this.arenaNames,
                    area_code:  this.arenaNames.split(" ")[0],
                    address: this.address,
                    operator: this.operator,
                    contact_number:!this.contactNos ? null : this.contactNos.length > 1 ? this.contactNos.join(" / "): this.contactNos.toString(),
                    email: !this.emailsArr ? null : this.emailsArr.length > 1 ? this.emailsArr.join(" / ") : this.emailsArr.toString()
                    })
                .then(() => {
                   
                    $("#addNew").modal("hide");
                    this.address = '',
                    this.operator = '',
                    this.contactNos = ''
                   this.emailsArr = ''
                    Toast.fire({
                        icon: "success",
                        title: "Successfully Created",
                    });

                    this.$Progress.finish();
                    // window.location.reload();
                    this.soaLists();
                })
                .catch((e) => {
                    this.errors.areaCode = (e.response.data.message.includes('Integrity constraint') || e.response.status === 500) ? 'Area Code/Arena already exist.' : ''
              });
            },
        },
        created() {
       
        }
    }
</script>





