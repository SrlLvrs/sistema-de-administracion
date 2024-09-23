import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'

import VCalendar from 'v-calendar';
import 'v-calendar/style.css';

// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyCWl3qAVlYu2LM9zIwMLP-yds5U3b2f9jk",
  authDomain: "huevos-46a28.firebaseapp.com",
  projectId: "huevos-46a28",
  storageBucket: "huevos-46a28.appspot.com",
  messagingSenderId: "910831070157",
  appId: "1:910831070157:web:f080e79d05e34a00f7b284"
};

// Initialize Firebase
initializeApp(firebaseConfig);

const app = createApp(App)

app.use(router)
// Use plugin with optional defaults
app.use(VCalendar, {})

app.mount('#app')