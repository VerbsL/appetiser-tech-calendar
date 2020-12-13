/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';

import VueToast from 'vue-toast-notification';

import VueMoment from 'vue-moment';

Vue.use(VueToast);

const moment = require('moment')
require('moment/locale/es')

Vue.use(require('vue-moment'), {
    moment
});

import 'vue-toast-notification/dist/theme-sugar.css';

Vue.component('calendar-component', require('./components/CalendarComponent.vue').default);

const app = new Vue({
    el: '#app',
});
