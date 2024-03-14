<template>
    <div>
        <section>
            <h1>SIGNUP</h1>
        </section>
        <section>
            <form>
                <label>Email:</label>
                <!--v-model tracks user input real-time-->
                <input type="email" required v-model="email">
                <label>Password</label>
                <input type="password" required v-model="password">
                <p>Don't have an account?</p>
                <p class="RegisterLink">Register</p>
            </form>
        </section>
        
    </div>
</template>

<script>
export default{
    name: 'SignupForm',
    components: {},
    data() {
        return{
            email: '',
            password: ''
        }
    },
    methods: {
    }
}
</script>

<style scoped>
    div {
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
    h1{
        color: #555;
    }
</style>