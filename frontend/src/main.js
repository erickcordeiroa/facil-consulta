import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import "bootstrap/dist/css/bootstrap.min.css";
import "@mdi/font/css/materialdesignicons.min.css";
import "./assets/main.css";
/* Depois de main.css para as @media em mobile.css ganharem da cascata (mesma especificidade). */
import "./assets/mobile.css";

createApp(App).use(router).mount("#app");
