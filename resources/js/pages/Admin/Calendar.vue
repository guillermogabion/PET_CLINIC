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
                label="Address"
                class="pa-2"
                >

                </v-text-field>
                <v-text-field
                type="date"
                v-model="payload.start"
                filled
                label="Address"
                class="pa-2"
                >

                </v-text-field>
                
                <v-text-field
                type="date"
                v-model="payload.end"
                filled
                label="Address"
                class="pa-2"
                >

                </v-text-field>
                
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" class="pa-2" @click="addEvent()">
                    Submit
                </v-btn>
                </v-card-actions>
            
            </v-card>
            </v-dialog>
    </v-card>
    <v-card>
    <v-col>
      <v-sheet height="400">
        <v-calendar
          ref="calendar"
          :now="today"
          :value="today"
          :events="events"
          color="primary"
          type="month"
            @click:event="showEventDialog"
        ></v-calendar>
      </v-sheet>
      <v-dialog v-model="dialogVisible" max-width="500px">
  <v-card>
    <v-card-title>
      Schedule Details
    </v-card-title>
    <v-card-text>
      <p>{{ selectedEvent.name }}</p>
      <p>{{ selectedEvent.start }} - {{ selectedEvent.end }}</p>
      <p>{{ selectedEvent.description }}</p>
      <p>{{ selectedEvent.contact }}</p>
      <!-- Add more event details here as needed -->
    </v-card-text>
    <v-card-text>
      <v-textarea
        solo
        name="input-7-4"
        label="Solo textarea"
        auto-grow
        full-width
        v-model="selectedEvent.message"
      ></v-textarea>
    </v-card-text>
    <v-card-actions>
      <v-btn color="primary" text @click="sendmessage()">Send Message</v-btn>
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
            })
        },

       fetchEvents() {
        axios.get('get-schedule')
            .then(response => {
            // const events = response.data;
            this.events = response.data;
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
          })
        }


      

        
    //   updateRange ({ start, end }) {
    //     const events = []

    //     const min = new Date(`${start.date}T00:00:00`)
    //     const max = new Date(`${end.date}T23:59:59`)
    //     const days = (max.getTime() - min.getTime()) / 86400000
    //     const eventCount = this.rnd(days, days + 20)

    //     for (let i = 0; i < eventCount; i++) {
    //       const allDay = this.rnd(0, 3) === 0
    //       const firstTimestamp = this.rnd(min.getTime(), max.getTime())
    //       const first = new Date(firstTimestamp - (firstTimestamp % 900000))
    //       const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
    //       const second = new Date(first.getTime() + secondTimestamp)

    //       events.push({
    //         name: this.names[this.rnd(0, this.names.length - 1)],
    //         start: first,
    //         end: second,
    //         color: this.colors[this.rnd(0, this.colors.length - 1)],
    //         timed: !allDay,
    //       })
    //     }

    //     this.events = events
    //   },
    },
  }
</script>