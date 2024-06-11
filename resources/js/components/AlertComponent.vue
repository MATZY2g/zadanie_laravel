<template>
    <div class="alert-container" v-if="visible">
      <div :class="`alert alert-${type}`" role="alert">
        {{ message }}
        <button type="button" class="btn-close" aria-label="Close" @click="hideAlert"></button>
      </div>
    </div>
</template>
  
<script>
  export default {
    name: 'AlertComponent', // Názov komponentu
    data() {
      return {
        message: '', // Správa, ktorá sa zobrazí v alerte
        type: 'success', // Typ alertu ('success', 'danger', atď.)
        visible: false, // Viditeľnosť alertu
      };
    },
    methods: {
      // Metóda na zobrazenie alertu s danou správou a typom
      showAlert(message, type = 'success') {
        this.message = message; // Nastavenie správy
        this.type = type; // Nastavenie typu alertu
        this.visible = true; // Nastavenie viditeľnosti alertu na true
        // Skrytie alertu po 3 sekundách
        setTimeout(() => {
          this.visible = false; // Nastavenie viditeľnosti alertu na false
        }, 3000);
      },
      // Metóda na okamžité skrytie alertu
      hideAlert() {
        this.visible = false; // Nastavenie viditeľnosti alertu na false
      }
    }
  };
</script>
  
  
<style scoped>
  .alert-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
  }
</style>
  