require('./bootstrap');
require('./libs/jquery.doubleScroll.js');
// Show the navbar when the page is scrolled up
let MQL = 592;
if ($(window).width() > MQL) {
    var headerHeight = $('#mainNav').height();
    var headerHeight = 300;
    $(window).on('scroll', {
            previousTop: 0
        },
        function () {
            var currentTop = $(window).scrollTop();
            if (currentTop >= 100) {
                $('#mainNav').addClass('is-fixed is-visible');
            } else {
                $('#mainNav').removeClass('is-fixed is-visible');
            }

            /*
            if (currentTop < this.previousTop) {
                //if scrolling up...
                if (currentTop > 0 && $('#mainNav').hasClass('is-fixed')) {
                    $('#mainNav').addClass('is-visible');
                } else {
                    $('#mainNav').removeClass('is-visible is-fixed');
                }
            } else if (currentTop > this.previousTop) {
                //if scrolling down...
                $('#mainNav').removeClass('is-visible');
                if (currentTop > headerHeight && !$('#mainNav').hasClass('is-fixed')) $('#mainNav').addClass('is-fixed');
            }
            this.previousTop = currentTop;
            */
        });
}
// Show the navbar when the page is scrolled up

window.Vue = require('vue');

// ******* axios start
import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios);
// ******* axios end

// ******* tooltip start
import VTooltip from 'v-tooltip'
Vue.use(VTooltip)
VTooltip.options.defaultClass = 'my-tooltip'
// ******* tooltip end

// ******* loader  start
import 'vue-loaders/dist/vue-loaders.css'
import VueLoaders from 'vue-loaders'
Vue.use(VueLoaders)
// ******* loader end

// ******* alert 
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);
// ******* alert end

// ******* backtotop
import BackToTop from 'vue-backtotop'
Vue.use(BackToTop)
// ******* backtotop end

// ******* copy
import VueClipboard from 'vue-clipboard2'
Vue.use(VueClipboard)
// ******* copy end

//**********************filter
Vue.filter('formatDate', function (value) {
    if (value) {
        let date = new Date(value * 1000);
        // let date = new Date(value);
        let day = date.getDate();
        if (day < 10) {
            day = '0' + day;
        }
        let month = date.getMonth() + 1;
        if (month < 10) {
            month = '0' + month;
        }
        return day + '.' + month + '.' + date.getFullYear();
    }
});
//**********************filter end


window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

Vue.component('TasklistuserComponent', require('./components/TasklistuserComponent.vue').default);
Vue.component('TaskreadComponent', require('./components/TaskreadComponent.vue').default);
Vue.component('TasklistComponent', require('./components/TasklistComponent.vue').default);
Vue.component('UserorderComponent', require('./components/UserorderComponent.vue').default);

Vue.component('TasksettingComponent', require('./components/TasksettingComponent.vue').default);
Vue.component('SidebarAdmin', require('./components/sidebar/SidebarAdmin.vue').default);
Vue.component('SavedComponent', require('./components/other/SavedComponent.vue').default);
const app = new Vue({
    el: '#app',
    components: {

    }
});
