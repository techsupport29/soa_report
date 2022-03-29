<template >
    <v-app>
		<v-container>
			<v-row class="mt-5 is-blurred" v-if="$gate.isAdminTech()">
				<v-col class="col-md-12">
					<v-card  class="mt-4">
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
                           	<v-card-actions class="card-tools">
                                <v-spacer></v-spacer>
								<v-btn color="success"
                                    elevation="2"  @click="newModal">Add New User <i class="fas fa-user-plus fa-fw"></i></v-btn>
							</v-card-actions>
                   
                            <v-data-table
                                    :headers="headers"
                                    :items="users.data"
                                    :items-per-page="10"
                                    :search="search"
                                    class="elevation-1 text-center"
                                >
                            <template v-slot:[`item.modify`]="{ item }">
                                {{$moment(item.updated_at).format('LL LTS') }}
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


                            <v-text-field
                                    prepend-inner-icon="mdi-shield-account"
                                    label="Username"
                                    placeholder="johndoe123"
                                    outlined
                                    dense
                                    :error-messages='usernameError'
                                    v-model="form.username"
                                    :rules="[() => !!form.username || 'This field is required']"
                                  
                            ></v-text-field>

                            <v-autocomplete
                                    :items="roles"
                                    label="Roles"
                                    prepend-inner-icon="mdi-account-tie"
                                    hide-details
                                    hide-no-data
                                    hide-selected
                                    outlined
                                    dense
                                    item-text="name"
                                    v-model="form.type"
                                    class="pb-4"

                            ></v-autocomplete> 

                            <v-autocomplete
                                    v-show="form.type != 'admin'"
                                    :items="position"
                                    label="Position"
                                    prepend-inner-icon="mdi-shield-account"
                                    hide-details
                                    hide-no-data
                                    hide-selected
                                    outlined
                                    dense
                                    item-text="position"
                                    item-value="id"
                                    v-model="form.position_id"
                                     class="pb-4"
                                    :rules="[
                                            () => !!form.position_id || 'This field is required',
                                            ]"
                            ></v-autocomplete>  

                            <v-select
                                v-model="form.permission"
                                :items="role_permission"
                                chips
                                item-text="name"
                                item-value="id"
                                label="Permissions"
                                prepend-inner-icon="mdi-account-lock"
                                dense
                                multiple
                                outlined
                                :rules="[
                                    () => !!form.permission || 'Select atleast one permission',
                                    ]"
                                deletable-chips
                                small-chips
                            >
                             <template v-slot:selection="{ item }">
                                <v-chip text-color="white" color="blue" small
                                >{{ item.name }}</v-chip>
                                </template>
                                <template v-slot:item="{  item, attrs, on }">
                                <v-list-item v-on="on" v-bind="attrs" #default="{ active }">
                                    <v-list-item-action>
                                        <v-checkbox 
                                        :input-value="active"

                                        ></v-checkbox>
                                    </v-list-item-action>
                                    <v-list-item-content @click="deletePermissions(active,item,userid)">
                                    <v-list-item-title>
                                        
                                        <v-row no-gutters align="center">
                                        <span>{{ item.name }} </span>
                                        <v-spacer></v-spacer>
                                         <v-chip text-color="white" color="#0000ff" small
                                        >{{ item.link}}</v-chip>
                                        </v-row>
                                    </v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                </template>
                            </v-select>

                            <v-text-field
                                    prepend-inner-icon="mdi-lock"
                                    type="password"
                                    label="Password"
                                    placeholder="*****"
                                    outlined
                                    dense
                                
                                    v-model="form.password"
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
	</v-app>
</template>

<script>
    export default {
        data() {
            return {
                headers: [
                    { text: 'Name', value: 'name' ,sortable: false },
                    { text: 'Username', value: 'username' ,sortable: false },
                    { text: 'Type', value: 'type' ,sortable: false },
                    { text: 'Modify', value: 'modify',sortable: false },
                    { text: '', value: 'actions', sortable: false },
                ],
                editmode: false,
                usernameError:'',
                users : {},
                length: '',
                search: '',
                teams:[],
                position:[],
                role_permission:[
                ],
                roles:[
                    'admin',
                    'employee'
                ],
                assigns:[
                    'computed',
                    'checked',
                    'prepared'
                ],
                role: {},
                userid:'',
                form: new Form({
                    id:'',
                    name : '',
                    email: '',
                    username: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                    team_id:'',
                    position_id:'',
                    assign:'',
                    isAdmin: false,
                    permission:[],
                  
                }),
                rules: {
                    password: []
                },
                checkboxRole:'',
              
               
                    
                
            }
        },
        methods: {
           
            loadroles(){
                axios.get("api/roles").then(({data}) => {
                     this.role_permission = data
                     console.log('roles' ,data)
                     });
            },
            loadTeams(){
                 axios.get("api/teams").then((data) => {
                     this.teams = data
                     console.log('team' ,data)
                     });
            },
            loadPosition(){
                axios.get('api/getposition').then((data)=>{
                    this.position = data.data;
                    
                });
            },
        
            updateUser(){

                this.$Progress.start();
                this.form.put('api/user/'+this.form.id)
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
            ViewModal(user){
                console.log('user',user);
            },
            async editModal(user){
                console.log('editmodal>>>>>>>>>',user);
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
                this.userid = user.id;
                await this.getPermission(user.id)
             
                await this.loadroles();

            },
            async getPermission(id){
                    const rolesPermission = await axios.get("api/getPermission/" + id);
                    const rolePerm = rolesPermission.data.map(r => r.roles)
                    this.form.permission = rolePerm

                    // console.log('THIS.FORM.PERMISSION', this.form.permission)
            },
           
            deletePermissions(status,roleid,userid){
                if(status == true){
                      axios.post('api/deletePermission',{
                            roleid : roleid,
                            userid : userid
                        }).then(({data})=>{
                            console.log(data)
                            const rolePerm = data.map(r => r.roles)
                            this.form.permission = rolePerm
                        })
                    
                }else{
                      console.log('no');
                }
               
              
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
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                         if (result.value) {
                                this.form.delete('api/user/'+id).then(()=>{
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
                    axios.get("api/user").then((data) => {
                        this.users = data
                        console.log('user',data.data);
                        });
                
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                .then((data)=>{
                    console.log(data);
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    fire.toast({
                        type: 'success',
                        title: 'User Created in successfully'
                        })
                    this.$Progress.finish();
                })
                .catch((error)=>{
                    this.usernameError = error.response.data.errors.username;
                   
                })
            }
        },
        created() {
            this.loadUsers();
            this.loadTeams();
            this.loadPosition();
            this.loadroles();
            Fire.$on('AfterCreate',() => {
                this.loadUsers();
            });
        }
    }
</script>
