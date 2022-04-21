<template>
    <v-app>
        <v-container>
            <v-row class="mt-5 is-blurred flex-column flex-md-row" v-if="$gate.isAdminTech()">
                <v-col class="col-md-100">
                    <v-card outlined color="transparent vcard">
                        <v-card-title class="custom-color">
                            Team Management
                            <v-spacer></v-spacer>
                             <v-text-field
                            color="#8DA90B"
                            v-model="search"
                            label="Search"
                            class="mx-4"
                            >
                                <template v-slot:prepend-inner>        
                                    <v-icon outlined dark color="#8DA90B">mdi-magnify</v-icon> 
                                </template>
                            </v-text-field>
                        </v-card-title>
                        <v-card-actions class="card-tools m-10">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="#8DA90B"
                                            dark
                                            v-bind="attrs"
                                            v-on="on"
                                            class="mx-2"
                                            @click="openNewTeam"
                                        >
                                        Add New Team
                                            <v-icon style="padding:5px"
                                                >mdi-account-multiple-plus</v-icon
                                            >
                                        </v-btn>
                                    </template>
                                    <span>Add New Team</span>
                                </v-tooltip>
                        </v-card-actions>
                      
                        <v-data-table
                            :headers="headers"
                            :items-per-page="10"
                            :search="search"
                            :items="teams"
                            class="elevation-1 text-center mt-5 custom-tbl"
                        >
                            <template  style="font-size:16px;"  v-for="header in headers" v-slot:[`header.${header.value}`]="{ header }">
                                <v-icon medium color="#8DA90B">{{header.icon}}</v-icon>
                                <span style="color:#8DA90B"> &nbsp; {{ header.text }} </span>
                            </template>
                            <template v-slot:[`item.name`]="{ item }">
                                 <span class="font-weight-medium">{{item.name != null ? item.name.toUpperCase() : item.name }}</span>
                            </template>
                            <template v-slot:[`item.user_details`]="{ item }">
                                <div class="d-flex flex-column">
                                    <span class="font-weight-medium">{{ item.user_details && item.user_details.name }}</span>
                                    <span class="caption">{{ (item.user_details && item.user_details.position_details) ? item.user_details.position_details.position : ''}}</span>
                                </div>                          
                            </template>
                            <template v-slot:[`item.arena_details`]="{ item }">
                                <span class="font-weight-medium">{{ item.arena_details && item.arena_details.length }}</span>               
                             </template>
                            <template v-slot:[`item.actions`]="{ item }">
                                <v-tooltip color="primary" bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="white"
                                            icon
                                            dark
                                            class="view custom-btn"
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="openViewTeam(item)"
                                        >
                                            View
                                        </v-btn>
                                    </template>
                                    <span>View {{ item.name }}</span>
                                </v-tooltip>

                                <v-tooltip color="yellow darken-4" bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="white"
                                            icon
                                            dark
                                            class="edit custom-btn"
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="updateViewTeam(item)"
                                        >
                                            Update
                                        </v-btn>
                                    </template>
                                    <span>Edit {{ item.name }}</span>
                                </v-tooltip>

                                <v-tooltip color="error" bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="white"
                                            dark
                                            icon
                                            class="delete custom-btn"
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="deleteNewTeam(item.id)"
                                        >
                                            Delete
                                        </v-btn>
                                    </template>
                                    <span>Delete {{ item.name }}</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-col>
                <v-col>
                    <v-banner
                    class="mt-7"
                    v-if="!viewTeam"
                    single-line
                    transition="slide-y-transition"
                    ><b class="custom-color">No team selected yet</b></v-banner>
                    <team-management 
                    v-else 
                    :loadTeam="loadTeam" 
                    :viewTeam.sync="viewTeam" 
                    :selectedTeam.sync="selectedTeam" 
                    :arenaTeams.sync="arenaTeams" 
                    :teams="teams" 
                    :getAllArenaPerTeam="getAllArenaPerTeam" 
                    :userTeams.sync="userTeams" 
                    :getAllUserPerTeam="getAllUserPerTeam" 
                    :assignedComputed.sync="assignedComputed" 
                    :getAssignUserTeam="getAssignUserTeam">
                    </team-management>
                </v-col>
            </v-row>

            <v-dialog
                transition="dialog-bottom-transition"
                max-width="400"
                v-model="openDialog"
            >
                <v-card class="custom-tbl">
                    <v-toolbar elevation="0">
                        <v-toolbar-title class="text-h6 pl-0 custom-color">
                           {{editMode === true ? 'Update Team' : 'Add New Team'}} 
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    medium
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="openDialog = false"
                                >
                                    <v-icon medium>mdi-close</v-icon>
                                </v-btn>
                            </template>
                            <span>Close Modal</span>
                        </v-tooltip>
                    </v-toolbar>

                    <v-card-text>
                        <v-text-field
                            v-model="team.name"
                            label="Team Name"
                            placeholder="Emerald"
                            outlined
                            color="#8DA90B"
                            required
                            prepend-inner-icon="mdi-account-group"
                        ></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn class="add" :color="editMode === true ? '#8DA90B' : '#8DA90B'" @click="editMode === true ? updateNewTeam() :  createNewTeam()"
                            ><v-icon>{{editMode === true ? 'mdi-update' : 'mdi-plus ' }}</v-icon> {{editMode === true ? 'Update' : 'Add'}}</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
                </v-container>
    </v-app>
