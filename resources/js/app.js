require('./bootstrap');
require('./common');
// require('./libs/jquery.doubleScroll.js');
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

// визуальный редактор

import wysiwyg from "vue-wysiwyg";
import "vue-wysiwyg/dist/vueWysiwyg.css";
Vue.use(wysiwyg, {
    hideModules: { "image": true },
});

// ******* loader  start
import 'vue-loaders/dist/vue-loaders.css'
import VueLoaders from 'vue-loaders'
Vue.use(VueLoaders)
// ******* loader end

// ******* alert
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2)
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

export const eventBus = new Vue();

// миксин
import GlobalMixin from './mixin/mixin'
Vue.mixin(GlobalMixin);

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

Vue.component('TaskreadAdmin', require('./components/TaskreadAdmin.vue').default);

// Vue.component('TasklistComponent', require('./components/TasklistAdmin.vue').default);

// задания пользователя с датой
Vue.component('TasklistuserComponent', require('./components/TasklistUsert.vue').default);

// задания пользователя  свободные
Vue.component('TasklistuserFree', require('./components/TasklistuserFree.vue').default);

//работник заказы
Vue.component('UserorderComponent', require('./components/UserOder.vue').default);
// работник история
Vue.component('HistoryOrders', require('./components/HistoryOrders.vue').default);

Vue.component('TasksettingComponent', require('./components/TasksettingComponent.vue').default);
Vue.component('SidebarAdmin', require('./components/sidebar/SidebarAdmin.vue').default);

Vue.component('SavedComponent', require('./components/other/SavedComponent.vue').default);

//главная страница админа
Vue.component('DashbordAdmin', require('./components/dashbord/DashbordAdmin.vue').default);

Vue.component('ReadOrder', require('./components/order/ReadOrder.vue').default);

// короткий серийник
Vue.component('ShortSerial', require('./components/serial/ShortSerial.vue').default);

// короткий серийник
Vue.component('SerialLink', require('./components/other/SerialLink.vue').default);


// обновить статус заданию
Vue.component('AdminUpdate', require('./components/task/AdminUpdate.vue').default);



Vue.component('AdminLogtaskother', require('./components/task/AdminLogtaskother.vue').default);


//************************ task ***********************************************************
// лог одиночной записи
Vue.component('AdminLogtask', require('./components/task/AdminLogtask.vue').default);
// кнопка изменить статус для админа
Vue.component('ChangtaskAdmin', require('./components/task/ChangtaskAdmin.vue').default);
// добавить коментарий к  исполненому заказу
Vue.component('AddorderCommentadmin', require('./components/task/AddorderCommentadmin.vue').default);
// ссылка показать комментарий
Vue.component('LinkShowcomment', require('./components/task/LinkShowcomment.vue').default);
// кнопка показать лог
Vue.component('LogTask', require('./components/task/LogTask.vue').default);
//модалка обновить статус
Vue.component('AdminUpdate', require('./components/task/AdminUpdate.vue').default);
//************************ task end ***********************************************************


// для пользователя показать комментарии
Vue.component('AdmincoomentsModal', require('./components/order/AdmincoomentsModal.vue').default);
// показать сообщение пользователю что присоединено
Vue.component('AddworkTask', require('./components/worker/AddworkTask.vue').default);
// просмотр ленты комментов
Vue.component('CommentsFeed', require('./components/worker/CommentsFeed.vue').default);
const app = new Vue({
    el: '#app',
    components: {},
    data() {
        return {}
    }
});
