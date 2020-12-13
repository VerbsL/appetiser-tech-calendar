<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <br>
                <form @submit.prevent>
                    <div class="form-group">
                        <label for="event_name">Event Name</label>
                        <input type="text" id="title" class="form-control" v-model="newEvent.title" >
                    </div>
                    <div class="form-group">
                        <div class="col-md=6">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" id="start" class="form-control" v-model="newEvent.start" @change="onChangeDate()">
                            </div>
                        </div>
                        <div class="col-md=6">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end" class="form-control" v-model="newEvent.end" @change="onChangeDate()">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                          <label v-for="(day,index) in days" style="width: 50px">
                            <input class="weekdays" name="weekdays" v-model="day.selected" type="checkbox" @change="OnChangeDays(index)"> {{ day.day }}
                          </label>
                    </div>
                    <div class="col-md-6 mb-4">
                        <button class="btn btn-primary" @click="addNewEvent">Save Event</button>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <FullCalendar :options="calendarOptions"/>
            </div>
        </div>
    </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import axios from "axios";

export default {
    components: {
        FullCalendar
    },
    data() {    
        return {
            calendarOptions: {
                plugins: [ dayGridPlugin, interactionPlugin ],
                initialView: 'dayGridMonth',
                events:  [],
                buttonText: {
                    today: "Today"
                },
                headerToolbar: {
                left: 'title',
                center: '',
                right: 'prev,next today'
                },
            },
            newEvent: {
                title: "",
                start: "",
                end: "",
                cond: ""
            },
            daysSelected:[0,1,2,3,4,5,6],
            eventsData: [],
            validEntry: true,
            days: [
              {selected: true, day: 'Sun'},
              {selected: true, day: 'Mon'},
              {selected: true, day: 'Tue'},
              {selected: true, day: 'Wed'},
              {selected: true, day: 'Thu'},
              {selected: true, day: 'Fri'},
              {selected: true, day: 'Sat'},
            ],
        }
    },
    created() {
        this.getEvents();
    },
    methods: {
        async getEvents() {
            await axios
                .get("/api/calendar")
                .then(response => {
                    this.eventsData = response.data.data;
                })
                .catch(error => {
                    reject(error);
                });
            this.displayEvents();
        },
        displayEvents(){
            let daylist = [];
            let tempEnd = "";

            this.eventsData.forEach((value, index) => {
                let startDate = value.start;
                let endDate = value.end;
                let daysCond = [value.cond]

                while (startDate <= endDate) {

                    tempEnd = this.$moment(startDate).add(1, 'days').format("YYYY-MM-DD");
                    if(value.cond.includes(this.$moment(startDate).day())){
                        daylist.push({'title' : value.title, 'start' : startDate, 'end' : tempEnd});
                    }        
                    startDate = tempEnd;
                }
            });

            this.calendarOptions.events = daylist;
        },

        addNewEvent() {
            if(!this.newEvent.title){
                this.errorAlert();
            } else if(!this.newEvent.start){
                this.errorAlert();
            } else if(!this.newEvent.end){
                this.errorAlert();
            } else {
                if(this.validEntry){

                    if(this.daysSelected.length > 0){
                        this.newEvent.cond = this.daysSelected;
                    }
                    axios
                        .post("api/calendar", {
                            ...this.newEvent
                        })
                        .then(data => {
                            this.getEvents();
                            this.resetForm();
                            this.successAlert();
                    });
                } else {
                    this.dateAlert();
                }
                
            }
        },
        resetForm() {
            Object.keys(this.newEvent).forEach(key => {
                return (this.newEvent[key] = "");
            });
        },
        successAlert() {
            this.$toast.open({
                message: "Calendar Events successfully created!",
                type: "success",
                duration: 5000,
                dismissible: true,
                position: "top-right"
              })
        },
        errorAlert() {
            this.$toast.open({
                message: 'Oops something went wrong! Please check your data and try again.',
                type: "warning",
                duration: 5000,
                dismissible: true,
                position: "top-right"
            })
        },
        dateAlert() {
            this.$toast.open({
                message: 'Start date cannot be ahead of End date!',
                type: "warning",
                duration: 5000,
                dismissible: true,
                position: "top-right"
            })
        },
        onChangeDate(){
            if(this.newEvent.start && this.newEvent.end) {
                if(this.newEvent.start > this.newEvent.end){
                    this.validEntry = false;
                    this.dateAlert();
                } else {
                    this.validEntry = true;
                }
            }
        },
        OnChangeDays(index) {
            if(this.days[index].selected){
                this.daysSelected.push(index);
            } else {
                this.daysSelected.splice(index, 1);
            }
        },
    },
    mounted() {
    
    },
    beforeMount() {
        
    }
}

</script>
