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
                    <v-toolbar-title>Pet Record

                    </v-toolbar-title>
                  
                 <v-spacer></v-spacer>
                     <v-text-field
                      v-model="search"
                      append-icon="mdi-magnify"
                      label="Search"
                      single-line
                      hide-details
                    ></v-text-field>
                    <!-- <v-btn
                    color="primary"
                    dark
                    class="mb-2"
                    v-bind="attrs"
                    v-on="on"
                    @click="dialog=true"
                    >
                    Add New   
                    </v-btn> -->
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
                @click="petDelete(item)"
              >
                mdi-delete
              </v-icon>
               <v-icon
               
                small
                @click="viewRecord(item)"
                 v-if="$is_admin()" 
              >
                mdi-eye
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
        <div class="row">
            <div class="col-lg-6">
                <v-text-field
                    v-model="payload.pet_name"
                    filled
                    label="Pet Name"
                    class="pa-2"
                    >

                    </v-text-field>
                    <v-text-field
                    v-model="payload.specie"
                    filled
                    label="Specie"
                    class="pa-2"
                    >

                    </v-text-field>
                    <v-text-field
                    v-model="payload.breed"
                    filled
                    label="Breed"
                    class="pa-2"
                    >
                    </v-text-field>
            </div>
            <div class="col-lg-6">
                <v-text-field
                v-model="payload.sex"
                filled
                label="Gender"
                class="pa-2"
                >
                </v-text-field>

                <v-text-field
                v-model="payload.age"
                filled
                label="Age"
                class="pa-2"
                >
                </v-text-field>

                <v-text-field
                v-model="payload.color"
                filled
                label="Color"
                class="pa-2"
                >
                </v-text-field>
            </div>
        </div>
        <v-card-actions>
          <v-spacer></v-spacer>
           <v-btn color="primary" class="pa-2" @click="submit()">
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
        <v-dialog
            v-model="viewDialog"
            width="1000"
            persistent
        >
        <v-card
        class="pa-2"
        >
        <v-card-actions>
            <v-spacer></v-spacer>
            <span @click=" viewDialog = false">X</span>
        </v-card-actions>
        <div id="print-content" class="has-print">
            <div class="print-section">
                <p>Animal Profile</p>

                <span>
                    <ul>
                        <li>
                            Name : {{this.payload.pet_name}}
                        </li>
                        <li>
                            Owner: {{this.payload.owner && this.payload.owner.client_name}}
                        </li>
                        <li>
                            Specie: {{this.payload.specie}}
                        </li>
                        <li>
                            Breed: {{this.payload.breed}}
                        </li>
                    </ul>
                </span>
            </div>
            <div>
                <div class="print-section">
                    <span style="font-size: 15px; font-weight: bold;">Medical Records</span>
                </div>
                <div style="padding 10px">
                    <v-data-table
                    :items="payload.record"
                    :search="serch"
                    :headers="pets"
                    hide-default-footer
                    >
                        <template v-slot:top>
                            <v-toolbar
                                flat
                                class="no-print"
                                 v-if="!isPrinting"
                            >
                            
                            <v-spacer></v-spacer>
                                <v-text-field
                                v-model="serch"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                                class="no-print"
                                ></v-text-field>
                                <v-btn
                                color="primary"
                                dark
                                class="mb-2 remove-on-print"
                                v-bind="attrs"
                                v-on="on"
                                @click="recordDialog=true"
                                >
                                Add New Record  
                                </v-btn>
                            </v-toolbar>
                        </template>

                    </v-data-table>
                    
                </div>
            </div>
        </div>
            <v-card-actions>
                <v-spacer></v-spacer>
                    <v-btn
                    color="primary"
                    dark
                    class="mb-2"
                    v-bind="attrs"
                    v-on="on"
                    @click="printPDF()"
                    >
                    Print Medical Records  
                    </v-btn>
                    <v-btn
                    color="primary"
                    dark
                    class="mb-2"
                    v-bind="attrs"
                    v-on="on"
                    @click="printCertAndRedirect()"
                    target="_blank"
                    >
                    Print Medical Certificate
                    </v-btn>
            </v-card-actions>
        </v-card>

        </v-dialog>

        <v-dialog
        v-model="recordDialog"
        width="600"
        >
            <v-card
            class="pa-2"
            >
                <div class="row">
                    <div class="col-lg-6">
                      <v-text-field
                        v-model="recordpayload.service"
                        label="Service"
                      ></v-text-field>  
                      <v-text-field
                        type="date"
                        v-model="recordpayload.datevisitss"
                        label="Date"
                      ></v-text-field>  
                    </div>
                    <div class="col-lg-6">
                        <v-text-field
                        v-model="recordpayload.brand_name"
                        label="Brand (Optional)"
                      ></v-text-field>
                        <v-text-field
                        v-model="recordpayload.duration"
                        label="Duration (Optional)"
                      ></v-text-field>
                    </div>
                    <div class="col-lg-12">
                         <v-textarea
                        outlined
                        name="input-7-4"
                        label="Remarks"
                        value="recordpayload"
                        v-model="recordpayload.remarks"
                        ></v-textarea>
                    </div>
                </div>
                <v-card-actions>
                    <v-btn
                    color="primary"
                    dark
                    class="mb-2"
                    v-bind="attrs"
                    v-on="on"
                    @click="recordAdd()"
                    >
                    Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    
  </div>
