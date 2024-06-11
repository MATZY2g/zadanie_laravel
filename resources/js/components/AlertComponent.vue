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
    name: 'AlertComponent',
    data() {
      return {
        message: '',
        type: 'success', // Can be 'success', 'danger', 'warning', etc.
        visible: false,
      };
    },
    methods: {
      showAlert(message, type = 'success') {
        this.message = message;
        this.type = type;
        this.visible = true;
        setTimeout(() => {
          this.visible = false;
        }, 3000); // Alert will disappear after 3 seconds
      },
      hideAlert() {
        this.visible = false;
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
  