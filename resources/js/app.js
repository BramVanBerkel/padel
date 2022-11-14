import './bootstrap';
import Alpine from 'alpinejs';
import Datepicker from 'flowbite-datepicker/Datepicker';

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("app", () => ({
        selectedUser: null,
        users: null,
        selectedLocation: null,
        timeslots: [],
        locations: [],
        datePicker: null,
        async init() {
            axios.get('/api/users')
                .then((response) => this.users = response.data.data)

            axios.get('/api/locations')
                .then((response) => this.locations = response.data.data)

            this.datePicker = new Datepicker(document.getElementById('date'), {
                weekStart: 1,
                minDate: new Date()
            });

            // fix shitty flowbite datepicker styling
            document.getElementsByClassName('datepicker-picker').item(0).classList.remove('inline-block');
            document.getElementsByClassName('datepicker-view').item(0).classList.remove('flex');
            document.getElementsByClassName('datepicker-grid').item(0).classList.remove('w-64');
            for(const el of document.getElementsByClassName('cursor-not-allowed')) {
                el.classList.add('line-through')
            }

            this.updateTimeslots()
        },
        updateTimeslots() {
            if (this.selectedLocation === null || this.datePicker.getDate() === undefined) {
                return
            }

            axios.get(`/api/timeslots/${this.selectedLocation}/${this.datePicker.getDate('yyyy-mm-dd')}`)
                .then((response) => this.timeslots = response.data)
        },
        async updateAvailability(timeslot) {
            await axios.post(`/api/timeslots`, {
                'user_id': this.selectedUser,
                'date': this.datePicker.getDate('yyyy-mm-dd'),
                'location': this.selectedLocation,
                'timeslot': timeslot
            })

            this.updateTimeslots()
        },
    }));
});

// for some reason alpine won't catch the changeDate event, so we recast an event to the window, which alpine can catch
document.getElementById('date').addEventListener('changeDate', function(event) {
    window.dispatchEvent(new CustomEvent('update'));
})

Alpine.start();
