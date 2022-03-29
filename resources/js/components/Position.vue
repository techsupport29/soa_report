<template>
    <v-container>
        <v-data-table
            :headers="positionHeaders"
            :items="position"
            :search="search"
            sort-by="calories"
            class="elevation-1 ma-4"
           
        >
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title class="font-weight-bold">POSITIONS</v-toolbar-title>
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
                            @click="addnewPostion()"
                        >
                        Add New Position
                        </v-btn>
                </v-toolbar>
                </template>
                    <template v-slot:[`item.PositionAction`]="{ item }">

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                        color="primary"
                                        class="mx-2"
                                        icon
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        @click="editPositionModal(item)"
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
                                    @click="deletePosition(item.id)"
                                    >
                                    <i class="fa fa-trash"></i>
                                    </v-btn>
                                </template>
                            <span>Delete info User</span>
                            </v-tooltip>
                    </template>
        </v-data-table>

            <!-- Position Modal -->
        <div class="modal fade" id="addPostion" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add  Position</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Position</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updatePosition() : createPosition()">
                    <div class="modal-body">
                            <v-text-field
                                prepend-inner-icon="mdi-crosshairs"
                                label="Position"
                                placeholder="user position"
                                outlined
                                dense
                                v-model="positionform.position"
                                :rules="[
                                    () => !!positionform.position || 'This field is required',

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
    
                positionHeaders : [
                  
                    { text: 'Position', value: 'position' },
                    { text: '', value: 'PositionAction', sortable: false},
                ],
                editmode: false,
            
                length: '',
                search: '',
               
                position:[],
                positionNew:'',
                
                positionform: new Form({
                    id:'',
                    position: '',
                }),
               
            }
        },
        methods: {
        
            addnewPostion(){
                $('#addPostion').modal('show');
                 this.positionform.reset();
                   this.editmode = false;
            },
            createPosition(){
                this.positionform.post('api/saveposition').then((data)=> {
                    console.log(data)
                      Fire.$emit('AfterCreate');
                      Toast.fire({
                        icon: 'success',
                        title: 'Position Added in successfully'
                        })
                         $('#addPostion').modal('hide');
                });
            },
            updatePosition(){
                this.positionform.put('api/updateposition/'+this.positionform.id).then((data)=> {
             
                      Fire.$emit('AfterCreate');
                      Toast.fire({
                        icon: 'success',
                        title: 'Position Added in Updated'
                        })
                         $('#addPostion').modal('hide');
                });
            },
            editPositionModal(data){
                this.editmode = true;
                this.positionform.reset();
                $('#addPostion').modal('show');
                this.positionform.fill(data);
            },
            loadPosition(){
                axios.get('api/getposition').then((data)=>{
                    this.position = data.data;
                    
                });
            },
            deletePosition(id){
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
                              axios.get('api/deletePosition/'+id).then((data)=> {
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

