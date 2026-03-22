<template>
    <div>
      <LoginModal
        v-if="showLogin"
        @close="closeAll"
        @criar-conta="openRegister"
      />
  
      <RegisterModal
        v-if="showRegister"
        @close="closeAll"
        @entrar-conta="openLogin"
      />
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  import LoginModal from "./LoginModal.vue";
  import RegisterModal from "./RegisterModal.vue";
  
  const showLogin = ref(false);
  const showRegister = ref(false);
  
  function openLogin() {
    showLogin.value = true;
    showRegister.value = false;
  }
  
  function openRegister() {
    showLogin.value = false;
    showRegister.value = true;
  }
  
  function closeAll() {
    showLogin.value = false;
    showRegister.value = false;
  }
  
  defineExpose({
    openLogin,
    openRegister,
    closeAll,
  });
  </script>
  