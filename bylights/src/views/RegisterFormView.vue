<template>
    <div>
        <form @submit.prevent="handleSubmit">
            <p><a id="style-2" @click="back" class="back" data-replace="go back"><span>go back</span></a></p>
            
            <!-- <div>
                <router-link to="/badges"><p class="RoutingLink">Badges page</p></router-link>
            </div> -->
            <h1>REGISTER</h1>
            <section>
                <label>Nome:</label>
                <input type="text" required v-model="name">
                <label>Cognome:</label>
                <input type="text" required v-model="surname">
                <label>Email:</label>
                <!--metto @invalid.prevent=""/ per non mostrare graficamente il messaggio di errore in input sbagliato, ma comunque c'è la validazione-->
                <input type="email" required v-model="email" @invalid.prevent=""/>
                <label>Password</label>
                <input type="password" required v-model="password">
                <div v-if="passwordError" class="error">
                    {{ passwordError }}
                </div>
                <div>
                    <p>Already have an account?</p>
                    <router-link to="/"><p class="RoutingLink">Sign up</p></router-link>
                </div>
            </section>
            <div class="submit">
                <button :disabled="!isFormValid">Register</button>
            </div>
        </form>
    </div>
</template>

<script>
import { ref, computed, watch} from 'vue'
import { useRouter } from 'vue-router'

export default{
    name: 'RegisterFormView',
    setup(){
        const email = ref('')
        const password = ref('')
        const name = ref('')
        const surname = ref('')
        const passwordError = ref('')
        const router = useRouter()

        const back = () => {
            router.go(-1)
        }
        
        //controlla se il form ha campi nulli
        const isFormValid = computed(() => {
            return (
                name.value.trim() !== '' &&
                surname.value.trim() !== '' &&
                email.value.trim() !== '' &&
                email.value.includes('@') &&
                email.value.split('@')[1]?.trim() !== '' &&
                //il controllo è sequenziale, prima controlla se nulla, poi ne controlla la lunghezza
                password.value && password.value.length >= 6
            )
        })

        // //display messaggio errore password in tempo reale con watch
        watch(password, (newPassword) => {
            if (newPassword && newPassword.length < 6) {
                passwordError.value = 'Password must be at least 6 characters long';
            } else {
                passwordError.value = '';
            }
        })
       
        //funzione destinata a controllare solo se è valida l'intera form per mandare i dati
        const handleSubmit = () => {
            if (isFormValid.value) {
                //invio dati al database

                alert("Andato tutto bene");
                window.location.reload(); // Ricarica la pagina dopo l'invio
            }
        }

        return{
            email,
            password,
            name,
            surname,
            passwordError,
            handleSubmit,
            back,
            isFormValid
        }
    }
}
</script>

<style scoped>
    form {
        font-family: Avenir, Helvetica, Arial, sans-serif;
        max-width: 420px;
        margin: 30px auto;
        background:white;
        text-align: left;
        padding: 40px;
        border-radius: 10px;
    }
    label, p{
        color: #aaa;
        display: inline-block;
        margin: 25px 0 15px;
        font-size: 0.6em;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: bold;
    }
    input{
        display: block;
        padding: 10px 6px;
        width: 100%;
        box-sizing: border-box;
        border: none;
        border-bottom: 1px solid #ddd;
        color: #555;
    }
    h1{
        color: #555;
    }
    .RoutingLink{
        color: red;
        margin-left: 3%;
    }
    .RoutingLink:hover{
         text-decoration: underline;
         text-decoration-color: #ff0062;
    }
    .submit {
        text-align: center;
    }
    button {
        background: #0b6dff;
        border: 0;
        padding: 10px 20px;
        margin-top: 20px;
        color: white;
        border-radius: 20px;
    }
    .error {
        color: #ff0062;
        margin-top: 10px;
        font-size: 0.8em;
        font-weight: bold;
    }

    button:disabled{
        background: #ccc; /* Colore grigio per indicare che è disabilitato */
        cursor: not-allowed; /* Mostra un'icona di divieto */
        opacity: 0.6; /* Rende il bottone più trasparente */
    }
    
    .back {
        overflow: hidden;
        position: relative;
        display: inline-block;
        text-decoration: none;
        font-size:10px;
        color: #555;
        font-weight: bolder;
    }
    .back::before,
    .back::after{
        content: '';
        position: absolute;
        width: 100%;
        left: 0;
        }
    .back::before {
      background-color: #54b3d6;
      height: 2px;
      bottom: 0;
      transform-origin: 100% 50%;
      transform: scaleX(0);
      transition: transform .3s cubic-bezier(0.76, 0, 0.24, 1);
    }
    .back::after {
      content: attr(data-replace);
      height: 100%;
      top: 0;
      transform-origin: 100% 50%;
      transform: translate3d(200%, 0, 0);
      transition: transform .3s cubic-bezier(0.76, 0, 0.24, 1);
      color: #54b3d6;
    }

    .back:hover::before {
      transform-origin: 0% 50%;
      transform: scaleX(1);
    }
    .back:hover::after {
      transform: translate3d(0, 0, 0);
    }

    .back span {
      display: inline-block;
      transition: transform .3s cubic-bezier(0.76, 0, 0.24, 1);
    }
    .back:hover span{
        transform: translate3d(-200%, 0, 0);
    }
    


</style>