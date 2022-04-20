<template>
<v-app>
    <v-card outlined color="transparent vcard">
        	<v-card-title class="custom-color">
                Activity Logs
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    color="#8DA90B"
                    append-icon="mdi-magnify"
                    label="Search"
                    class="mx-4"
                    clearable
                ></v-text-field>
            </v-card-title>
                  <v-data-table
                    :headers="activityHeaders"
                    :items="activity"
                    :search="search"
                    sort-by="calories"
                    class="elevation-1 ma-4 border-0"
                >

                <template v-slot:[`item.date`]="{ item }">
                            <span>{{item.created_at | myDatewithtime}}</span>
                </template>
                <template v-slot:[`item.causer`]="{ item }">
                            <span>{{item.user == null ? "admin" : item.user.name }}</span>
                             <!-- <span>{{item}}</span> -->
                </template>
                <template v-slot:[`item.Description`]="{ item }">
                            <span>{{item.log_name}}
                                {{item.description}}
                                in
                                {{item.subject_type}}
                                on
                                {{item.created_at | myDatewithtime}} </span>
                </template>
                </v-data-table>

    </v-card>
    </v-app>
</template>

<script>
    export default {
        data() {
            return {

                activityHeaders : [
                    { text: 'Date', value: 'date' },
                    { text: 'By User', value: 'causer' },
                    { text: 'Action', value: 'description' },
                    { text: 'Ip Address', value: 'ip_address' },
                    { text: 'Description', value: 'Description' },
                ],
                search: '',
                activity:[],


            }
        },
        methods: {
            loadActivity(){
                axios.get('api/indexActivity').then((data)=>{
                    // console.log(data);
                    this.activity = data.data;
                });
            },

        },
        created() {
            this.loadActivity();
        }
    }
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
        box-shadow: none;
    }
    
</style>