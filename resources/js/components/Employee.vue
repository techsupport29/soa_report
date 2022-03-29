<style scoped>
 .nav-tabs .nav-link.active{
    background-color:#00C4F5!important ;
    color:white !important
}
</style>


<template >
    <v-app>
		<v-container>
			<v-row class="mt-5 is-blurred"  v-if="$gate.isAdmin()">
            
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
                             User Management
                             <v-spacer></v-spacer>
                               <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    class="mx-4"
                                ></v-text-field>
						
						</v-card-title>
                            <v-data-table
                                    :headers="headers"
                                    :items="employee"
                                    :search="search"
                                    class="elevation-1 text-center"
                                
                                >
                                    <template v-slot:top>
                                                <v-toolbar
                                                    flat
                                                >
                                                    <v-toolbar-title>Employee Details</v-toolbar-title>
                                                        <v-divider
                                                        class="mx-4"
                                                        inset
                                                        vertical
                                                    ></v-divider>
                                                    <v-spacer></v-spacer> 
                                                        <v-btn
                                                            color="primary"
                                                            dark
                                                             elevation="2"
                                                            class="mb-2"
                                                            @click="newModal()"
                                                        >
                                                        <i class="fas fa-user-plus fa-fw"></i>
                                                        Register Employee
                                                        </v-btn>
                                                </v-toolbar>
                                    </template>
                                    <template v-slot:[`item.assign`]="{ item }">
                                            <v-icon v-show="item.assign=='1'" large color="green">mdi-check</v-icon>
                                    </template>

                                    <template v-slot:[`item.actions`]="{ item }">

                                            <v-tooltip bottom>
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
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-btn
                                                    color="red"
                                                    dark
                                                    icon
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    class="mx-2"
                                                    @click="deleteUser(item.id)"
                                                    >
                                                    <i class="fa fa-trash"></i>
                                                    </v-btn>
                                                </template>
                                            <span>Delete info User</span>
                                            </v-tooltip>
                                    </template>
                        </v-data-table>
                    </v-card>
				</v-col>
			</v-row>

            
					<!-- Modal -->
			<div class="modal fade" id="addNew" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
						<h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editmode ? updateUser() : createUser()">
						<div class="modal-body">
                            <v-text-field
                                    label="Full name"
                                    placeholder="John doe Dela Cruz"
                                    outlined
                                    dense
                                    v-model="form.name"
                                    :rules="[() => !!form.name || 'This field is required']"
                                    required
                                     prepend-inner-icon="mdi-account"
                            ></v-text-field>

                            <!-- <v-text-field
                                     prepend-inner-icon="mdi-at"
                                    label="Email"
                                    placeholder="doe@gmail.com"
                                    outlined
                                    dense
                                    v-model="form.email"
                                    :rules="[
                                    () => !!form.email || 'This field is required',
                                    () =>  /.+@.+\..+/.test(form.email) || 'E-mail must be valid'
                                    ]"
                            ></v-text-field> -->


                            <v-autocomplete
                                :items="teams"
                                chips
                                label="Teams"
                                prepend-inner-icon="mdi-account-group"
                                hide-details
                                hide-no-data
                                hide-selected
                                outlined
                                dense
                                item-text="team"
                                v-model="form.team"
                                 class="pb-4"
                            ></v-autocomplete> 
                            

                            <v-autocomplete
                                :items="assigns"
                                label="Assign"
                                prepend-inner-icon="mdi-clipboard-account"
                                hide-details
                                hide-no-data
                                hide-selected
                                outlined
                                dense
                                v-model="form.group"
                                class="pb-4"
                                :rules="[
                                        () => !!form.group || 'This field is required',
                                        ]"
                            ></v-autocomplete>
                           

                           <v-autocomplete
                                :items="position"
                                chips
                                label="Position"
                                prepend-inner-icon="mdi-shield-account"
                                hide-details
                                hide-no-data
                                hide-selected
                                outlined
                                dense
                                item-text="position"
                                v-model="form.position"
                                 :rules="[
                                        () => !!form.position || 'This field is required',
                                        ]"
                            ></v-autocomplete> 

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
	</v-app>
</template>

<script>
    export default {
        data() {
            return {
                headers: [
                    { text: 'Name', value: 'name',sortable: false },
                    { text: 'Email', value: 'email' ,sortable: false},
                    // { text: 'Number', value: 'contact'},
                    {text: 'Team', value: 'team', sortable: false},
                    { text: 'Position', value: 'position',sortable: false },
                    {text:'Assign', value: 'group',sortable: false},

                    { text: '', value: 'actions', sortable: false },
                ],
               
                editmode: false,
                employee : [],
                length: '',
                search: '',
                assigns:[
                    'computed',
                    'checked',
                    'prepared'
                ],
                selected: [],
                check: false,
               
                form: new Form({
                    id:'',
                    name : '',
                    email: '',
                    address: '',
                    contact: '',
                    position: '',
                    group:'',
                }),
               

            }
        },
        methods: {
            loadPosition(){
                axios.get('api/getposition').then((data)=>{
                    this.position = data.data;
                    
                });
            },
           loadEmployee() {
                axios
                .get("api/employees")
                .then(
                    ({data}) => {
                      
                        
                        const selectedCompute = data.computed.filter(c => c.assign !== 0);
                        const selectedPrepared = data.prepared.filter(c => c.assign !== 0);
                         const checks = data.checked.filter(c => c.assign !== 0);
                        this.selectComputed = selectedCompute[0];
                        this.selectPrepared = selectedPrepared[0];
                        this.selectChecked1 = checks[0];
                        this.selectChecked2 = checks[1];
                        this.checks = checks
                        this.userPrepared.computed = data.computed;
                        this.userPrepared.prepared = data.prepared;
                        this.userPrepared.checked1 = data.checked.filter(c => c.id !== checks[1]?.id);
                        this.userPrepared.checked2 = data.checked.filter(c => c.id !== checks[0]?.id);

                    }
                );
            },
            updateUser(){
                this.$Progress.start();
                console.log('Editing data');
                this.form.put('api/employees/'+this.form.id)
                .then(() => {
                    $('#addNew').modal('hide');
                     swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            editModal(user){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);


            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
               
            },
            deleteUser(id){
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
                                this.form.delete('api/employees/'+id).then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wrong.", "warning");
                                });
                         }
                    })
            },
            loadUsers(){
                var selected = [];
                if(this.$gate.isAdmin()){
                    axios.get("api/employees").then((data) => (
                        this.employee = data.data.employee,

                        this.employee.forEach((element)=>{
                            selected.push(element.assign)
                            this.selected = element.assign
                        }),
                        this.selected = selected
                       
                    ));
                }
               


            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/employees')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    Toast.fire({
                        type: 'success',
                        title: 'User Created in successfully'
                        })
                    this.$Progress.finish();
                })
                .catch(()=>{
                })
            }
        },
        created() {
           this.loadUsers();
           this.loadEmployee();
            this.loadPosition();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
               this.loadEmployee();
                this.loadPosition();
           });
        }
    }
</script>
