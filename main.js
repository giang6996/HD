
import App from './app.js';
import router from './routes/router.js';

const app = Vue.createApp(App);

app.provide('auth', Vue.reactive({
  isLoggedIn: !!localStorage.getItem('user'),
  user: JSON.parse(localStorage.getItem('user'))
}));

app.use(router);
app.mount('#app');