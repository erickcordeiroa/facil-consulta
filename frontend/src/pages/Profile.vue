<template>
  <MainLayout>
    <div class="container profile-page">
      <div class="profile-content">
        <section class="appointments-section">
          <div class="card appointments-card">
            <header class="appointments-card__head">
              <h2 class="h2 appointments-card__title">
                <span class="material-icons-round appointments-card__title-icon" aria-hidden="true">event</span>
                Próximas consultas
              </h2>
              <div class="appointments-card__divider" role="presentation"></div>
            </header>
            <div v-if="loading" class="appointments-card__body">Carregando...</div>
            <div v-else-if="upcoming.length === 0" class="empty appointments-card__body">Nenhuma consulta futura.</div>
            <div v-else class="appointments-card__body">
              <article v-for="appt in upcoming" :key="appt.id" class="appointment-card">
                <div class="appointment-card__doctor">
                  <div class="doctor-name">{{ appt.doctor.name }}</div>
                  <div class="specialty">{{ appt.doctor.specialty }}</div>
                  <div class="address">
                    <span class="material-icons-round location-icon" aria-hidden="true">location_on</span>
                    <span class="address-text">{{ appt.doctor.address }}</span>
                  </div>
                </div>
                <div class="appointment-card__schedule">
                  <p class="appt-label">Agendada para</p>
                  <p class="appt-datetime">{{ formatDateTime(appt.date, appt.hour) }}</p>
                </div>
              </article>
            </div>
          </div>

          <div class="card appointments-card">
            <header class="appointments-card__head">
              <h2 class="h2 appointments-card__title">
                <span class="material-icons-round appointments-card__title-icon" aria-hidden="true">history</span>
                Consultas anteriores
              </h2>
              <div class="appointments-card__divider" role="presentation"></div>
            </header>
            <div v-if="loading" class="appointments-card__body">Carregando...</div>
            <div v-else-if="previous.length === 0" class="empty appointments-card__body">Nenhuma consulta anterior.</div>
            <div v-else class="appointments-card__body">
              <article v-for="appt in previous" :key="appt.id" class="appointment-card appointment-card--past">
                <div class="appointment-card__doctor">
                  <div class="doctor-name">{{ appt.doctor.name }}</div>
                  <div class="specialty">{{ appt.doctor.specialty }}</div>
                  <div class="address">
                    <span class="material-icons-round location-icon" aria-hidden="true">location_on</span>
                    <span class="address-text">{{ appt.doctor.address }}</span>
                  </div>
                </div>
                <div class="appointment-card__schedule">
                  <p class="appt-label">Realizada em</p>
                  <p class="appt-datetime">{{ formatDateTime(appt.date, appt.hour) }}</p>
                </div>
              </article>
            </div>
          </div>
        </section>
        <aside class="profile-aside card">
          <h2 class="h2 titleBlock"><span class="material-icons-round">account_circle</span> Minha conta</h2>
          <div class="profile-info-row">
            <div class="profile-info">
              <div class="profile-name">{{ user.name }}</div>
              <div class="profile-email">{{ user.email }}</div>
            </div>
            <div class="profile-logout">
              <button class="button danger logout-btn" @click="logout">Sair</button>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import MainLayout from '../layouts/MainLayout.vue';
import { useRouter } from 'vue-router';

const user = ref({ name: '', email: '' });
const upcoming = ref([]);
const previous = ref([]);
const loading = ref(true);
const router = useRouter();

function formatDateTime(date, hour) {
  if (!date || !hour) return '';
  const [year, month, day] = date.split('-');
  return `${day}/${month}/${year} às ${hour}`;
}