</template>

<script>
import TeamManagement from './Team/Management.vue'
import Signatory from './Signatory.vue'

export default {
    components: {TeamManagement, Signatory},
    data() {
        return {
            headers: [
                { text: "Name", value: "name", icon: "mdi-account-details"},
                { text: "Computed By", value: "user_details", icon: "mdi-calculator" },
                { text: "No. of Arenas", value: "arena_details", icon: "mdi-numeric"},
                { text: "", value: "actions", sortable: false },
            ],
            search: "",
            openDialog: false,
            editMode: false,
            viewTeam: false,
            team: {
                id:"",
                name: "",
            },
            teams: [],
            arenaTeams: [],
            userTeams: [],
            selectedTeam: {
                name: ""
            },
            assignedComputed: null,
            arenaCount: 0,
        };
    },
    methods: {
        loadTeam() {
            axios.get("api/teams").then(({data}) => {
          
                this.teams = data
            });
        },
        openNewTeam() {
            this.team.name = null;
            this.editMode = false;
            this.openDialog = true;
        },
        getAssignUserTeam(){
      
            axios.get('api/assigneduserteam/'+this.selectedTeam.id).then(({data}) => {
             
                this.assignedComputed = data.id;
            }); 
            
        },
        async getAllArenaPerTeam(){
            const arenaTeams = await axios.get('api/getArenaTeam/'+this.selectedTeam.name);
            let arenaTeamsArray = []
            arenaTeams.data.forEach(a => {
                arenaTeamsArray.push({ ...a, team: a.team.toUpperCase()})
            })
            this.arenaTeams = arenaTeamsArray;
        
        },
        async getAllUserPerTeam(){
            const userTeams = await axios.get('api/getUserTeam/'+this.selectedTeam.id);
            let userTeamsArray = []
            userTeams.data.forEach(a => {
               userTeamsArray.push({ team: a.team_details.name.toUpperCase(),team_details: { name: a.team_details.name.toUpperCase()}, ...a,  })
            })
           
            this.userTeams = userTeamsArray
        },
       
        openViewTeam(item){
         
            this.viewTeam = true;
            this.selectedTeam = item
            this.getAssignUserTeam(item)
            this.getAllArenaPerTeam(item);
            this.getAllUserPerTeam(item);
            
        },
        updateViewTeam(item){
            this.editMode = true;
            this.openDialog = true;
            this.team.name = item.name;
            this.team.id = item.id;
            // this.selectedTeam = item
           
        },
      
        createNewTeam() {
            axios.post("api/teams", this.team).then((data) => {
                Toast.fire({
                    icon: "success",
                    title: "Team Added in successfully",
                });
                this.openDialog = false;
                Fire.$emit("AfterCreate");
            });
        },
        updateNewTeam(){
    
            axios.post('api/updateTeam/'+this.team.id,{
                team : this.team.name
            }).then((data) => {
                Toast.fire({
                    icon: "success",
                    title: "Team Updated in successfully",
                });
                this.openDialog = false;
                Fire.$emit("AfterCreate");
            });
        },
        deleteNewTeam(id){
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
                            axios.get('api/deleteTeam/'+id).then(()=>{
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

        }
        
    },
    created() {
        this.loadTeam();
        Fire.$on("AfterCreate", () => {
            this.loadTeam();
        });
    },
};
</script>
<style scoped>
    .custom-color{
    color:#8DA90B;
    }

    .vcard{
        border-radius: 20px;
    }

    .add{
        background-color: #8DA90B;
        color:white;
    }

    .view{
        background-color: #3a89e8;
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
