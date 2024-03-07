import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

/*app gets rendered @ the id=app */
createApp(App).use(router).mount('#app')