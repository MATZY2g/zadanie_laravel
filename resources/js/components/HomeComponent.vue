<template>
  <div id="app">
    
    <NavBar />
    <AlertComponent ref="alertComponent" />

  </div>
</template>

<script>
import NavBar from './NavComponent.vue';
import AlertComponent from './AlertComponent.vue'; 

export default {
  name: 'App',
  components: {
    NavBar,
    AlertComponent
    
  }
};
</script>

<style>
</style>
