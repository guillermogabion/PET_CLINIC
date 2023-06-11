<template>
  <div>
    <div>
        <v-data-table 
        :headers="headers" 
        :items="items"
        :search="search"
        >
            <template v-slot:top>
                <v-toolbar
                flat
                >
                    <v-toolbar-title>Staff</v-toolbar-title>
                 <v-spacer></v-spacer>
                     <v-text-field
                      v-model="search"
                      append-icon="mdi-magnify"
                      label="Search"
                      single-line
                      hide-details
                    ></v-text-field>
                    <v-btn
                    color="primary"
                    dark
                    class="mb-2"
                    v-bind="attrs"
                    v-on="on"
                    @click="dialog=true"
                    >
                    Add New   
                    </v-btn>
                </v-toolbar>
            </template>

            <template v-slot:item.action="{ item }">
              <v-icon
                small
                class="mr-2"
                @click="edit(item)"
              >
                mdi-pencil
              </v-icon>
              <v-icon
                small
                @click="petAdd(item)"
              >
                mdi-plus
              </v-icon>
             
            </template>
            <!-- <template v-slot:item.is_active="{ item }">
             
            </template> -->
             <template v-slot:item.is_active="{ item }">
            <v-switch v-model="item.is_active" @click="status(item)"></v-switch>
            </template>
           
        </v-data-table>
    </div>
    <!-- <v-btn color="primary" class="pa-2" @click="enrollmentDialog = true">
      Generate Enrollment List
    </v-btn>
    <v-btn color="primary" class="pa-2" @click="enrollment2Dialog = true">
      Generate Enrollment List(By Program)
    </v-btn>
     -->
    <!-- <v-dialog
      v-model="enrollmentDialog"
      width="400px"
    >
      <v-card>
        <v-card-title>Generate Enrollment List</v-card-title>
         <v-select
          :items="items"
          filled
          label="Select Year"
          class="pa-2"
          v-model="payload.year"
        ></v-select>
        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn color="primary" class="pa-2" @click="exportEnroll()">
            Generate
          </v-btn>
        </v-card-actions>
       
      </v-card>
    </v-dialog>
    -->
    <v-dialog
      v-model="dialog"
      width="400px"
    > 
      <v-card>
        <v-card-title>{{ formTitle }}</v-card-title>
        
         <v-text-field
          v-model="payload.last_name"
          filled
          label="Last Name"
          class="pa-2"
         >

         </v-text-field>
         <v-text-field
          v-model="payload.first_name"
          filled
          label="First Name"
          class="pa-2"
         >

         </v-text-field>
         <v-text-field
          v-model="payload.email"
          filled
          label="Username"
          class="pa-2"
         >

         </v-text-field>
         <v-text-field
          v-model="payload.password"
          filled
          label="Password"
          class="pa-2"
          type="password"
         >

         </v-text-field>
        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn color="primary" class="pa-2" @click=" !payload.id ? save() : editSave() " :disabled="!payload || !payload.last_name || !payload.first_name || !payload.email || !payload.password || payload.last_name.trim().length === 0 || payload.first_name.trim().length === 0 || payload.email.trim().length === 0 || payload.password.trim().length === 0 ">
            Submit
          </v-btn>
        </v-card-actions>
       
      </v-card>
    </v-dialog>
   
    
  </div>
</template>

<script>
import axios from '../../plugins/axios'
export default {
  data() {
    return {
      editedIndex: -1,
      detailDialog: false,
      dialog: false,
      petDialog: false,
      enrollment2Dialog: false,
      search: '',
      payload: {
        last_name: '',
        first_name: '',
        email : '',
        password : '',
        is_active: ''
      },
      switch1 : false,
      petPayload : [],
      items: [],
      headers: [
        { text: "No", value: "id" },
        { text: "Last Name", value: "last_name" },
        { text: "First Name", value: "first_name" },
        { text: "Position", value: "position" },
        { text: "Username", value: "email" },
        { text: "Status", value: "is_active" },
        { text: "Action", value: "action" }
     
      ]

    };
  },

  mounted () {
    this.display()
    this.getAll()
  },


  methods: {
    display(){
      axios.get('getallUser').then(res => {
        this.items = res.data
      })
    },
     edit (item) {
        this.editedIndex = this.items.indexOf(item)
        this.payload = Object.assign({}, item)
        this.dialog = true
      },

      save() {
        axios.post('addUser', this.payload).then(res => {
            console.log(res)
            this.dialog = false
            this.display()
        })
      },
      editSave() {
        axios.put('editUser/'+this.payload.id, this.payload).then(res => {
            console.log(res)
            this.dialog = false
            this.display()
        })
      },

      status(){
         axios.put('status/'+item.id).then(res => {
            console.log(res)
            this.display()
        })
      },

   petSubmit() {
    const payloads = this.petPayload.map((item) => {
      return {
        patient_id: this.payload.id,
        pet_name: item.pet_name,
        specie: item.specie,
        sex: item.sex,
        age: item.age,
        breed: item.breed,
        color: item.color
      };
    });

    console.log(payloads);

    axios.post('insert-pet', payloads)
      .then(res => {
        console.log(res);
        alert(this.payload.client_name + ' Pet Added');
        this.petDialog = false;
        this.petPayload = []
        this.payload.id = ''
      })
      .catch(error => {
        console.error(error);
      });
  },

    addField() {
      this.petPayload.push({
        pet_name: '',
        specie : '',
        sex : '',
        age: '',
        breed: '',
        color : ''
    })
    },
      removeField(index) {
    this.payload.splice(index, 1)
    },

    petAdd(item) {
      this.editedIndex = this.items.indexOf(item)
      this.payload = Object.assign({}, item)
      this.petDialog = true
    }

  },
  

  computed: {

    areAllFieldsFilled() {
      for (const petPayloads of this.petPayload) {
        if (
          !petPayloads.pet_name ||
          !petPayloads.specie ||
          !petPayloads.sex ||
          !petPayloads.age ||
          !petPayloads.breed ||
          !petPayloads.color
        ) {
          return false;
        }
      }
      return true;
    },

  studentRows() {
    const rows = [];
    const numCols = this.headers.length;
    for (let i = 0; i < this.students.length; i++) {
      const student = this.students[i];
      const row = { stud_no: student.stud_no , last_name: student.last_name, first_name: student.first_name, mid_name: student.mid_name, suffix: student.suffix, sex: student.sex };
       let totalUnits = 0;
      for (let j = 1; j <= student.record.length; j++) {
  const subject = student.record[j-1];
  row[`subject_${j}`] = subject.course_code;
  row[`unit_${j}`] = subject.unit;
  totalUnits += subject.unit;
}
  row['total_units'] = totalUnits;

      rows.push(row);
    }
    return rows;
  },

   formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },

      close() {
        this.dialog = false;
        this.petDialog = false;
        this.$nextTick(() => {
          this.petPayload = []; // Reset the petPayload array
         this.payload = {
            client_name: "",
            address: "",
            contact: "",
            id: ""
          }; // Reset the payload object
          this.editedIndex = -1;
        });
      }
},

watch: {
      dialog (val) {
        val || this.close()
      },
      petDialog (val) {
        val || this.close()
      },
   
    },



};
</script>
