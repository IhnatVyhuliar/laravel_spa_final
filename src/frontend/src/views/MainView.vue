<script setup>

// const items = ref([{ message: 'Foo' }, { message: 'Bar' }])

import { computed,  onMounted, reactive, ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import VueRecaptcha from 'vue-recaptcha-v3';
const siteKey = ref('yourSiteAPIKey');

const handleSuccess = (response) => {
  // Handle successful reCAPTCHA verification
};

const handleError = (error) => {
  // Handle reCAPTCHA error
};

const comments = ref([])

const base_url = "http://127.0.0.1:8000/api/v1"
const api_endpoints = {
    index : '/comments/',

    sort: {
        reverse: '/comments/reverse',
        offset: '/comments/offset/',
        revrseOffset: '/comments/reverse/offset/',

        email:{
            sortByEmail: '/comments/email',
            sortByEmailReverse: '/comments/email/reverse',
            sortByEmailOffset: '/comments/email/offset/',
            sortByEmailReverseOffset: '/comments/email/reverse/offset/',
        },
        name:{
            sortByName: '/comments/name',
            sortByNameReverse: '/comments/name/reverse',
            sortByNameOffset: '/comments/name/offset/',
            sortByNameReverseOffset: '/comments/name/reverse/offset/',
        }
        
    },
    add: '/comment/add'
}

let offset = 0
let reverse = ref(true)
let email_field = ref(null)
let name_field = ref(null)

let reactive_data_form = reactive({
    name:  null,
    email: null,
    text_comment: null
})


const router = useRouter()
const getComments = (offset = 0 ) => {
    let url = base_url

    if (email_field.value.value != ""&& checkEmail(email_field.value.value)){

        getSortedEmails(email_field.value, offset)
        return
    }
    else if (reverse.value){
        url+= api_endpoints.sort.revrseOffset+offset
        
    }
    else{
        url+= api_endpoints.sort.offset+offset
    }

    getCommentsFromLink(url)
}

const getCommentsFromLink = (link, data_validated = null) => {
    if (data_validated)
    {
        axios.post(link, 
            data_validated,
            {headers:{
                Authorization: `Bearer ${localStorage.getItem('token')}`
            }}
            
        )
        .then((response) => {
            // console.log(response.data)
            comments.value = response.data
            
        
        })
        .catch((error)=> {
            // localStorage.removeItem('token');
            router.push({
                name: 'login'
            })
        })
    }else{
        
        axios.get(link, {
            headers:{
            Authorization: `Bearer ${localStorage.getItem('token')}`
        }
        })
        .then((response) => {
            // console.log(response.data)
            comments.value = response.data
            
        
        })
        .catch((error)=> {
            // localStorage.removeItem('token');
            router.push({
            name: 'login'
            })
        })
    }
}

const renderNextComments = () =>{
    offset+= 25
    getComments(offset)
    // console.log(offset)
}

const renderPreviousComments = () =>{
    if (offset < 25)
    {
        return
    }
    offset-= 25
    console.log(offset)
    getComments(offset)
}

const reverseDate = () =>{

    reverse.value = !reverse.value

    getComments(offset)

}


const onChangeEmail= (event, offset = 0) => {
    const email = event.target.value;
    if (checkEmail(email)) {
        getSortedEmails(email, offset)
    }
    getComments(offset)
}

const getSortedEmails = (email, offset) =>{
    if (checkEmail(email)) {
        let url =  base_url
        if (reverse.value)
        {
            url+=api_endpoints.sort.email.sortByEmailReverseOffset+offset
        }else{
            url+= base_url+api_endpoints.sort.email.sortByEmailOffset+offset
        }

        let data_with_email ={
            email: email
        }
        getCommentsFromLink(url,  data_with_email)

    }
    
}

const getSortedNames = (name, offset) =>{
    let url =  base_url
    if (reverse.value)
    {
        url+=api_endpoints.sort.name.sortByNameReverseOffset+offset
    }else{
        url+= base_url+api_endpoints.sort.name.sortByNameOffset+offset
    }
    alert(url)
    let data_with_email ={
        name: name
    }
    getCommentsFromLink(url,  data_with_email)
}

const checkEmail = (email) => {
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)
}

const onChangeName = (event, offset = 0) => {
    const name = event.target.value;
    //alert(event.target.value)
    getSortedNames(name, offset)
}

// const addComment = (event) =>{
//     console.log(event.currentTarget.id)
// } 

