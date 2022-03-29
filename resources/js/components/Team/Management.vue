<template lang="">
    <v-container>
        <v-card>
          <v-toolbar elevation="0" dark color="primary">
            <v-icon class="mr-4">mdi-group</v-icon>
            <v-toolbar-title>{{selectedTeam && selectedTeam.name.toUpperCase()}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dark text @click.stop="closeViewTeam">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-toolbar-items>
          </v-toolbar>
          <v-card-text>
            <v-tabs
              color="deep-purple accent-4"
              right
              flat                      
            >
              <v-tab>Arenas</v-tab>
              <v-tab>Members</v-tab>
              <v-tab-item>
                <v-data-table :items="arenaTeams" :headers="headers">
                  <template v-slot:top>
                    <v-toolbar flat>                                        
                      <v-tooltip color="blue lighten-1" bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            color="primary"
                            dark
                            v-bind="attrs"
                            v-on="on"
                            class="mx-2"
                            @click="openArenaSelection"                                                                  
                          >
                            <v-icon>mdi-stadium-variant</v-icon>
                            Add Arena
                          </v-btn>
              </template>
                  <span>Add Arena to {{selectedTeam && selectedTeam.name.toUpperCase()}}</span>
                                                    </v-tooltip>
                                            </v-toolbar>

                                            <v-dialog v-model="dialogRemoveItem" max-width="500px"  persistent>
                                                    <v-card>
                                                        <v-card-title class="text-h6"> Remove {{selectedArena && selectedArena.area_code}} from {{selectedTeam && selectedTeam.name.toUpperCase()}}?</v-card-title>
                                                    
                                                        <v-card-actions >
                                                            <v-spacer></v-spacer>
                                                            <v-btn color="red darken-1" text @click="dialogRemoveItem = false">Cancel</v-btn>
                                                            <v-btn color="blue darken-1" text @click="removeItemConfirm">OK</v-btn>
                                                            
                                                        </v-card-actions>
                                                    </v-card>
                                            </v-dialog>                                             
                                    </template>

                                <template v-slot:[`item.team`]="{ item }">
                                    <v-edit-dialog
                                    :return-value.sync="item.team"
                                    large
                                     persistent
                                    @save="save(item)"
                                    @cancel="cancel"
                                    @open="open"
                                    @close="close"
                                    :default="item.team"
                                    >
                                     <div>{{ item.team }}</div>
                                        <template v-slot:input>
                                            <div class="mt-4 text-h6">
                                                Change Team
                                            </div>
                                        
                                            <v-select
                                                
                                                v-model="item.team"
                                                :items="teams"
                                                menu-props="auto"
                                                label="Team"
                                                single-line
                                                counter
                                                autofocus
                                                item-text='name'
                                                item-value='name'
                                                @change="handleChanged"
                                                
                                            ></v-select>
                                            
                                        </template>
                                    </v-edit-dialog>
                                </template>
                              
                                <template v-slot:[`item.actions`]="{ item }">

                                        <v-tooltip color="error" bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn
                                                    color="error"
                                                    dark
                                                    icon
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    class="mx-2"
                                                    @click="removeItem(item)"
                                                    
                                                >
                                                    <v-icon>mdi-playlist-remove</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Remove {{ item.name }}</span>
                                        </v-tooltip>
                                </template>
                               
                            </v-data-table>
                         
                           
                        </v-tab-item>
                        <!-- MEMBERS -->
                         <v-tab-item>
                             
                                <v-data-table v-model="selected" value="10"  :items="userTeams" item-key="id"  :headers="headersUser" single-select show-select @input="handleChangedSelectedUser">
                                <template v-slot:top>
                                    <v-toolbar flat>                                        
                                        <v-spacer></v-spacer> 
                                                <v-tooltip color="blue lighten-1" bottom>
                                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                              color="primary"
                                              dark
                                              v-bind="attrs"
                                              v-on="on"
                                              class="mx-2"
                                              @click="openUserSelection"
                                            >
                                              <v-icon>mdi-account-plus</v-icon>
                                              Add User
                                            </v-btn>
                                          </template>
                                                            <span>Add User to {{selectedTeam && selectedTeam.name}}</span>
                                                        </v-tooltip>
                                                </v-toolbar>
                                                
                                                   <v-dialog v-model="dialogRemoveUserItem" max-width="500px">
                                                    <v-card>
                                                    <v-card-title class="text-h6">Remove {{selectedUser && selectedUser.name}} from {{selectedTeam && selectedTeam.name}}?</v-card-title>
                                                
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn color="red darken-1" text @click="dialogRemoveUserItem = false">Cancel</v-btn>
                                                        <v-btn color="blue darken-1" text @click="removeUserItemConfirmation">OK</v-btn>
                                                       
                                                    </v-card-actions>
                                                    </v-card>
                                                </v-dialog>


                                    </template>


                                     <template
                                                v-slot:[`item.data-table-select`]="{
                                                    item,
                                                    isSelected,
                                                    select,
                                                }"
                                            >
                                                <v-simple-checkbox
                                                    
                                                    :value="item.id == assignedComputed"
                                                    @change="handleChangedSelectedUser(item)"
                                                    @input="select(item)"
                                                ></v-simple-checkbox>
                                            </template>

                                <template v-slot:[`item.team`]="{ item }">
                                    <v-edit-dialog
                                    :return-value.sync="item.team"
                                    large
                                     persistent
                                    @save="saveTeamUser(item)"
                                    @cancel="cancel"
                                    @open="openUser"
                                    @close="closeUser"
                                    :default="item.team"
                                    >
                                     <div>{{ item.team }}</div>
                                        <template v-slot:input>
                                            <div class="mt-4 text-h6">
                                                Change Team
                                            </div>
                                           
                                            <v-select
                                                :items="teams"
                                                menu-props="auto"
                                                label="Team"
                                                single-line
                                                counter
                                                autofocus
                                                item-text='name'
                                                item-value='id'
                                                :value="item"
                                                return-object
                                                @change="handleChangedUserTeam"
                                               
                                            ></v-select>
                                            
                                        </template>
                                    </v-edit-dialog>
                                </template>
                              
                                  <template v-slot:[`item.actions`]="{ item }">
                                          <v-tooltip color="error" bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn
                                                    color="error"
                                                    dark
                                                    icon
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    class="mx-2"
                                                    @click="removeUserItem(item)"
                                                    
                                                >
                                                    <v-icon>mdi-playlist-remove</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Remove {{ item.name }}</span>
                                          </v-tooltip>
                                    </template>
                            </v-data-table>
                        </v-tab-item>
                    </v-tabs>
                    </v-card-text>
                </v-card>
            <!-- </v-dialog> -->

             <v-dialog internal-activator v-model="addNewArenaItem" max-width="500px">
                <v-card>
                   <v-card-title class="text-h6">Add arena into {{selectedTeam && selectedTeam.name}} team</v-card-title>
                      <v-card-title>
                        <v-text-field
                          v-model="arenaSearch"
                          append-icon="mdi-magnify"
                          label="Search"
                          single-line
                          hide-details
                        ></v-text-field>
                      </v-card-title>
                    <v-card-text>
                        <span class="subtitle-2">Select arena that not yet selected to other team.</span>
                          <v-data-table v-model="selectedArenasToTeam"  :items="arenaNoTeam" :headers="headersArena" :search="arenaSearch" :itemsPerPage="5" show-select  :footer-props="{
                                                'items-per-page-options': [
                                                    5,
                                                    10,
                                                ],
                                            }">

                          </v-data-table>
                        
                    </v-card-text>                           
                    <v-card-actions>
                        <v-spacer></v-spacer>                         
                            <v-btn color="red darken-1" text @click="() => {addNewArenaItem = false; this.selectedArenasToTeam = []}">Cancel</v-btn>
                            <v-btn :disabled="selectedArenasToTeam < 1 ? true : false" color="blue darken-1" text @click="addArenaSelectedToTeam">ADD</v-btn>
                            
                    </v-card-actions>
                </v-card>
            </v-dialog>

                <!-- ADD MEMBER TO TEAM LIST -->
               <v-dialog internal-activator v-model="addNewUserItem" max-width="500px">
                <v-card>
                    <v-card-title class="text-h6">Add user into {{selectedTeam && selectedTeam.name}} team</v-card-title>
                       <v-card-title>
                        <v-text-field
                          v-model="staffSearch"
                          append-icon="mdi-magnify"
                          label="Search"
                          single-line
                          hide-details
                        ></v-text-field>
                      </v-card-title>
                    <v-card-text>
                        <!-- <span class="subtitle-2">Select user that not yet selected to other team.</span>
                          <v-autocomplete
                            v-model="addSelectedUserTeamItem"
                            :items="userNoTeam"
                            color="white"
                            item-text="name"
                            label="Staffs"
                           
                            return-object
                        ></v-autocomplete> -->
                         <v-data-table v-model="selectedUserToTeam"  :items="userNoTeam" :headers="headerStaffs" :search="staffSearch" :itemsPerPage="5" show-select  :footer-props="{
                                                'items-per-page-options': [
                                                    5,
                                                    10,
                                                ],
                                            }">

                          </v-data-table>
                        
                    </v-card-text>                           
                    <v-card-actions>
                        <v-spacer></v-spacer>                         
                            <v-btn color="red darken-1" text @click="() => {addNewUserItem = false}">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="addUserSelectedToTeam">ADD</v-btn>
                            
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-snackbar
                v-model="snack"
                :timeout="2000"
                :color="snackColor"
                bottom
                right
                style="z-index: 9999;"
                >
                {{ snackText }}

            </v-snackbar>

    </v-container>
