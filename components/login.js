const Login = {
    inject: ['auth'],
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
  
          const response = await fetch('./backend(OOP)/handler/accountHandler.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              customerEmail: this.email,
              customerPassword: this.password,
              authmode: 'login'
            })
          });
  
          const result = await response.json();
  
          if (result.success) {
            const userData = {
              id: result.data.id,
              customerName: result.data.customerName,
              customerEmail: result.data.customerEmail
            };
            localStorage.setItem('user', JSON.stringify(userData));
            this.auth.isLoggedIn = true;
            this.auth.user = userData;
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
        <h1 class="text-center my-4">Login</h1>
        <div class="container">
        <form method="post" @submit.prevent="login" class="mx-auto" style="max-width: 400px;">
            <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" v-model="email" class="form-control" required>
            </div>
            <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" v-model="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <p v-if="errorMessage" class="text-danger mt-3">{{ errorMessage }}</p>
        </form>
        <p class="text-center mt-3">Don't have an account? <router-link to="/signup" class="link-primary">Sign Up</router-link></p>
        </div>
    `
  };
  
  export default Login;
  