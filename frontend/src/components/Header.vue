<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from 'vue-router';
import Button from "../components/Button.vue";
import AuthModals from "../components/AuthModals.vue";

const authRef = ref(null);
const isLoggedIn = ref(false);
const router = useRouter();

function openModal() {
  authRef.value.openLogin();
}

function handleLoginSuccess() {
  isLoggedIn.value = true;
}

function handleMeuPerfil() {
  router.push('/profile');
}

function handleSearch() {
  router.push('/search');
}

onMounted(() => {
  isLoggedIn.value = localStorage.getItem('isLoggedIn') === 'true';
});
</script>

<template>
  <header class="header">
    <div class="container flex-header">
      <img src="/logo.png" alt="Logo Fácil Consulta" class="logo" />

      <div class="actions">
        <Button v-if="!isLoggedIn" variant="button secondary" @click="openModal">
          Entrar / Criar conta
        </Button>
        <template v-else>
          <button class="icon-button" @click="handleSearch">
            <span class="material-icons-round">search</span>
          </button>
          <Button variant="button secondary" @click="handleMeuPerfil">
            Meu perfil
          </Button>
        </template>
      </div>
    </div>

    <AuthModals ref="authRef" @login-success="handleLoginSuccess" />
  </header>
</template>