</template>

<script>
import axios from '../../plugins/axios'
import html2pdf from 'html2pdf.js';

export default {
  data() {
    return {
        isPrinting: false,
         printOptions: {
        stylesheets: [], // Add your custom CSS file if needed
      },
      recordDialog: false,
      viewDialog: false,
      editedIndex: -1,
      detailDialog: false,
      dialog: false,
      petDialog: false,
      enrollment2Dialog: false,
      search: '',
      serch: '',
      payload: {
        pet_name : '',
        breed : '',
        age: '',
        specie: '',
        color: '',
        sex: '',
      },
      medical_record: [],
      petPayload : [],
      items: [],
      headers: [
        { text: "No", value: "id" },
        { text: "Pet Name", value: "pet_name" },
        { text: "Specie", value: "specie" },
        { text: "Breed", value: "breed" },
        { text: "Owner", value: "owner.client_name" },
        { text: "Age", value: "age" },
        { text: "Color", value: "color" },
        { text: "Gender", value: "sex" },
        { text: "Action", value: "action" }
     
      ],
      pets: [
         { text: "Service", value: "service" },
         { text: "Brand Name", value: "brand_name" },
         { text: "Date", value: "datevisitss" },
         { text: "Duration", value: "duration" },
         { text: "Remarks", value: "remarks" },

      ],
     recordpayload : {
        service : '',
        brand_name : '',
        datevisitss: '',
        duration: '',
        remarks: '',
     }
    };
  },

  mounted () {
    this.display()
    this.getAll()
    this.recordGet()
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
      axios.put('edit-pet/' + `${this.payload.id}`, this.payload).then(res => {
        console.log(res.data)
        this.dialog = false
        this.getAll()
      })
    },
    getAll(){
      axios.get('getPet').then(res => {
        this.items = res.data
      })
    },
     edit (item) {
        this.editedIndex = this.items.indexOf(item)
        this.payload = Object.assign({}, item)
        this.dialog = true
      },
     viewRecord (item) {
        this.editedIndex = this.items.indexOf(item)
        this.payload = Object.assign({}, item)
        this.viewDialog = true
      },

   petSubmit() {
   
    axios.post('edit-pet/' + `${payload.id}`, this.payload)
      .then(res => {
        console.log(res);
        alert(this.payload.client_name + ' Pet Updated');
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
    },
    petDelete(item) {
        axios.delete('delete-pet/' + item.id).then(res => {
            this.getAll();
        });
    },

    recordAdd(){
        this.recordpayload.pet_id = this.payload.id
        axios.post('add-record', this.recordpayload).then(res => {
            console.log(res)
            this.recordDialog = false
        })
    },
    
    recordGet() {
        axios.get('get-record', this.payload.id).then(res => {
            console.log(res)
            this.medical_record = res.data
        })
    },
    printPDF() {
         this.isPrinting = true;

           setTimeout(() => {
        const element = document.getElementById('print-content');
          const opt = {
            margin: [0, 0, 0, 0],
            filename: 'animal_profile.pdf',
            image: {
              type: 'jpeg',
              quality: 1
            },
            html2canvas: {
              scale: 2,
              logging: false
            },
            jsPDF: {
              unit: 'mm',
              format: 'a4',
              orientation: 'portrait'
            }
          };
          html2pdf().set(opt).from(element).save();
            this.isPrinting = false;
      }, 100);
    },

    test(){
        console.log(this.payload.id)

        axios.get('cert/'+`${this.payload.id}`).then(res => {
            console.log(res.data)
        })
    },

    printCertAndRedirect() {
        this.test()
        window.location.href = 'http://127.0.0.1:8000/admin/v1/cert/'+`${this.payload.id}`;
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
      'this.payload.id': function(newVal, oldVal) {
    if (newVal !== oldVal) {
        this.recordGet()
      // Value of payload.id has changed
      // Perform your desired actions here
      
      // Example: Close the dialog if payload.id becomes falsy
      if (!newVal) {
        this.close();
      }
    }
  }
   
    },



};
</script>
<style scoped>
.no-print {
  display: block;
}
.has-print {
    display: block;
}
@media print {
 
 .has-print {
  width: 50%; /* Set the width to 100% for a wider view */
  padding: 20px; /* Add padding to the content for better spacing */
  box-sizing: border-box; /* Include padding in the width calculation */
}
 .no-print {
    display: none;
  }
}
 
</style>