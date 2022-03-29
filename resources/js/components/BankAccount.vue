<template>
    <v-container>
        <v-row class="is-blurred">
            <v-col class="col-md-12">

                    <v-data-table
                        :headers="headers"
                        :items="account"
                        :items-per-page="5"
                        :search="search"
                         class="elevation-1 ma-4"
                    >
                        <template v-slot:top>
                            <v-toolbar flat>
                                <v-toolbar-title class="font-weight-bold"
                                    >COMPANY BANKS</v-toolbar-title
                                >
                                <v-divider
                                    class="mx-4"
                                    inset
                                    vertical
                                ></v-divider>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="success"
                                    elevation="2"
                                    @click="openModal"
                                >
                                    Add Bank Information
                                    <i class="fas fa-plus fa-fw"></i>
                                </v-btn>
                            </v-toolbar>
                            <v-row>
                                <v-spacer></v-spacer>
                                <v-col class="pr-5">
                                    <v-text-field
                                        v-model="search"
                                        append-icon="mdi-magnify"
                                        label="Search"
                                        color="primary darken-2"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </template>

                        <template v-slot:[`item.actions`]="{ item }">
                            <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="primary"
                                        class="mx-2"
                                        icon
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        @click="editModal"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </v-btn>
                                </template>
                                <span>Edit User Info</span>
                            </v-tooltip>
                            |
                            <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="red"
                                        dark
                                        icon
                                        v-bind="attrs"
                                        v-on="on"
                                        class="mx-2"
                                        @click="deleteAccount(item.id)"
                                    >
                                        <i class="fa fa-trash"></i>
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
                <div class="modal-content">
                    <div class="modal-header">
                        <h5
                            class="modal-title"
                            v-show="!editmode"
                            id="addNewLabel"
                        >
                            New Bank Information
                        </h5>
                        <h5
                            class="modal-title"
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
    </v-container>
</template>

<script>
export default {
    name: "bank-account",
    data() {
        return {
            headers: [
                { text: "Account Name", value: "account_name" ,sortable: false },
                { text: "Bank Name", value: "bank_name" ,sortable: false },
                { text: "Bank Account", value: "bank_number" ,sortable: false },
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
        editModal() {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            // this.form.fill(accounts);
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
                confirmButtonColor: "#3085d6",
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
