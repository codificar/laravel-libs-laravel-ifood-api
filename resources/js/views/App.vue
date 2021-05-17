<template>
  <v-app>
    <header-component/>
    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
    
    <!-- <sidebar-component/> -->
    
    <footer-component/>
  </v-app>
</template>

<script>
import FooterComponent from '../components/Footer.vue';
import HeaderComponent from '../components/Header.vue';
import SidebarComponent from '../components/Sidebar.vue';
import ModalComponent from '../components/Modal';
export default {
  components: {
    FooterComponent,
    HeaderComponent,
    SidebarComponent,
    ModalComponent
  },
  data() {
    return {
      drawer: true
    }
  },
}
</script>

<style>

</style>