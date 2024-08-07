import { createRouter, createWebHistory } from 'vue-router'
import axios from "axios";
import LoginView from '../views/LoginView.vue'
import MainView from '../views/MainView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/main',
      name: 'main',
      component: MainView
    }
  ]
})

router.beforeEach((to, from)=>{
  if (to.name == "login"){
    return true
  }
  if(!localStorage.getItem('token')){
    return {
      name: 'login'
    }
  }

  checkTokenAuthenticity()

})

const checkTokenAuthenticity = () => {

  axios.get('http://127.0.0.1:8000/api/v1/user', {
    headers:{
      Authorization: `Bearer ${localStorage.getItem('token')}`
    }
  })
  .then((response) => {})
  .catch((error)=> {
    localStorage.removeItem('token');
    router.push({
      name: 'login'
    })
  })

}

export default router
