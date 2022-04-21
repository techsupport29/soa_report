<template >
    <v-container class="w-200" >
        <v-data-table 
            :headers="emailccHeaders"
            :items="emailcc"
            :search="search"
            class="elevation-1 ma-4 w-200 custom-tbl" >

            <template  style="font-size:16px;"  v-for="header in emailccHeaders" v-slot:[`header.${header.value}`]="{ header }">
                <v-icon medium color="#8DA90B">{{ header.icon }}</v-icon>
                <span style="color:#8DA90B"> &nbsp;{{ header.text }} </span>
            </template>
            <template v-slot:top >
                <v-toolbar style="border-radius:20px"
                    flat
                >
                    <v-toolbar-title class="font-weight-bold custom-color">CARBON COPY IN EMAIL</v-toolbar-title>
                        
                    <v-spacer></v-spacer>
                        <v-btn
                            color="#8DA90B"
                            dark
                            class="mb-2"
                            @click="OpenModal()"
                        >
                        <i class="fas fa-edit fa-fw"></i>
                        &nbsp;Add CC for Email
                        </v-btn>
                </v-toolbar>
                </template>
                    <template v-slot:[`item.emailAction`]="{ item }">

                            <v-tooltip bottom color="primary">
                                <template v-slot:activator="{ on, attrs }">
                                        <v-btn
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
                            <v-tooltip bottom  color="red">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                    color="white"
                                    dark
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    class="delete custom-btn"
                                    @click="deleteEmail(item.id)"
                                    >
                                    DELETE
                                    </v-btn>
                                </template>
                            <span>Delete Email</span>
                            </v-tooltip>
                    </template>
        </v-data-table>

            <!-- Position Modal -->
        <div class="modal fade" id="OpenModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content custom-tbl">
                <div class="modal-header">
                    <h5 class="modal-title custom-color" v-show="!editmode" id="addNewLabel">Add  CC in Email</h5>
                    <h5 class="modal-title custom-color" v-show="editmode" id="addNewLabel">Update CC in Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateEmail() : createEmail()">
                    <div class="modal-body">
                            <v-text-field
                                color="#8DA90B"
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
                        <v-btn v-show="editmode" type="submit" class="add" color="#8DA90B" elevation="2">Update</v-btn>
                        <v-btn v-show="!editmode" type="submit" class="add" color="#8DA90B"  elevation="2">Add Email</v-btn>
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

                    { text: 'Email', value: 'email_cc', icon: 'mdi-chevron-down-box' },
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
                    confirmButtonColor: '#8DA90B',
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

