const Login = {
    data() {
        return {
            email: '',
            password: '',
            errorMessage: ''
        };
    },
    methods: {  

        validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },

        async login() {
            try {

                if (!this.email) {
                    this.errorMessage = 'Email is required';
                    return;
                }
                if (!this.password) {
                    this.errorMessage = 'Password is required';
                    return;
                }

                if (!this.validateEmail(this.email)) {
                    this.errorMessage = 'Invalid email format.';
                    return;
                }

                const response = await fetch('database/API/api-login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password
                    })
                });

                const result = await response.json();
                console.log(result);

                if (result.success) {
                    localStorage.setItem('user', JSON.stringify(result));
                    this.$router.push('/catalog');
                } else {
                    this.errorMessage = result.message;
                }
            } catch (error) {
                console.error('Error:', error);
                this.errorMessage = 'An error occurred while processing your request.' + error.message;
            }
        }
    },
    template: `
        <h1> Login </h1>
        <form method="post" @submit.prevent="login">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" v-model="email">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" v-model="password">
            <button type="submit">Login</button>

            <p v-if="errorMessage" style="color: red;">{{ errorMessage }}</p>
        </form>
    `
}

export default Login;
