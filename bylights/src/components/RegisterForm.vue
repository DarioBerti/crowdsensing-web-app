<template>
    <div>
        <form @submit.prevent="handleSubmit">
            <section>
                <h1>REGISTER</h1>
            </section>
            <section>
                <label>Nome:</label>
                <input type="text" required v-model="name">
                <label>Cognome:</label>
                <input type="text" required v-model="surname">
                <label>Email:</label>
                <input type="email" required v-model="email">
                <label>Password</label>
                <input type="password" required v-model="password">
                <div v-if="this.passwordError" class="error">
                    {{ passwordError }}
                </div>
                <p>Already have an account?</p>
                <p class="SignupLink">Signup</p>
            </section>
            <div class="submit">
                <button>Register</button>
            </div>
        </form>
    </div>
</template>

<script>
export default{
    name: 'SignupForm',
    components: {},
    data() {
        return{
            email: '',
            password: '',
            name: '',
            surname: '',
            passwordError: ''
        }
    },
    methods: {
        handleSubmit(){
            this.passwordError =  this.password.length < 6 ? 'the password must be at least 6 chars long!' : ''  
        }
    }
}
</script>

<style scoped>
    form {
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
    .registerLink{
        color: red;
        margin-left: 3%;
    }
    .SignupLink{
        color: red;
        margin-left: 3%;
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
</style>