require('./bootstrap');
require('./common');
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
    hideModules: {
        "image": true
    },
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


Vue.component('TaskreadAdmin', require('./components/TaskreadAdmin.vue').default);


// задания пользователя с датой
Vue.component('TasklistuserComponent', require('./components/TasklistUsert.vue').default);

// задания пользователя  свободные
Vue.component('TasklistuserFree', require('./components/TasklistuserFree.vue').default);

//работник заказы
Vue.component('UserorderComponent', require('./components/UserOder.vue').default);

// работник история
Vue.component('HistoryOrders', require('./components/HistoryOrders.vue').default);

// компоненты загрузки-импорта
Vue.component('TaskImport', require('./components/TaskImport.vue').default);
Vue.component('ImportButton', require('./components/ImportButton.vue').default);
// компоненты загрузки-импорта ********************************************


Vue.component('SavedComponent', require('./components/other/SavedComponent.vue').default);

//главная страница админа
Vue.component('DashbordAdmin', require('./components/dashbord/DashbordAdmin.vue').default);

Vue.component('ReadOrder', require('./components/order/ReadOrder.vue').default);

// короткий серийник
Vue.component('ShortSerial', require('./components/serial/ShortSerial.vue').default);

// короткий серийник
Vue.component('SerialLink', require('./components/other/SerialLink.vue').default);

// копирование серийника SerialsCopy
Vue.component('SerialsCopy', require('./components/serial/SerialsCopy.vue').default);

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

//**************** ReadMore **********************************
Vue.component('ReadMore', require('./components/ReadMore.vue').default);

//**************** Docs **********************************
Vue.component('DocsCopy', require('./components/docs/Copy.vue').default);
Vue.component('DocsLink', require('./components/docs/Link.vue').default);

const app = new Vue({
    el: '#app',
    components: {},
    data() {
        return {}
    }
});
