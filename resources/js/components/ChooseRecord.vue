
<template >
    <v-app>
		<v-container>
			<v-row class="mt-5 is-blurred"  >
            
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
                             Choose Record
                             <v-spacer></v-spacer>
                               <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    class="mx-4"
                                ></v-text-field>
						
						</v-card-title>
                        <div class="ma-5">
                         
                            <v-row>
                                 
                                <v-col>
                                        <v-select
                                            
                                            label="Prepared By"
                                            :value="selectPrepared"
                                            :items="userPrepared.prepared"
                                            name="prepared"
                                            :item-text="item => `${item.name} - ${item.position}`"
                                            
                                            return-object
                                            @change="selectedrecord(selectPrepared, $event)"
                                        ></v-select>
                                </v-col>
                                 <v-col>
                                        <v-select
                                        
                                            label="Checked By"
                                            :value="selectChecked1"
                                            :items="userPrepared.checked1"
                                            name="checked1"
                                            :item-text="item => `${item.name} - ${item.position}`"
                                        
                                            return-object
                                            
                                            @change="selectedrecord(selectChecked1, $event)"
                                        ></v-select>
                                </v-col>
                                <v-col>
                                        <v-select
                                            
                                            label="Checked By"
                                            :value="selectChecked2"
                                            :items="userPrepared.checked2"
                                            name="checked2"
                                            :item-text="item => `${item.name} - ${item.position}`"
                                            
                                            return-object
                                            @change="selectedrecord(selectChecked2, $event)"
                                        ></v-select>
                                </v-col>
                            </v-row>
                        </div>           
                    </v-card>
				</v-col>
			</v-row>
		</v-container>
	</v-app>
</template>

<script>
    export default {
        data() {
            return {
               
               
                search: '',
                assigns:[
                    'computed',
                    'checked',
                    'prepared'
                ],
              
                checkbox1: 1,
                checkbox2: 0,
                userPrepared: {
                    computed: [],
                    checked1: [],
                    checked2: [],
                    prepared: [],
                },
                selectComputed:null,
                selectChecked1:null,
                selectChecked2:null,
                selectPrepared:null,
                checks: []
            }
        },
        methods: {
            selectedrecord(val, event){
                console.log('old',val);
                console.log('new',event);
               axios.put(`api/selected/${val?.id}`, {
                   new: event.id
               }).then((data)=>{
                    Toast.fire({
                        icon: 'success',
                        title: 'User Created in successfully'
                        })
                  
               });
            },
           
        }
    }
</script>
