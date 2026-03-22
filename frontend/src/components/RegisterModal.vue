<template>
    <div class="modal modal-overlay" @click.self="close">
      <div class="modal-content">
        <div class="modal-title-row">
          <h2 class="modal-title">Crie sua conta</h2>
          <button class="close-btn" @click="close" aria-label="Fechar">
            <span class="material-icons-round">close</span>
          </button>
        </div>
  
        <form class="modal-form" @submit.prevent="register">
          <label class="input-group">
            <span class="label-text">Nome completo</span>
            <input 
              type="text" 
              placeholder="Digite seu nome completo" 
              v-model="name" 
               
            />
            <span v-if="nameError" class="error-message" style="color: red;">{{ nameError }}</span>
          </label>
  
          <label class="input-group">
            <span class="label-text">E-mail</span>
            <input 
              type="email" 
              placeholder="exemplo@gmail.com" 
              v-model="email" 
               
            />
            <span v-if="emailError" class="error-message" style="color: red;">{{ emailError }}</span>
          </label>
  
          <label class="input-group">
            <span class="label-text">Senha</span>
            <input 
              type="password" 
              placeholder="Digite sua senha" 
              v-model="password" 
               
            />
            <span v-if="passwordError" class="error-message" style="color: red;">{{ passwordError }}</span>
          </label>
          <div v-if="registerError" class="error-message" style="color: red; margin-bottom: 1rem;">
            {{ registerError }}
          </div>
          <button class="button primary" type="submit">Criar conta</button>
        </form>
  
        <div class="modal-footer">
          <span>Já tem uma conta?</span>
          <button class="button secondary" @click="entrarConta">Entrar na conta</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, defineEmits } from "vue";
  const emit = defineEmits(["close", "entrar-conta"]);
  
  const name = ref("");
  const email = ref("");
  const password = ref("");
  const registerError = ref("");
  const nameError = ref("");
  const emailError = ref("");
  const passwordError = ref("");

  function validateName(nameValue) {
    if (!nameValue || nameValue.trim().split(" ").length < 2) {
      return "Por favor, informe o nome completo.";
    }
    return "";
  }

  function validateEmail(emailValue) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailValue || !emailRegex.test(emailValue)) {
      return "Por favor, informe um e-mail válido.";
    }
    return "";
  }

  function validatePassword(passwordValue) {
    if (!passwordValue || passwordValue.length < 4) {
      return "A senha deve ter pelo menos 4 dígitos.";
    }
    return "";
  }

  function clearErrors() {
    nameError.value = "";
    emailError.value = "";
    passwordError.value = "";
    registerError.value = "";
  }

  function close() {
    emit("close");
  }
  
  async function register() {
    clearErrors();
    // Local validation
    nameError.value = validateName(name.value);
    emailError.value = validateEmail(email.value);
    passwordError.value = validatePassword(password.value);

    if (nameError.value || emailError.value || passwordError.value) {
      return;
    }

    try {
      const response = await fetch("http://localhost:8000/api/auth/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify({
          name: name.value,
          email: email.value,
          password: password.value
        })
      });
      if (!response.ok) {
        const data = await response.json();
        if (data && data.errors) {
          if (data.errors.name) {
            nameError.value = data.errors.name[0];
          }
          if (data.errors.email) {
            emailError.value = data.errors.email[0];
          }
          if (data.errors.password) {
            passwordError.value = data.errors.password[0];
          }
          if (!data.errors.name && !data.errors.email && !data.errors.password) {
            registerError.value = "Erro ao tentar registrar.";
          }
        } else if (data && data.message) {
          registerError.value = data.message;
        } else {
          registerError.value = "Erro ao tentar registrar.";
        }
        return;
      }
      registerError.value = "";
      const data = await response.json();
      if (data.access_token) {
        localStorage.setItem("auth_token", data.access_token);
      }
      localStorage.setItem("isLoggedIn", "true");
      window.location.reload();
    } catch (error) {
      registerError.value = "Erro ao tentar registrar.";
    }
  }
  
  function entrarConta() {
    emit("entrar-conta");
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
  