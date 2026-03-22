<template>
  <MainLayout>
    <section class="doctor-list">
      <div v-if="loading" class="loading">Carregando...</div>
      <div v-else>
        <div v-for="doctor in doctors" :key="doctor.id" class="doctor-card">
          <div class="container d-flex">
          <div class="doctor-info">
            <h3 class="doctor-name">{{ doctor.name }}</h3>
            <p class="specialty">{{ doctor.specialty }}</p>
            <p class="address">
              <span class="material-icons-round location-icon"
                >location_on</span
              >
              {{ doctor.address }}
            </p>
          </div>

          <div class="divider"></div>

          <div class="doctor-schedule">
            <div class="days-nav">
              <button class="arrow" @click="prevDays(doctor)">
                <span class="material-icons-round">chevron_left</span>
              </button>
              <div class="days-list days-list-grid">
                <div
                  v-for="(day, idx) in getVisibleDays(doctor)"
                  :key="day.date"
                  :class="['day']"
                >
                  <span class="day-name">{{ day.weekday_name }}</span>
                  <span class="day-date">{{ formatDate(day.date) }}</span>
                  <div class="hours-col">
                    <button
                      v-for="(hour, i) in day.hours"
                      :key="hour + i"
                      class="time-slot"
                      @click="bookAppointment(doctor, day, hour)"
                    >
                      {{ hour.slice(0, 5) }}
                    </button>
                    <span v-if="!day.hours.length" class="no-slots">-</span>
                  </div>
                </div>
              </div>
              <button class="arrow" @click="nextDays(doctor)">
                <span class="material-icons-round">chevron_right</span>
              </button>
            </div>
          </div>
          </div>
        </div>

        <div v-if="doctors.length === 0" class="empty">
          Nenhum médico encontrado.
        </div>
      </div>
    </section>
    <LoginModal
      v-if="showLoginModal"
      @close="showLoginModal = false"
      @criar-conta="() => { showLoginModal = false; showRegisterModal = true; }"
    />
    <RegisterModal
      v-if="showRegisterModal"
      @close="showRegisterModal = false"
      @entrar-conta="() => { showRegisterModal = false; showLoginModal = true; }"
    />
    <AppointmentModal
      :visible="showAppointmentModal"
      :details="appointmentDetails"
      @cancel="showAppointmentModal = false"
      @confirm="confirmAppointment"
    />
  </MainLayout>
</template>

<script setup>
import { ref, onMounted, reactive } from "vue";
import { useRouter } from 'vue-router';
import MainLayout from "../layouts/MainLayout.vue";
import LoginModal from "../components/LoginModal.vue";
import AppointmentModal from "../components/AppointmentModal.vue";
import RegisterModal from "../components/RegisterModal.vue";

const doctors = ref([]);
const loading = ref(true);

// Modal state
const showLoginModal = ref(false);
const showRegisterModal = ref(false);
const showAppointmentModal = ref(false);
const appointmentDetails = ref({ doctor: null, day: null, hour: null });
const router = useRouter();

// For each doctor, track the window of days and the selected day
const scheduleState = reactive({});

onMounted(async () => {
  try {
    const res = await fetch("http://localhost:8000/api/doctors");
    if (res.ok) {
      doctors.value = await res.json();
      doctors.value.forEach((doctor) => {
        scheduleState[doctor.id] = {
          dayIndex: 0,
          selectedDay: doctor.days[0] || null,
        };
      });
    } else {
      doctors.value = [];
    }
  } catch (e) {
    doctors.value = [];
  } finally {
    loading.value = false;
  }
});

function getVisibleDays(doctor) {
  const state = scheduleState[doctor.id];
  if (!state) return [];
  return doctor.days.slice(state.dayIndex, state.dayIndex + 4);
}

function prevDays(doctor) {
  const state = scheduleState[doctor.id];
  if (state.dayIndex > 0) {
    state.dayIndex -= 1;
    state.selectedDay = doctor.days[state.dayIndex] || null;
  }
}

function nextDays(doctor) {
  const state = scheduleState[doctor.id];
  if (state.dayIndex < doctor.days.length - 4) {
    state.dayIndex += 1;
    state.selectedDay = doctor.days[state.dayIndex] || null;
  }
}

function formatDate(dateStr) {
  // "2025-07-18" => "18/07"
  const [year, month, day] = dateStr.split("-");
  return `${day}/${month}`;
}

function bookAppointment(doctor, day, hour) {
  const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
  if (!isLoggedIn) {
    showLoginModal.value = true;
    return;
  }
  appointmentDetails.value = { doctor, day, hour };
  showAppointmentModal.value = true;
}

async function confirmAppointment() {
  const doctor = appointmentDetails.value.doctor;
  const day = appointmentDetails.value.day;
  const hour = appointmentDetails.value.hour;
  const token = localStorage.getItem('auth_token');
  try {
    const res = await fetch('http://localhost:8000/api/appointments', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(token ? { 'Authorization': `Bearer ${token}` } : {})
      },
      body: JSON.stringify({
        doctor_id: doctor.id,
        date: day.date,
        hour: hour
      })
    });
    if (!res.ok) {
      const data = await res.json();
      alert(data.message || 'Erro ao agendar consulta.');
      return;
    }
    // const data = await res.json();
    showAppointmentModal.value = false;
    // Redirect to Profile page
    router.push('/profile');
  } catch (e) {
    alert('Erro ao agendar consulta.');
  }
}
</script>

<style scoped>

</style>
