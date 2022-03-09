import { createRouter, createWebHistory } from 'vue-router'
import Index from './components/Index.vue'
import checkoutForm from './components/checkoutForm'
import bookingList from './components/bookingList'
import calendar from './components/calendar'
import login_form from './components/login_form'


export const router = createRouter({
    history: createWebHistory(),
    routes: [
        // {
        //     path: '/',
        //     name: 'index',
        //     components: {
        //         default:HelloWorld,
        //         first:HelloWorld,
        //         second:HelloJSX            
        //     }  // '/' で接続されたら、Helloworldコンポーネントを表示してね。
        // },

        {
            path:'/',
            redirect:'/index'
        },
        {
            path: '/index',
            name: 'index',
            component:Index,
            props:true
        },
        {
            path:'/checkoutform',
            name: 'checkoutform',
            component:checkoutForm,
            props:true        
        },        
        {
            path:'/bookingList',
            name: 'bookingList',
            component:bookingList,
            props:true        
        },
        {
            path:'/calendar',
            name: 'calendar',
            component:calendar,
            props:true        
        },        
        {
            path:'/login',
            name: 'login_form',
            component:login_form,
            props:true        
        },        
        
    ],
})

export default router