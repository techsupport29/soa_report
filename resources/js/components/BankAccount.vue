<template>
    <v-container>
        <v-row class="is-blurred">
            <v-col class="col-md-12">

                    <v-data-table
                        :headers="headers"
                        :items="account"
                        :items-per-page="5"
                        :search="search"
                        class="elevation-1 ma-4 custom-tbl"
                    >
                        <template  style="font-size:16px;"  v-for="header in headers" v-slot:[`header.${header.value}`]="{ header }">
                            <v-icon medium color="#8DA90B">{{ header.icon }}</v-icon>
                            <span style="color:#8DA90B"> {{ header.text }} </span>
                        </template>
                        <template v-slot:top>
                            <v-toolbar flat style="border-radius:20px">
                                <v-toolbar-title class="font-weight-bold custom-color"
                                    >COMPANY BANKS</v-toolbar-title
                                >
                               
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="#8DA90B"
                                    dark
                                    class="mb-2"
                                    @click="openModal"
                                >
                                    <i class="fas fa-landmark circle-icon"></i>
                                    &nbsp; Add Bank Information
                                </v-btn>
                            </v-toolbar>
                           
                        </template>

                        <template v-slot:[`item.actions`]="{ item }">
                            <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="white"
                                        class="mx-2 edit custom-btn"
                                        icon
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        @click="editModal(item)"
                                    >
                                        UPDATE
                                    </v-btn>
                                </template>
                                <span>Edit User Info</span>
                            </v-tooltip>
                            <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="white"
                                        class="delete custom-btn"
                                        dark
                                        icon
                                        v-bind="attrs"
                                        v-on="on"
                                        @click="deleteAccount(item.id)"
                                    >
                                        DELETE
                                    </v-btn>
                                </template>
                                <span>Delete info User</span>
                            </v-tooltip>
                        </template>
                    </v-data-table>
             
            </v-col>
        </v-row>

        <!-- Modal -->
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
                <div class="modal-content custom-tbl">
                    <div class="modal-header">
                        <h5
                            class="modal-title custom-color"
                            v-show="!editmode"
                            id="addNewLabel"
                        >
                            New Bank Information
                        </h5>
                        <h5
                            class="modal-title custom-color"
                            v-show="editmode"
                            id="addNewLabel"
                        >
                            Update Bank Information
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
                        @submit.prevent="editmode ? updateBank() : createBank()"
                    >
                        <div class="modal-body">
                            <v-text-field
                                color="#8DA90B"
                                label="Account Name"
                                placeholder="john doe"
                                outlined
                                dense
                                v-model="form.account_name"
                                :rules="[
                                    () =>
                                        !!form.account_name ||
                                        'This field is required',
                                ]"
                                required
                                prepend-inner-icon="mdi-shield-account"
                            ></v-text-field>
                            <v-text-field
                                color="#8DA90B"
                                label="Bank Name"
                                placeholder="BDO"
                                outlined
                                dense
                                v-model="form.bank_name"
                                :rules="[
                                    () =>
                                        !!form.bank_name ||
                                        'This field is required',
                                ]"
                                required
                                prepend-inner-icon="mdi-bank"
                            ></v-text-field>
                            <v-text-field
                                color="#8DA90B"
                                label="Bank Number"
                                placeholder="0123-456-789"
                                outlined
                                dense
                                v-model="form.bank_number"
                                :rules="[
                                    () =>
                                        !!form.bank_number ||
                                        'This field is required',
                                ]"
                                required
                                prepend-inner-icon="mdi-ticket-confirmation"
                            ></v-text-field>
                        </div>
                        <div class="modal-footer">
                            <v-btn
                                type="button"
                                color="error"
                                elevation="2"
                                data-dismiss="modal"
                                >Close</v-btn
                            >
                            <v-btn
                                v-show="editmode"
                                type="submit"
                                class="add"
                                color="#8DA90B"
                                elevation="2"
                                >Update</v-btn
                            >
                            <v-btn
                                v-show="!editmode"
                                type="submit"
                                class="add"
                                color="#8DA90B"
                                elevation="2"
                                >Add Bank</v-btn
                            >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </v-container>
</template>

<script>
export default {
    name: "bank-account",
    data() {
        return {
            headers: [
                { text: "Account Name", value: "account_name" , icon: 'mdi-account-details' ,sortable: false },
                { text: "Bank Name", value: "bank_name" , icon: 'mdi-bank-minus' ,sortable: false },
                { text: "Bank Account", value: "bank_number" , icon: 'mdi-bank' ,sortable: false },
                { text: "", value: "actions", sortable: false },
            ],
            editmode: false,
            account: [],
            length: "",
            arena_id: {},
            search: "",
            form: new Form({
                id: "",
                isAdmin: 1,
                account_name: "",
                bank_name: "",
                bank_number: "",
            }),
        };
    },
    methods: {
        openModal() {
            this.editmode = false;
            this.form.reset();
            $("#addNew").modal("show");
        },
        editModal(data) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(data);
            this.form.arenas_id = accounts.arenas_id;
            // console.log(accounts);
        },
        loadDetails() {
            axios.get("api/Companybanks").then(({ data }) => {
                (this.account = data), console.log(data);
            });
        },
        loadArenaId() {
            axios.get("api/arenaId").then(({ data }) => (this.arena_id = data));
        },
        createBank() {
            this.$Progress.start();
            this.form
                .post("api/bankaccount")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");
                    Toast.fire({
                        icon: "success",
                        title: "Company account added  successfully",
                    });
                    this.$Progress.finish();
                })
                .catch(() => {});
        },
        updateBank() {
            this.$Progress.start();

            this.form
                .put("api/bankaccount/" + this.form.id)
                .then(() => {
                    $("#addNew").modal("hide");
                    Toast.fire({
                        icon: "success",
                        title: "Company account updated  successfully",
                    });
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        deleteAccount(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#8DA90B",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    this.form
                        .delete("api/bankaccount/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                            Fire.$emit("AfterCreate");
                        })
                        .catch(() => {
                            swal.fire(
                                "Failed!",
                                "There was something wrong.",
                                "warning"
                            );
                        });
                }
            });
        },
    },
    created() {
        this.loadDetails();
        this.loadArenaId();
        Fire.$on("AfterCreate", () => {
            this.loadDetails();
        });
    },
};
</script>
<style scoped>
    .custom-color{
    color:#8DA90B;
    }

    .add{
        background-color: #8DA90B;
        color:white;
    }

    .edit{
        background-color: #f0bb00;
    }

    .delete{
        background-color: red;
    }

    .custom-btn{
        width:100px;
        border-radius: 20px;
    }

    .custom-tbl{
        border: 2px solid #8DA90B;
        border-radius: 20px;
    }

</style>