async function fetchProfile() {
  loading.value = true;
  const token = localStorage.getItem('auth_token');
  try {
    const resUser = await fetch('http://localhost:8000/api/auth/me', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    if (resUser.ok) {
      user.value = await resUser.json();
    }
    const resAppt = await fetch('http://localhost:8000/api/appointments', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    if (resAppt.ok) {
      const data = await resAppt.json();
      upcoming.value = data.upcoming || [];
      previous.value = data.previous || [];
    }
  } catch (e) {
  } finally {
    loading.value = false;
  }
}

async function logout() {
  const token = localStorage.getItem('auth_token');
  await fetch('http://localhost:8000/api/auth/logout', {
    method: 'POST',
    headers: { 'Authorization': `Bearer ${token}` }
  });
  localStorage.removeItem('auth_token');
  localStorage.removeItem('isLoggedIn');
  router.push('/search');
}

onMounted(fetchProfile);
</script>

<style scoped>
.profile-page {
  padding: 2rem 0;
  background: #f0f4f8;
  min-height: 80vh;
}
.profile-content {
  display: flex;
  gap: 2rem;
  margin: 0 auto;
}
.appointments-section {
  flex: 2;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}
.card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 1px 8px rgba(41, 133, 240, 0.08);
  padding: 2rem;
  margin-bottom: 1rem;
  border: 1px solid #e1efff;
}
.appointments-card {
  padding: 24px;
}
.appointments-card__head {
  margin: 0;
}
.appointments-card__title {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  font-family: "Inter", sans-serif;
  font-weight: 700;
  font-size: 1rem;
  line-height: 1.35;
  color: #333;
}
.appointments-card__title-icon {
  font-size: 22px;
  color: #333;
  flex-shrink: 0;
}
.appointments-card__divider {
  height: 1px;
  background: #e5e9ed;
  margin-top: 14px;
  width: 100%;
}
.appointments-card__body {
  margin-top: 0;
}
.appointment-card {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  gap: 0;
  padding: 22px 0 20px;
  border-bottom: 1px solid #e5e9ed;
}
.appointment-card:first-of-type {
  padding-top: 20px;
}
.appointment-card:last-child {
  border-bottom: none;
  padding-bottom: 0;
}
.appointment-card__doctor {
  width: 100%;
  min-width: 0;
}
.appointment-card__schedule {
  width: 100%;
  margin-top: 22px;
}
.doctor-name {
  font-family: "Inter", sans-serif;
  font-weight: 700;
  font-size: 1.0625rem;
  line-height: 1.45;
  color: #333;
  margin: 0;
}
.specialty {
  font-family: "Inter", sans-serif;
  font-weight: 400;
  font-size: 0.9375rem;
  line-height: 1.5;
  color: #777;
  margin: 6px 0 0;
}
.address {
  font-family: "Inter", sans-serif;
  font-weight: 400;
  font-size: 0.75rem;
  line-height: 1.5;
  display: flex;
  align-items: flex-start;
  gap: 6px;
  margin: 6px 0 0;
  color: #999;
}
.address-text {
  flex: 1;
  min-width: 0;
}
.location-icon {
  font-size: 16px;
  color: #999;
  flex-shrink: 0;
  margin-top: 1px;
}
.appt-label {
  font-family: "Inter", sans-serif;
  font-weight: 400;
  font-size: 0.9375rem;
  line-height: 1.45;
  color: #777;
  margin: 0;
}
.appt-datetime {
  font-family: "Inter", sans-serif;
  font-weight: 700;
  font-size: 0.9375rem;
  line-height: 1.45;
  color: #2985f0;
  margin: 4px 0 0;
}
.appointment-card--past .appt-datetime {
  color: #333;
}
@media (min-width: 769px) {
  .appointment-card {
    flex-direction: row;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
  }
  .appointment-card__doctor {
    flex: 1;
    width: auto;
  }
  .appointment-card__schedule {
    flex: 0 0 auto;
    width: auto;
    margin-top: 0;
    margin-left: auto;
    text-align: right;
  }
}
.profile-aside {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 1.5rem;
  min-width: 280px;
}
.profile-info-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  gap: 1rem;
}
.profile-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.profile-logout {
  margin-left: auto;
  display: flex;
  align-items: center;
}
.profile-name {
  font-family: Poppins;
  font-weight: 600;
  font-size: 18px;
}
.profile-email {
  font-family: Inter;
  font-size: 15px;
  color: #888;
}
.logout-btn {
  background: var(--color-danger-0);
  color: var(--color-danger-2);
  border: none;
  padding: 10px 24px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.2s;
}
.logout-btn:hover {
  background: var(--color-danger-1);
}
.empty {
  color: #888;
  font-size: 15px;
  margin: 1rem 0;
}
.titleBlock{
  display: flex;
  gap: 10px;
}
@media (max-width: 900px) {
  .profile-content {
    flex-direction: column;
    gap: 1rem;
  }
  .appointments-section, .profile-aside {
    width: 100%;
    min-width: 0;
  }
}
@media (max-width: 768px) {
  .profile-content {
    flex-direction: column;
  }
  .profile-aside {
    order: 1;
  }
  .appointments-section {
    order: 2;
  }
  .appointments-section > .card:first-child {
    order: 2;
  }
  .appointments-section > .card:last-child {
    order: 3;
  }
}
</style> 