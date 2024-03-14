<template>
    <div>
        <form>
            <label>Email:</label>
            <!--v-model tracks user input real-time-->
            <input type="email" required v-model="email">
            <label>Password</label>
            <input type="password" required v-model="password">
            <p>Don't have an account?</p>
            <p class="registerLink">Register</p>
        </form>
    </div>
</template>

<script>
export default{
    data(){
        return{
            email: '',
            password: ''
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
    .registerLink{
        color: red;
        margin-left: 3%;
    }
</style>