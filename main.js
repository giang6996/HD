
import App from './app.js';
import router from './routes/router.js';

Vue.createApp(App)
  .use(router)
  .mount('#app');