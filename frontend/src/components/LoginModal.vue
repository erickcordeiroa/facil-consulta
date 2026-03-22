<template>
  <div class="modal modal-overlay" @click.self="close">
    <div class="modal-content">
      <div class="modal-title-row">
        <h2 class="modal-title">Entre na sua conta</h2>
        <button class="close-btn" @click="close" aria-label="Fechar">
          <span class="material-icons-round">close</span>
        </button>
      </div>
      <form class="modal-form" @submit.prevent="login">
        <label class="input-group">
          <span class="label-text">E-mail</span>
          <input 
            type="email" 
            placeholder="exemplo@gmail.com" 
            v-model="email" 
            required 
          />
        </label>
        <label class="input-group">
          <span class="label-text">Senha</span>
          <input 
            type="password" 
            placeholder="Digite sua senha" 
            v-model="password" 
            required 
          />
        </label>
        <div v-if="loginError" class="error-message">
          {{ loginError }}
        </div>
        <button class="button primary" type="submit">Entrar</button>
      </form>
      <div class="modal-footer">
        <span>Não tem uma conta?</span>
        <button class="button secondary" @click="criarConta">Criar uma conta</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits } from "vue";
const emit = defineEmits(["close", "criar-conta"]);

const email = ref("");
const password = ref("");
const loginError = ref("");

function close() {
  emit("close");
}
  
async function login() {
  loginError.value = "";
  try {
    const response = await fetch("http://localhost:8000/api/auth/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value
      })
    });
    if (!response.ok) {
      const data = await response.json();
      if (data && data.errors && (data.errors.email || data.errors.password)) {
        loginError.value = "Email ou senha incorretos, verifique e tente novamente.";
      } else if (data && data.message) {
        loginError.value = data.message;
      } else {
        loginError.value = "Erro ao tentar fazer login.";
      }
      return;
    }
    loginError.value = "";
    const data = await response.json();
    localStorage.setItem('auth_token', data.access_token);
    localStorage.setItem('isLoggedIn', 'true');
    window.location.reload();
    // emit('login-success', data); // Refatorar essa função e processo para não atualizar a página.
    // close(); // Refatorar essa função e processo para não atualizar a página.
  } catch (error) {
    loginError.value = "Erro ao tentar fazer login.";
  }
}
  
function criarConta() {
  emit("criar-conta");
}
</script>

<style scoped>
.modal-title-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}
</style>
  