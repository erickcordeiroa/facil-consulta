<template>
  <div v-if="visible" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-title-row">
        <h2 class="modal-title">Agendar consulta?</h2>
        <button class="close-btn" @click="$emit('cancel')" aria-label="Fechar">
          <span class="material-icons-round">close</span>
        </button>
      </div>
      <div class="doctor-info d-flex flex-row justify-content-start">
        <div class="left">
          <h3 class="doctor-name">{{ details.doctor?.name }}</h3>
          <p class="specialty mb-1">{{ details.doctor?.specialty }}</p>
          <p class="address mb-4">
            <span class="material-icons-round location-icon">location_on</span>
            {{ details.doctor?.address }}
          </p>
        </div>
        <div class="right small">
          <p class="mb-0">Data e horário</p>
          <span>{{ formatDateTime(details.day?.date, details.hour) }}</span>
        </div>
      </div>
      <button class="button primary" @click="$emit('confirm')">Agendar consulta</button>
      <button class="button secondary mt-3" @click="$emit('cancel')">Cancelar</button>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
const props = defineProps({
  visible: Boolean,
  details: {
    type: Object,
    default: () => ({ doctor: null, day: null, hour: null })
  }
});
const emit = defineEmits(['confirm', 'cancel']);

function formatDateTime(dateStr, hourStr) {
  if (!dateStr || !hourStr) return '';
  const [year, month, day] = dateStr.split("-");
  return `${day}/${month}/${year} às ${hourStr}`;
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0,0,0,0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal-content {
  background: #fff;
  padding: 2em 2.5em;
  border-radius: 12px;
  box-shadow: 0 2px 16px rgba(0,0,0,0.12);
  min-width: 320px;
  max-width: 90vw;
}
.right{
  margin-left: 20px;
  width: 30%;
}
.right span{
  color: var(--color-primary-2);
}
.left{
  width: 70%;
}
.modal-title-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}
@media (max-width: 900px) {
  .right, .left{
    width: 100%;
  }
  .right{
    margin-left: 0px;
  }
  .doctor-info.d-flex {
    flex-direction: column !important;
    display: flex !important;
    align-items: stretch !important;
  }
}
</style>
