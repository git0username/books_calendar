import { createRouter, createWebHistory } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
import checkoutForm from './components/checkoutForm'

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
            component: HelloWorld,
            props:true
        },
        {
            path:'/check',
            name: 'check',
            component:checkoutForm,
            props:true        
        },
        {
            path:'/check',
            name: 'check',
            component:checkoutForm,
            props:true        
        },

            //post渡し方
        
    ],
})

export default router