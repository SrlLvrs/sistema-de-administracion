<template>
  <div id="app">
    <!-- Mostrar el navbar solo si el usuario está autenticado -->
    <Navbar v-if="user" />
    <router-view></router-view>
  </div>
</template>

<script>
import { getAuth, onAuthStateChanged } from 'firebase/auth';
import Navbar from './components/Navbar.vue';

export default {
  data() {
    return {
      user: null, // Almacena el estado del usuario
    };
  },
  components: {
    Navbar,
  },
  created() {
    const auth = getAuth();
    onAuthStateChanged(auth, (user) => {
      this.user = user; // Actualiza el estado del usuario cuando cambia
    });
  },
};
</script>
