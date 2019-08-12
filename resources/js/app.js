require('./bootstrap');
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
            //check if user is scrolling up
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


window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

Vue.component('Task', require('./components/TaskComponent.vue').default);
Vue.component('OrderComponent', require('./components/OrderComponent.vue').default);
const app = new Vue({
    el: '#app',
    components: {

    }
});
