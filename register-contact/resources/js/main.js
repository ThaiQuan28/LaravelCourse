import { createApp } from 'vue';
import router from './router';
import App from './components/App.vue';
import 'vue-sonner/dist/style.css';

createApp(App).use(router).mount('#app');