<template>
    <v-container>
        <v-data-table
            :headers="emailccHeaders"
            :items="emailcc"
            :search="search"
            class="elevation-1 ma-4">

            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title class="font-weight-bold">CARBON COPY IN EMAIL</v-toolbar-title>
                        <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                        <v-btn
                            color="success"
                            dark
                            class="mb-2"
                            @click="OpenModal()"
                        >
                        Add CC for Email
                        </v-btn>
                </v-toolbar>
                </template>
                    <template v-slot:[`item.emailAction`]="{ item }">

                            <v-tooltip bottom color="primary">
                                <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                        color="primary"
                                        class="mx-2"
                                        icon
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        @click="editModal(item)"
                                        >
                                        <i class="fas fa-edit"></i>
                                        </v-btn>
                                </template>
                            <span>Edit User Info</span>
                            </v-tooltip>
                            |
                            <v-tooltip bottom  color="red">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                    color="red"
                                    dark
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    class="mx-2"
                                    @click="deleteEmail(item.id)"
                                    >
                                    <i class="fa fa-trash"></i>
                                    </v-btn>
                                </template>
                            <span>Delete Email</span>
                            </v-tooltip>
                    </template>
        </v-data-table>

            <!-- Position Modal -->
        <div class="modal fade" id="OpenModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add  CC in Email</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update CC in Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateEmail() : createEmail()">
                    <div class="modal-body">
                            <v-text-field
                                prepend-inner-icon="mdi-email"
                                label="Enter Email Address"
                                placeholder="Add cc Email"
                                outlined
                                dense
                                :error-messages="errors"
                                v-model="ccform.email_cc"
                                :rules="[
                                    () => !!ccform.email_cc|| 'This field is required',

                                ]"
                                required
                        ></v-text-field>
                    </div>
                    <div class="modal-footer">
                        <v-btn type="button" color="error" elevation="2" data-dismiss="modal">Close</v-btn>
                        <v-btn v-show="editmode" type="submit" color="primary" elevation="2">Update</v-btn>
                        <v-btn v-show="!editmode" type="submit" color="success"  elevation="2">Create</v-btn>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </v-container>
</template>

<script>
    export default {
        name: 'position',
        data() {
            return {

                emailccHeaders : [

                    { text: 'Email', value: 'email_cc' },
                    { text: '', value: 'emailAction', sortable: false},
                ],
                editmode: false,
                length: '',
                search: '',
                emailcc:[],
                errors:[],
                ccform: new Form({
                    id:'',
                    email_cc: '',
                }),

            }
        },
        methods: {
            OpenModal(){
                $('#OpenModal').modal('show');
                this.ccform.reset();
                this.editmode = false;
            },
            createEmail(){
                this.ccform.post('api/SaveCCEmail').then((data)=> {
                      Fire.$emit('AfterCreate');
                      Toast.fire({
                        icon: 'success',
                        title: 'Eamil Added in successfully'
                        })
                        $('#OpenModal').modal('hide');
                }).catch((error) => {
                        this.errors = error.response.data.errors?.email_cc;
                })

            },
            updateEmail(){
                this.ccform.put('api/emails/'+this.ccform.id).then((data)=> {
                      Fire.$emit('AfterCreate');
                      Toast.fire({
                        icon: 'success',
                        title: 'email  Updated'
                        })
                         $('#OpenModal').modal('hide');
                }).catch((error) => {
                        this.errors = error.response.data.errors?.email_cc;
                });
            },
            editModal(data){
                this.editmode = true;
                this.ccform.reset();
                $('#OpenModal').modal('show');
                this.ccform.fill(data);
            },
            loadPosition(){
                axios.get('api/emails').then((data)=>{
                    this.emailcc = data.data;

                });
            },
            deleteEmail(id){
                  swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                         if (result.value) {
                              this.ccform.delete('api/emails/'+id).then((data)=> {
                                    swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                });
                         }
                    })

            },

        },
        created() {
            this.loadPosition();
           Fire.$on('AfterCreate',() => {
                this.loadPosition();
           });
        }
    }
</script>