</template>

<script>
export default {
  name: "team-management",
  props: {
    viewTeam: Boolean,
    selectedTeam: Object,
    arenaTeams: Array,
    userTeams: Array,
    teams: Array,
    getAllArenaPerTeam: Function,
    getAllUserPerTeam: Function,
    getAssignUserTeam: Function,
    loadTeam: Function,
    assignedComputed: Number,
  },
  data() {
    return {
      snack: false,
      snackColor: "",
      snackText: "",
      arenaSearch: "",
      staffSearch: "",
      tab: null,
      headers: [
        { text: "Code", value: "area_code" },
        { text: "Arena", value: "arena" },
        { text: "Team", value: "team" },
        { text: "", value: "actions", sortable: false },
      ],
      headersUser: [
        { text: "Name", value: "name" },
        { text: "Position", value: "position_details.position" },
        { text: "Team", value: "team" },
        { text: "", value: "actions", sortable: false },
      ],
      headerStaffs: [
         { text: "Name", value: "name" },
        { text: "Position", value: "position_details.position" },
      ],
      headersArena: [
        { text: "Code", value: "area_code" },
        { text: "Arena", value: "arena" },
      ],

      selected: [],
      selectedArenasToTeam: [],
      selectedUserToTeam: [],
      newSelectedTeam: "",
      newSelectedUserTeam: "",
      dialogRemoveItem: false,
      dialogRemoveUserItem: false,
      selectedArena: {},
      selectedUser: {},
      addNewArenaItem: false,
      addNewUserItem: false,
      arenaNoTeam: [],
      userNoTeam: [],
      addSelectedArenaTeamItem: null,
      addSelectedUserTeamItem: null,
      assignedCompt: this.assignedComputed,
    };
  },
  methods: {
    closeViewTeam() {
      this.$emit("update:viewTeam", false);
      this.$emit("update:arenaTeams", []);
      this.$emit("update:userTeams", []);
      this.$emit("update:selectedTeam", null);
    },

    async save(item) {
      this.snack = true;
      this.snackColor = "success";
      this.snackText = "Data saved";

      if (this.newSelectedTeam !== this.selectedTeam.name) {
        this.$emit(
          "update:arenaTeams",
          this.arenaTeams.filter(function (v, i) {
            return v.area_code !== item.area_code && v.team !== item.team;
          })
        );
        await axios.put("api/updateArenaTeam/" + item.area_code, {
          team: item.team,
        });
        await Fire.$emit("AfterCreateUserTeam");
      }
    },
    async saveTeamUser(item) {
      this.snack = true;
      this.snackColor = "success";
      this.snackText = "Data saved";
      const teamId = this.newSelectedUserTeam.id;

      if (teamId === this.selectedTeam.id) {
        console.log("Not happening");
      } else if (item.isAssign) {
        console.log("Not happening IS ASSIGN");
      } else {
        this.$emit(
          "update:userTeams",
          this.userTeams.filter(function (v, i) {
            return v.id !== item.id;
          })
        );
        await axios.put("api/updateUserTeam/" + item.id, {
          team_id: teamId,
           assign: 'computed'
        });
        await Fire.$emit("AfterCreateUserTeam");
      }
    },
    cancel() {
      this.snack = true;
      this.snackColor = "error";
      this.snackText = "Canceled";
    },
    open() {
      this.snack = true;
      this.snackColor = "info";
      this.snackText = "Dialog opened";
    },
    openUser(item) {
      console.log("OPEN TEAM ");
    },
    close() {
      console.log("Dialog closed");
    },
    closeUser(item) {},
    handleChanged(item) {
      this.newSelectedTeam = item;
    },
    handleChangedUserTeam(item) {
      this.newSelectedUserTeam = item;
    },
    openArenaSelection() {
      this.addNewArenaItem = true;
      this.getArenasWithoutTeam();
    },
    openUserSelection() {
      this.addNewUserItem = true;
      this.getUsersWithoutTeam();
    },
    
    removeItem(item) {  // Open dialog asking to confirm if arena will be remove
      this.selectedArena = item;
      this.dialogRemoveItem = true;
    },
   
    async removeItemConfirm() {  // Remove Arena to specific a team
      const code = this.selectedArena.area_code;

      await axios.put("api/updateArenaTeam/" + code);

      this.$emit(
        "update:arenaTeams",
        this.arenaTeams.filter(function (v, i) {
          return v.area_code !== code;
        })
      );
      await Fire.$emit("AfterCreateArenaTeam");

      this.selectedArena = {};
      this.dialogRemoveItem = false;
    },
    
    removeUserItem(item) { // Open dialog asking to confirm if user will be remove
      this.selectedUser = item;
      this.dialogRemoveUserItem = true;
    },
  
    async removeUserItemConfirmation() {   // Remove user to specific a team
      const user = this.selectedUser; 
      await axios.put("api/updateUserTeam/" + user.id, {
        team_id: null,
        assign: null
      });

      this.$emit(
        "update:userTeams",
        this.userTeams.filter(function (v, i) {
          return v.id !== user.id;
        })
      );

      await Fire.$emit("AfterCreateUserTeam");
      this.selectedUser = {};
      this.dialogRemoveUserItem = false;
    },
    
    async getArenasWithoutTeam() { // Get all arenas without team
      const arena = await axios.get("api/arena");
      const arenaNoTeam = arena.data.filter((a) => a.team == null);

      this.arenaNoTeam = arenaNoTeam;
    },
    // get all users that are not admin and no team and not yet assigned into a signatory
    async getUsersWithoutTeam() {
      const users = await axios.get("api/getStaffs");

      this.userNoTeam = users.data;
    },

    // async addArenaToTeam() {
    //   this.addSelectedArenaTeamItem.team = this.selectedTeam.name;
    //   await axios.put(
    //     "api/updateArenaTeam/" + this.addSelectedArenaTeamItem.area_code,
    //     { team: this.selectedTeam.name }
    //   );
    //   this.addNewArenaItem = false;

    //   //   let arrayArena = this.arenaTeams;
    //   //   arrayArena.push(this.addSelectedArenaTeamItem);
    //   Fire.$emit("AfterCreateArenaTeam");
    //   // this.$emit("update:arenaTeams", arrayArena);
    // },

    // async addUserToTeam() {
    //   this.addSelectedUserTeamItem.team = this.selectedTeam.name;
    //   const user = this.addSelectedUserTeamItem;
    //   const team_id = this.selectedTeam.id;
    //   await axios.put("api/updateUserTeam/" + user.id, { team_id });
    //   this.addNewUserItem = false;
    //   Fire.$emit("AfterCreateUserTeam");
    // },
    
      // // ADD multiple users to team and assigned as computed
      async addUserSelectedToTeam() {
          // let usersTeam = [];

          // this.selectedUserToTeam.forEach((a) => {
          //   const { bank_details, contact_details, ...arenas } = a;
          //   usersTeam.push({ ...arenas, team: this.selectedTeam.name });
          // });

          const teamId = this.selectedTeam.id;
          await axios.put(
            `api/updateSelectedUserToTeam/${teamId}`,
            { 
              assign: 'computed',
              users: this.selectedUserToTeam
            }
          );
          Fire.$emit("AfterCreateUserTeam");
          Fire.$emit("AfterAddSelected");

          this.addNewUserItem = false;
          this.selectedUserToTeam = [];
    },
    // Assign specific user as computed
    async handleChangedSelectedUser() {
      const assignedd = await axios.put(
        "api/updateAssignedTeam/" + this.selected[0].id,
        { team_id: this.selectedTeam.id }
      );

      this.assignedCompt = assignedd.data.id;
      Fire.$emit("AfterAssigned");
      Fire.$emit("AfterCreateUserTeam");
      this.$emit("update:assignedComputed", this.assignedCompt);
    },

    // Assign multiple arena to specific team
    async addArenaSelectedToTeam() {
      let arenasTeam = [];

      this.selectedArenasToTeam.forEach((a) => {
        const { bank_details, contact_details, ...arenas } = a;
        arenasTeam.push({ ...arenas, team: this.selectedTeam.name });
      });

      const team = this.selectedTeam.name;
      await axios.put(
        `api/updateSelectedArenaToTeam/${team}`,
        this.selectedArenasToTeam
      );
      Fire.$emit("AfterCreateArenaTeam");
      Fire.$emit("AfterAddSelected");

      this.addNewArenaItem = false;
      this.selectedArenasToTeam = [];
    },

    // getAssignUserTeam(){
    //       axios.get('api/assigneduserteam/'+this.selectedTeam.id).then(({data}) => {
    //         //  this.assignedComputed = data.id.toString();
    //         this.selected.push({id: data.id})
    //      });
    // }
  },
  computed: {
    viewingTeam: {
      get() {
        return this.viewTeam;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
  },
  created() {
    Fire.$on("AfterCreateUserTeam", () => {
      this.getAllUserPerTeam();
      this.loadTeam();
    });
    Fire.$on("AfterCreateArenaTeam", () => {
      this.getAllArenaPerTeam();
      this.loadTeam();
    });

    Fire.$on("AfterAssigned", () => {
      this.getAssignUserTeam();
      this.loadTeam();
    });

    Fire.$on("AfterAddSelected", () => {
      this.getArenasWithoutTeam();
      this.loadTeam();
    });
  },
};
</script>
<style lang=""></style>
