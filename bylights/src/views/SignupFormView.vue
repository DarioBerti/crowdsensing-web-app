<template>
    <div>
        <!--calls function 'handleSubmit' and does not reload the page-->
        <form @submit.prevent="handleSubmit">
            <section>
                <h1>SIGN UP</h1>
            </section>
            <section>
                <label>Email:</label>
                <!--v-model tracks user input real-time-->
                <input type="email" required v-model="email">
                <label>Password</label>
                <input type="password" required v-model="password">
                <div v-if="passwordError" class="error">
                    {{ passwordError }}
                </div>
                <div>
                    <p>Don't have an account?  </p>
                    <router-link :to="{ name: 'RegisterFormView'}"><p class="RegisterLink">Register</p></router-link>
                </div>
            </section>
            <div class="submit">
                <button>Sign up</button>
            </div>
        </form>
    </div>
</template>

<script>
import { ref } from 'vue'

export default{
    name: 'SignupFormView',
    setup(){
        const email = ref(null)
        const password = ref(null)
        const passwordError = ref('')

        const handleSubmit = () => {
            passwordError.value =  password.value.length < 6 ? 'the password must be at least 6 characters long :(' : ''  
        }

        return{
            email,
            password,
            passwordError,
            handleSubmit
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
    .RegisterLink{
        color: red;
        margin-left: 3%;
    }
    .RegisterLink:hover{
         text-decoration: underline;
         text-decoration-color: #ff0062;
    }
    h1{
        color: #555;
    }
    button {
        background: #0b6dff;
        border: 0;
        padding: 10px 20px;
        margin-top: 20px;
        color: white;
        border-radius: 20px;
    }
    .submit {
        text-align: center;
    }
    .error {
        color: #ff0062;
        margin-top: 10px;
        font-size: 0.8em;
        font-weight: bold;
    }
</style>