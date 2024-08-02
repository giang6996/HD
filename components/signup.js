const Signup = {
    data() {
        return {
            // firstname: '',
            // lastname: '',
            username: '',
            email: '',
            // dateofbirth: '',
            // street: '',
            // district: '',
            // city: '',
            password: '',
            confirmPassword: '',
            errorMessage: {},
            successMessage: ''
        };
    },
    methods: {
        async signup() {
            // Reset error and success messages
            this.errorMessage = {};
            this.successMessage = '';

            // Validate inputs
            if (this.password !== this.confirmPassword) {
                this.errorMessage.password = 'Passwords do not match';
                return;
            }

            // Prepare data for API
            const userData = {
                // firstname: this.firstname,
                // lastname: this.lastname,
                customerName: this.username,
                customerEmail: this.email,
                // dateofbirth: this.dateofbirth,
                // street: this.street,
                // district: this.district,
                // city: this.city,
                customerPassword: this.password,
                authmode: 'signup'
            };

            try {
                // const response = await fetch('database/API/api-signup.php', {
                    const response = await fetch('./backend(OOP)/handler/accountHandler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(userData)
                });

                const result = await response.json();
                
                if (result.success) {
                    this.successMessage = result.message;
                    this.$router.push('/login');
                } else {
                    this.errorMessage = { general: result.message };
                }
            } catch (error) {
                console.error('Error:', error);
                this.errorMessage = { general: 'An error occurred while processing your request.' };
            }
        }
    },
    template: `
        <div>
            <h1>Sign Up</h1>
            <form @submit.prevent="signup">
            
                <label for="username">Username</label>
                <input type="text" id="username" v-model="username">
                <p v-if="errorMessage.username">{{errorMessage.username}}</p>

                <label for="email">Email</label>
                <input type="email" id="email" v-model="email">
                <p v-if="errorMessage.email">{{errorMessage.email}}</p>

                <label for="password">Password</label>
                <input type="password" id="password" v-model="password">
                <p v-if="errorMessage.password">{{errorMessage.password}}</p>

                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" v-model="confirmPassword">
                <p v-if="errorMessage.confirmPassword">{{errorMessage.confirmPassword}}</p>

                <button type="submit">Sign Up</button>
                <p v-if="errorMessage.general">{{errorMessage.general}}</p>
                <p v-if="successMessage">{{successMessage}}</p>
            </form>
        </div>
    `
};

export default Signup;

// // <label for="firstname">First Name</label>
                // <input type="text" id="firstname" v-model="firstname">
                // <p v-if="errorMessage.firstname">{{errorMessage.firstname}}</p>

                // <label for="lastname">Last Name</label>
                // <input type="text" id="lastname" v-model="lastname">
                // <p v-if="errorMessage.lastname">{{errorMessage.lastname}}</p>
                                // <label for="dateofbirth">Date of Birth</label>
                // <input type="date" id="dateofbirth" v-model="dateofbirth">
                // <p v-if="errorMessage.dateofbirth">{{errorMessage.dateofbirth}}</p>

                // <label for="street">Street</label>
                // <input type="text" id="street" v-model="street">
                // <p v-if="errorMessage.street">{{errorMessage.street}}</p>

                // <label for="district">District</label>
                // <input type="text" id="district" v-model="district">
                // <p v-if="errorMessage.district">{{errorMessage.district}}</p>

                // <label for="city">City</label>
                // <input type="text" id="city" v-model="city">
                // <p v-if="errorMessage.city">{{errorMessage.city}}</p>