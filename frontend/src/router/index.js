import { createRouter, createWebHistory } from "vue-router";
import Search from "../pages/Search.vue";
import Profile from "../pages/Profile.vue";

const routes = [
  { path: "/search", component: Search },
  { path: "/profile", component: Profile },
  { path: "/", redirect: "/search" },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
