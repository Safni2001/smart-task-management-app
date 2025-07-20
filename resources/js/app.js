import "./bootstrap";
import { createApp } from "vue";
import Register from "./components/Register.vue";

const app = createApp({});
app.component("Register", Register);
app.mount("#app");
