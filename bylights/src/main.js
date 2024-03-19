import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
/*import global css file 
import './assets/global.css'*/

/*app gets rendered @ id=app */
createApp(App).use(router).mount('#app')
