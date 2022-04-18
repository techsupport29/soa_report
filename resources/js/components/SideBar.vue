<template>
 <v-app>
    <v-container>
        <v-data-table
            :headers="sidebarHeaders"
            :items="sidebar"
            :search="search"
            class="elevation-1 ma-4"

        >
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title class="font-weight-bold">SideBar Configuration</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                        <v-btn
                            class="mb-2 bg-success"
                            @click="openModalSidebar()"
                        >
                        Add New Sidebar
                        </v-btn>
                </v-toolbar>
                </template>
                <template v-slot:[`item.link`]="{ item }">
                  {{env_url}}/{{item.link}}
                </template>
                <template v-slot:[`item.icon`]="{ item }">
                   	<i :class="`text-success nav-icon fas fa-thin` + item.icon" ></i>
                </template>
                <template v-slot:[`item.action`]="{ item }">
                            <v-tooltip bottom color="primary">
                                <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                        color="primary"
                                        class="mx-2"
                                        icon
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        @click="EditModalSidebar(item)"
                                        >
                                        <i class="fas fa-edit"></i>
                                        </v-btn>
                                </template>
                            <span>Edit Sidebar Info</span>
                            </v-tooltip>
                            |
                            <v-tooltip bottom color="red">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                    color="red"
                                    dark
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    class="mx-2"
                                    @click="deleteSidebar(item.id)"
                                    >
                                    <i class="fa fa-trash"></i>
                                    </v-btn>
                                </template>
                            <span>Delete Sidebar User</span>
                            </v-tooltip>
                    </template>
        </v-data-table>

            <!-- sidebar Modal -->
        <div class="modal fade" id="sidebarModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add  Sidebar</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Sidebar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateSidebar() : createSidebar()">
                    <div class="modal-body">
                        <v-text-field
                                prepend-inner-icon="mdi-apple-keyboard-command"
                                label="SideBar Name"
                                placeholder="name"
                                outlined
                                dense
                                v-model="sidebarform.name"
                                :rules="[
                                    () => !!sidebarform.name || 'This field is required',

                                ]"
                                required>
                        </v-text-field>
                               <v-text-field
                                prepend-inner-icon="mdi-link"
                                label="Route name"
                                placeholder="link"
                                outlined
                                dense
                                v-model="sidebarform.link"
                                :rules="[
                                    () => !!sidebarform.link || 'This field is required',

                                ]"
                                required>
                        </v-text-field>
                        <v-text-field
                                prepend-inner-icon="mdi-star-face"
                                label="Icon"
                                placeholder="font awesome icon"
                                outlined
                                dense
                                v-model="sidebarform.icon"
                                :rules="[
                                    () => !!sidebarform.icon || 'This field is required',

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
</v-app>
</template>

<script>
    export default {
        name: 'sidebar',
        data() {
            return {
                sidebarHeaders : [
                    { text: 'Name', value: 'name' },
                    { text: 'Url', value: 'link' },
                    { text: 'Icon', value: 'icon' },
                    { text: '', value: 'action', sortable: false},
                ],
                editmode: false,
                env_url: process.env.APP_URL,
                length: '',
                search: '',
                sidebar:[],
                sidebarform: new Form({
                    id:'',
                    name: '',
                    link:'',
                    icon:'',
                }),

            }
        },
        methods: {
            loadSidebar(){
                axios.get('api/sidebar').then(({data})=>{
                    this.sidebar = data;

                });
            },
            openModalSidebar(){
                $('#sidebarModal').modal('show');
                 this.sidebarform.reset();
                   this.editmode = false;
            },
            createSidebar(){
                this.sidebarform.post('api/sidebar').then((data)=> {
                    console.log(data)
                      Fire.$emit('AfterCreate');
                      Toast.fire({
                        icon: 'success',
                        title: 'Link Added in successfully'
                        })
                         $('#sidebarModal').modal('hide');
                });
            },
            EditModalSidebar(data){
                this.editmode = true;
                this.sidebarform.reset();
                $('#sidebarModal').modal('show');
                this.sidebarform.fill(data);
            },
            updateSidebar(){
                this.sidebarform.put('api/sidebar/'+ this.sidebarform.id).then((data)=> {
                      Fire.$emit('AfterCreate');
                      Toast.fire({
                        icon: 'success',
                        title: 'Link updated'
                        })
                         $('#sidebarModal').modal('hide');
                });
            },

            deleteSidebar(id){
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
                              this.sidebarform.delete('api/sidebar/'+id).then((data)=> {
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
            this.loadSidebar();
           Fire.$on('AfterCreate',() => {
                this.loadSidebar();
           });
        }
    }
</script>

