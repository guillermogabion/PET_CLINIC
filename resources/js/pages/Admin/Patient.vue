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
                    <v-toolbar-title>Pet Owner Record</v-toolbar-title>
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
          v-model="payload.client_name"
          filled
          label="Owner Name"
          class="pa-2"
         >

         </v-text-field>
         <v-text-field
          v-model="payload.contact"
          filled
          label="Contact"
          class="pa-2"
         >

         </v-text-field>
         <v-text-field
          v-model="payload.address"
          filled
          label="Address"
          class="pa-2"
         >

         </v-text-field>
        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn color="primary" class="pa-2" @click=" !payload.id ? submit() : submitEdit() ">
            Submit
          </v-btn>
        </v-card-actions>
       
      </v-card>
    </v-dialog>
    <v-dialog
        v-model="petDialog"
        max-width="700px"
        >
        <v-card>
          <v-card-title>
          Add Pet

          </v-card-title>
          <v-text-field v-model="payload.id" style="display:none" ></v-text-field>

          <v-container>
           <div v-for="(petPayloads, index) in petPayload" :key="index">
           
              <label>Pet {{(index + 1)}}</label>
                  <div class="row">
                    <div class="col-lg-6">
                      <v-text-field v-model="petPayloads.pet_name" :label="'Name of Pet'"></v-text-field>
                      <v-text-field v-model="petPayloads.specie" :label="'Specie'"></v-text-field>
                      <v-text-field v-model="petPayloads.sex" :label="'Gender'"></v-text-field>
                    </div>
                    <div class="col-lg-6">
                      <v-text-field v-model="petPayloads.age" :label="'Age'"></v-text-field>
                      <v-text-field v-model="petPayloads.breed" :label="'Breed'"></v-text-field>
                      <v-text-field v-model="petPayloads.color" :label="'Color'"></v-text-field>
                    </div>
                  </div>
                    
                    
                    <v-btn @click="removeField(index)" class="mb-2">
                      <v-icon>mdi-minus</v-icon>
                    </v-btn>
          </div>
              <v-btn text elevation="0" @click="addField()" :disabled="payload.length >= 10"> 
                <v-icon dark>
                  mdi-plus
                </v-icon>
              </v-btn>
              <v-btn color="primary" @click="petSubmit()" :disabled="payload.length == 0">Submit</v-btn>
          </v-container>
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
        client_name: '',
        address: '',
        contact : ''
      },
      petPayload : [],
      items: [],
      headers: [
        { text: "No", value: "id" },
        { text: "Pet Owner Name", value: "client_name" },
        { text: "Address", value: "address" },
        { text: "Contact", value: "contact" },
        { text: "Action", value: "action" }
     
      ]

    };
  },

  mounted () {
    this.display()
    this.getAll()
  },

  created() {
  // Get unique list of subjects from students data property
  const subjects = new Set();

  // Move this loop to the 'display' method to ensure that the students data is populated before creating headers
  for (let i = 0; i < this.students.length; i++) {
    const record = this.students[i].record;
    for (let j = 0; j < record.length; j++) {
      const subject = record[j].course_code;
      subjects.add(subject);
    }
  }

  // Generate headers array with one column per subject
  const maxSubjects = this.getMaxSubjects();
  // Slice the unique subjects array to include only up to maxSubjects, then map it to an array of header objects
  const subjectHeaders = Array.from(subjects).slice(0, maxSubjects).map((subject, index) => {
  const headerObj = {};
  headerObj[`subject_${index + 1}`] = subject;
  headerObj[`unit_${index + 1}`] = '';
  return headerObj;
});


  // Append the subject headers to the existing headers array
  this.headers = [...this.headers, ...subjectHeaders];
},



  methods: {

    exportEnroll() {
        axios.post('/export', { year: this.payload.year }, {
            responseType: 'blob', // Set the response type to blob
        }).then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data])); // Create a temporary URL for the blob data
            const link = document.createElement('a'); // Create a new anchor element
            link.href = url; // Set the href attribute to the temporary URL
            link.setAttribute('download', 'filename.pdf'); // Set the download attribute to the file name
            document.body.appendChild(link); // Append the anchor element to the DOM
            link.click(); // Simulate a click event to trigger the download
            link.remove(); // Remove the anchor element from the DOM
          this.enrollmentDialog = false
        }).catch(error => {
            console.log(error); // Handle any errors
        });
    },
    exportByProgram() {
        axios.post('/exportbyprogram', { year: this.payload.year, program : this.payload.program }, {
            responseType: 'blob', // Set the response type to blob
        }).then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data])); // Create a temporary URL for the blob data
            const link = document.createElement('a'); // Create a new anchor element
            link.href = url; // Set the href attribute to the temporary URL
            link.setAttribute('download', 'filename.pdf'); // Set the download attribute to the file name
            document.body.appendChild(link); // Append the anchor element to the DOM
            link.click(); // Simulate a click event to trigger the download
            link.remove(); // Remove the anchor element from the DOM
          this.enrollmentDialog = false
        }).catch(error => {
            console.log(error); // Handle any errors
        });
    },
    display() {
      axios.get('display-subjects').then(response => {
        console.log(response.data);
        // Populate the students data property with the response data
        this.students = response.data;

        // Get unique list of subjects from students data property
        const subjects = new Set();
        for (let i = 0; i < this.students.length; i++) {
          const record = this.students[i].record;
         for (let j = 0; j < record.length; j++) {
            const subject = record[j].course_code;
            const unit = record[j].unit;
            subjects.add(subject);

          }
        }

        // Generate headers array with one column per subject
        const maxSubjects = this.getMaxSubjects();
        const subjectHeaders = [];
        for (let i = 0; i < maxSubjects; i++) {
          subjectHeaders.push({
            text: `Course Code ${i + 1}`,
            value: `subject_${i + 1}`
          });
          subjectHeaders.push({
            text: `Unit ${i + 1}`,
            value: `unit_${i + 1}`
          });
        }

        // Append the subject headers to the existing headers array
        this.headers = [...this.headers, ...subjectHeaders];
      }) 
    },

    getMaxSubjects() {
      let maxSubjects = 0;
      for (let i = 0; i < this.students.length; i++) {
        const student = this.students[i];
        if (student.record.length > maxSubjects) {
          maxSubjects = student.record.length;
        }
      }
      return maxSubjects;
    },
    submit() {
      axios.post('storeClient', this.payload).then(res => {
        console.log(res.data)
        this.dialog = false
        this.getAll()
      })
    },
    submitEdit() {
      axios.put('editClient/' + `${this.payload.id}`, this.payload).then(res => {
        console.log(res.data)
        this.dialog = false
        this.getAll()
      })
    },


    getAll(){
      axios.get('getClient').then(res => {
        this.items = res.data
      })
    },
     edit (item) {
        this.editedIndex = this.items.indexOf(item)
        this.payload = Object.assign({}, item)
        this.dialog = true
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
