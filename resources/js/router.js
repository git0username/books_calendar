import { createRouter, createWebHistory } from 'vue-router'
import Index from './components/index/Index.vue'
import bookingList from './components/bookingList/bookingList'
import calendar from './components/calendar'
import login_form from './components/form/login_form'


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