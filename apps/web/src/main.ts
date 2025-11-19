import { VueQueryPlugin } from "@tanstack/vue-query";
import { createPinia } from "pinia";
import PrimeVue from "primevue/config";
import { createApp } from "vue";
import App from "./App.vue";
import "./style.css";

const app = createApp(App);
const pinia = createPinia();

app.use(VueQueryPlugin);
app.use(pinia);
app.use(PrimeVue, { unstyled: true });
app.mount("#app");