const handleAddReply = () => {
    getCommentsFromLink(base_url+api_endpoints.add, reactive_data_form)
}

onMounted(() => {
    getComments(0)
 
    if (localStorage.getItem('token')) {
        // alert("main");
        router.push({
            name: 'main'
        })
    }
})

</script>

<template>
    <div class="w-full p-6  mx-auto">
        <div >
            <form action="#" class="container flex flex-col gap-4 min-h-20 block p-4 border-solid border-4 border-black" method="post" @submit.prevent="handleAddReply">
                <input type="text"  name="name"  v-model="reactive_data_form.name" class=" p-4 border-solid border-4 w-full border-gray-400" placeholder="User name" required>
                <input type="email"  name="email" v-model="reactive_data_form.email" class=" p-4 border-solid border-4 w-full border-gray-400" placeholder="example@gmail.com" required>
                <textarea   class=" p-4 border-solid border-4 w-full border-gray-400" v-model="reactive_data_form.text_comment" placeholder="home page"></textarea>
                <VueRecaptcha
                :sitekey="siteKey"
                :load-recaptcha-script="true"
                @verify="handleSuccess"
                @error="handleError"></VueRecaptcha>
                <div  class="bg-gray-50 text-right sm: px-6">
                    <button type="submit" @submit.prevent="handleAddReply" class="inline-flex justify-center rounded-md border border-transparent
                    bg-black p-4 text-white">Send</button>
                </div>
            </form>
            
        </div>
        <div class="container flex flex-auto gap-5 mt-4">
            <div>
                <button @click="reverseDate" class="p-4 bg-black rounded-md text-white" >
                Sort by date
                <span v-if="reverse" class="material-symbols-outlined text-white" >
                    keyboard_arrow_down
                </span>
                <span v-else class="material-symbols-outlined text-white" >
                    keyboard_arrow_up
                </span>
            </button>
            </div>
            <div>
                <input type="email" ref="email_field"  @change="onChangeEmail($event)" name="email"  class=" p-4 border-solid border-4 border-black" placeholder="example@gmail.com">
            </div>
            <div>
                <input type="text" ref="name_field"  @change="onChangeName($event)" name="name"  class=" p-4 border-solid border-4 border-black" placeholder="Mark">
            </div>
        </div>
        <div class="container flex flex-col overflow-hidden">
            <div v-for="comment in comments" :key="comment.id" @click='addComment' v-if="comments.length >0" class="container min-h-20 p-3 block">
                <div class="w-full rounded-md h-16 bg-gray-200 overflow-hidden">
                    <div class="flex justify-start gap-5 place-items-center">
                    
                        <div v-if="comment.photo_file" class="w-12 h-12 ml-2 rounded-full ">
                            <img :src="comment.photo_file" alt="Image description" />
                        </div>
                        <div v-else class="w-12 h-12 ml-2 rounded-full bg-black">
                            
                        </div>
                        <div class="name text-xl font-bold">
                            {{ comment.user.name }}
                        </div>
                        <div class="time text-l font-normal" v-if="comment.created_at">
                            
                            {{  comment.created_at.substring(0, 10)}} в {{comment.created_at.substring(11, 19)}}
                        </div>
                        <div >
                            <div  class="favorite text-l font-extrabold not-italic text-xl">
                            <svg width="30" height="30" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect width="96" height="96" fill="url(#pattern0_26_2)" />
                                <defs>
                                    <pattern id="pattern0_26_2" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_26_2" transform="scale(0.0104167)" />
                                    </pattern>
                                    <image id="image0_26_2" width="96" height="96"
                                        xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAACXBIWXMAAAsTAAALEwEAmpwYAAABRUlEQVR4nO3VwY1DMRDDUPfftLaGIBvwx3kE5mx4KNnnAAAAAAAAAP/H3pxvPz+nXsAIICClTuA0gICUOoHTAAJS6gROAwhIqRM4DSAgpU7gbmvAuxe6fT5OfcE9fAg4BOQpnAb0i5gnqF/GgvEHHALyFE4D+kXME9QvY8H4Aw4BeQqnAf0i5gnql7Fg/AGHgDyFu7kBr1JfaLct9NsWMAIISKkTOA0gIKVO4DSAgJQ6gdMAAlLqBE4DCEipEzgNICClTuA0gICUOoG7rQHvXuj2+Tj1BffwIeAQkKdwGtAvYp6gfhkLxh9wCMhTOA3oFzFPUL+MBeMPOATkKZwG9IuYJ6hfxoLxBxwC8hTu5ga8Sn2h3bbQb1vACCAgpU7gNICAlDqB0wACUuoETgMISKkTOA0gIKVO4H69AQAAAAAAADiP4A8DzPLEfyS0lgAAAABJRU5ErkJggg==" />
                                </defs>
                            </svg>
                            </div>
                        </div>
                        
                        <div>
                            <svg width="30" height="30" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_27_3)">
                                    <path
                                        d="M68 12H28C23.6 12 20.04 15.6 20.04 20L20 84L48 72L76 84V20C76 15.6 72.4 12 68 12ZM68 72L48 63.28L28 72V20H68V72Z"
                                        fill="black" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_27_3">
                                        <rect width="90" height="90" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div>
                            <svg width="30" height="30" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_28_6)">
                                    <path
                                        d="M48 80C30.36 80 16 65.64 16 48C16 30.36 30.36 16 48 16C65.64 16 80 30.36 80 48C80 65.64 65.64 80 48 80ZM48 88C70.08 88 88 70.08 88 48C88 25.92 70.08 8 48 8C25.92 8 8 25.92 8 48C8 70.08 25.92 88 48 88ZM44 48V64H52V48H64L48 32L32 48H44Z"
                                        fill="black" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_28_6">
                                        <rect width="96" height="96" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                    </div>
                        <div class="ml-auto flex justify-end">
                            <div>
                                <svg width="30" height="30" viewBox="0 0 96 96" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_28_10)">
                                        <path d="M16 48L21.64 53.64L44 31.32V80H52V31.32L74.32 53.68L80 48L48 16L16 48Z"
                                            fill="black" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_28_10">
                                            <rect width="96" height="96" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="text-l font-extrabold not-italic text-xl">{{ comment.reply_comments.length }}</div>
                            
                            <div>
                                <svg width="30" height="30" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M44 56.8V24C44 22.8667 44.3833 21.9167 45.15 21.15C45.9167 20.3833 46.8667 20 48 20C49.1333 20 50.0833 20.3833 50.85 21.15C51.6167 21.9167 52 22.8667 52 24V56.8L63.6 45.2C64.3333 44.4667 65.2667 44.1 66.4 44.1C67.5333 44.1 68.4667 44.4667 69.2 45.2C69.9333 45.9333 70.3 46.8667 70.3 48C70.3 49.1333 69.9333 50.0667 69.2 50.8L50.8 69.2C50 70 49.0667 70.4 48 70.4C46.9333 70.4 46 70 45.2 69.2L26.8 50.8C26.0667 50.0667 25.7 49.1333 25.7 48C25.7 46.8667 26.0667 45.9333 26.8 45.2C27.5333 44.4667 28.4667 44.1 29.6 44.1C30.7333 44.1 31.6667 44.4667 32.4 45.2L44 56.8Z"
                                    fill="black" />
                            </svg>
                            </div>
                        </div>
                        
                        
                    </div>  

                </div>
                <div class="mt-3">
                    <div v-html="comment.comment_text"></div>
                </div>
                
                <div v-for="reply_comment in comment.reply_comments" :name="reply_comment.id"    v-if="comment.reply_comments.length >0" class="container ml-5 min-h-20 p-3 block">
                <div class="w-full rounded-md h-16 bg-gray-200 overflow-hidden">
                    <div class="flex justify-start gap-5 place-items-center">
                        <div class="w-12 h-12 ml-2 rounded-full  bg-black"></div>
                        <div class="name text-xl font-bold">

                            <!-- {{ reply_comment.comment.user.name}} -->
                           {{ reply_comment.comment[0].user.name  }}
                        </div>
                        <div class="time text-l font-normal" v-if="reply_comment.comment.created_at">
                            {{  reply_comment.comment.created_at.substring(0, 10)}} в {{reply_comment.comment.created_at.substring(11, 19)}}
                        </div>
                        <div @click='addComment'>
                            <div   class="favorite text-l font-extrabold not-italic text-xl">
                            <svg width="30" height="30" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect width="96" height="96" fill="url(#pattern0_26_2)" />
                                <defs>
                                    <pattern id="pattern0_26_2" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_26_2" transform="scale(0.0104167)" />
                                    </pattern>
                                    <image id="image0_26_2" width="96" height="96"
                                        xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAACXBIWXMAAAsTAAALEwEAmpwYAAABRUlEQVR4nO3VwY1DMRDDUPfftLaGIBvwx3kE5mx4KNnnAAAAAAAAAP/H3pxvPz+nXsAIICClTuA0gICUOoHTAAJS6gROAwhIqRM4DSAgpU7gbmvAuxe6fT5OfcE9fAg4BOQpnAb0i5gnqF/GgvEHHALyFE4D+kXME9QvY8H4Aw4BeQqnAf0i5gnql7Fg/AGHgDyFu7kBr1JfaLct9NsWMAIISKkTOA0gIKVO4DSAgJQ6gdMAAlLqBE4DCEipEzgNICClTuA0gICUOoG7rQHvXuj2+Tj1BffwIeAQkKdwGtAvYp6gfhkLxh9wCMhTOA3oFzFPUL+MBeMPOATkKZwG9IuYJ6hfxoLxBxwC8hTu5ga8Sn2h3bbQb1vACCAgpU7gNICAlDqB0wACUuoETgMISKkTOA0gIKVO4H69AQAAAAAAADiP4A8DzPLEfyS0lgAAAABJRU5ErkJggg==" />
                                </defs>
                            </svg>
                        </div>
                        </div>
                        
                        <div>
                            <svg width="30" height="30" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_27_3)">
                                    <path
                                        d="M68 12H28C23.6 12 20.04 15.6 20.04 20L20 84L48 72L76 84V20C76 15.6 72.4 12 68 12ZM68 72L48 63.28L28 72V20H68V72Z"
                                        fill="black" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_27_3">
                                        <rect width="90" height="90" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div>
                            <svg width="30" height="30" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_28_6)">
                                    <path
                                        d="M48 80C30.36 80 16 65.64 16 48C16 30.36 30.36 16 48 16C65.64 16 80 30.36 80 48C80 65.64 65.64 80 48 80ZM48 88C70.08 88 88 70.08 88 48C88 25.92 70.08 8 48 8C25.92 8 8 25.92 8 48C8 70.08 25.92 88 48 88ZM44 48V64H52V48H64L48 32L32 48H44Z"
                                        fill="black" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_28_6">
                                        <rect width="96" height="96" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                    </div>
                        <div class="ml-auto flex justify-end">
                            <div>
                                <svg width="30" height="30" viewBox="0 0 96 96" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_28_10)">
                                        <path d="M16 48L21.64 53.64L44 31.32V80H52V31.32L74.32 53.68L80 48L48 16L16 48Z"
                                            fill="black" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_28_10">
                                            <rect width="96" height="96" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="text-l font-extrabold not-italic text-xl">{{ 0}}</div>
                            
                            <div>
                                <svg width="30" height="30" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M44 56.8V24C44 22.8667 44.3833 21.9167 45.15 21.15C45.9167 20.3833 46.8667 20 48 20C49.1333 20 50.0833 20.3833 50.85 21.15C51.6167 21.9167 52 22.8667 52 24V56.8L63.6 45.2C64.3333 44.4667 65.2667 44.1 66.4 44.1C67.5333 44.1 68.4667 44.4667 69.2 45.2C69.9333 45.9333 70.3 46.8667 70.3 48C70.3 49.1333 69.9333 50.0667 69.2 50.8L50.8 69.2C50 70 49.0667 70.4 48 70.4C46.9333 70.4 46 70 45.2 69.2L26.8 50.8C26.0667 50.0667 25.7 49.1333 25.7 48C25.7 46.8667 26.0667 45.9333 26.8 45.2C27.5333 44.4667 28.4667 44.1 29.6 44.1C30.7333 44.1 31.6667 44.4667 32.4 45.2L44 56.8Z"
                                    fill="black" />
                            </svg>
                            </div>
                        </div>
                        
                        
                    </div>  

                </div>
                <div class="mt-3">
                    <div v-html="reply_comment.comment[0].comment_text"></div>
                </div>
                
                </div>
            </div>
            
        </div>
        <div class="flex text-right">
           
        </div>

        <div class="flex gap-4 text-right mt-3">
            <button @click="renderPreviousComments" class="p-2 rounded-md bg-black text-white"> previous </button>
            <button @click="renderNextComments" class="p-2 rounded-md w-14 bg-black text-white ">
                next
            </button>
        </div>
        
    </div>
    
</template>