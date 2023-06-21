<template>
<div>
    <v-card
    elevation="0"
    >
        <v-card-actions>
             <v-spacer></v-spacer>
            <v-btn
            color="primary"
            dark
            class="mb-2"
            v-bind="attrs"
            v-on="on"

            @click="dialog = true"
            >
            New Item
            </v-btn>
        </v-card-actions>
         <v-dialog
            v-model="dialog"
            width="400px"
            > 
            <v-card>
                <v-card-title>{{ formTitle }}</v-card-title>
                
                <v-text-field
                v-model="payload.name"
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
                v-model="payload.description"
                filled
                label="Purpose"
                class="pa-2"
                >

                </v-text-field>
                <v-text-field
                type="date"
                v-model="payload.start"
                filled
                label="Date of Visit"
                class="pa-2"
                >

                </v-text-field>
                
              
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" class="pa-2" @click="addEvent()" :disabled="!areAllFieldsFilled">
                    Submit
                </v-btn>
                </v-card-actions>
            
            </v-card>
            </v-dialog>
    </v-card>
    <v-card>
   
    <v-col>
      <v-sheet height="400">
        <div class="custom-calendar">
          <v-btn icon @click="previousMonth">
        <v-icon>mdi-chevron-left</v-icon>
      </v-btn>
        
       <v-btn icon @click="nextMonth">
        <v-icon>mdi-chevron-right</v-icon>
      </v-btn>
      <v-toolbar style="margin-bottom: 15px">
         <v-toolbar-title v-if="$refs.calendar">
            {{ $refs.calendar.title }}
          </v-toolbar-title>
      </v-toolbar>
          <v-calendar
           ref="calendar"
          v-model="selectedDate"
          :now="today"
          :value="today"
          :events="events"
          color="primary"
          type="month"
          @click:event="showEventDialog"
          :day-mode="'stack'"
          :day-height="50"
          >
            <template #event="{ event }">
              <div class="v-event">
                {{ event.name }}
              </div>
            </template>
          </v-calendar>
           
        </div>
        
      </v-sheet>

      <v-dialog v-model="dialogVisible" max-width="500px">
  <v-card>
    <v-card-title>
      Schedule Details
    </v-card-title>
    <v-card-text>
      <p>{{ selectedEvent.name }}</p>
      <p>{{ selectedEvent.start }}</p>
      <p>{{ selectedEvent.description }}</p>
      <p>{{ selectedEvent.contact }}</p>
      <!-- Add more event details here as needed -->
    </v-card-text>
    <v-card-text>
      <v-textarea
        solo
        name="input-7-4"
        label="Please type a  message"
        auto-grow
        full-width
        v-model="selectedEvent.message"
      ></v-textarea>
    </v-card-text>
    <v-card-actions>
      <!-- <v-btn color="primary" :disabled="selectedEvent.message.trim().length === 0" @click="sendmessage()">Send Message</v-btn> -->
      <v-btn color="primary" text @click="sendmessage()" :disabled="!selectedEvent || !selectedEvent.message || selectedEvent.message.trim().length === 0">Send Message</v-btn>
      <v-btn color="primary" text @click="closeEventDialog">Close</v-btn>
    </v-card-actions>
  </v-card>
</v-dialog>
    </v-col>
    </v-card>
  </div>
</template>
<script>
import axios from '../../plugins/axios'
  export default {
    data: () => ({
       selectedDate: new Date(),
        type: 'month',
        typeToLabel: {
          month: 'Month',
          week: 'Week',
          day: 'Day',
          '4day': '4 Days',
        },
        dialogVisible: false,
        selectedEvent: null,
        payload: {
         name: '',
         contact: '',
         description: '',
         start: '',
         end: '',
        },
        dialog: false,
      focus: '',
      type: 'month',
      typeToLabel: {
        month: 'Month',
        week: 'Week',
        day: 'Day',
        '4day': '4 Days',
      },
      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
      events: [],
      colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
      names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
    }),
    mounted () {
      this.fetchEvents()
    },
     
    methods: {
      viewDay ({ date }) {
        this.focus = date
        this.type = 'day'
      },
      getEventColor (event) {
        return event.color
      },
      setToday () {
        this.focus = ''
      },
      prev () {
        this.$refs.calendar.prev()
      },
      next () {
        this.$refs.calendar.next()
      },
      showEvent ({ nativeEvent, event }) {
        const open = () => {
          this.selectedEvent = event
          this.selectedElement = nativeEvent.target
          requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
        }

        if (this.selectedOpen) {
          this.selectedOpen = false
          requestAnimationFrame(() => requestAnimationFrame(() => open()))
        } else {
          open()
        }

        nativeEvent.stopPropagation()
      },
        addEvent() {
            axios.post('add-schedule', this.payload).then(res => {
                console.log(res.data)
                this.dialog = false
            })
        },

       fetchEvents() {
        axios.get('get-schedule')
          .then(response => {
            const events = response.data.map(event => ({
              name: event.name,
              start: event.start,
              end: new Date(event.end),
              color: event.color,
              timed: true, // Adjust this based on your event data structure
            }));
            this.events = events;
          })
          .catch(error => {
            console.error('Error fetching events:', error);
          });
      },
        showEventDialog({event}) {

        console.log(event)
        this.selectedEvent = event;
        this.dialogVisible = true;
        },
        closeEventDialog() {
        this.selectedEvent = null;
        this.dialogVisible = false;
        },
        sendmessage(){
          axios.post('sendSms', this.selectedEvent).then(res=> {
            console.log(res)
            this.dialogVisible = false
            this.fetchEvents()

            
          })
        },
    previousMonth() {
      this.selectedDate = new Date(this.selectedDate.getFullYear(), this.selectedDate.getMonth() - 1, 1);
    },

    nextMonth() {
      this.selectedDate = new Date(this.selectedDate.getFullYear(), this.selectedDate.getMonth() + 1, 1);
    },
    },
    computed: {
      areAllFieldsFilled() {
        return this.payload.name && this.payload.contact;
      }
    },
   
  }
</script>

<style>
.custom-calendar .v-event-group .v-event-group__stacked-events {
  display: flex;
  align-items: center;
  justify-content: center;
  height: auto;
  padding: 2px;
}

.custom-calendar .v-event-group .v-event-group__stacked-events .v-event {
  margin: 2px;
}

.custom-calendar .v-event-group .v-event-group__stacked-events .v-event::before {
  content: '+';
  font-weight: bold;
}

.custom-calendar .v-event-group .v-event-group__stacked-events:hover {
  background-color: rgba(0, 0, 0, 0.1);
  cursor: pointer;
}

.custom-calendar .v-event-group .v-event-group__stacked-events:hover .v-event {
  background-color: rgba(0, 0, 0, 0.2);
  color: white;
}
</style>
