const Signup = {
    data() {
      return {
        username: '',
        email: '',
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
  
        try {
          const response = await fetch('./backend(OOP)/handler/accountHandler.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              customerName: this.username,
              customerEmail: this.email,
              customerPassword: this.password,
              authmode: 'signup'
            })
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
      <div class="container my-5">
        <h1 class="text-center mb-4">Sign Up</h1>
        <form @submit.prevent="signup" class="w-50 mx-auto">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" v-model="username" class="form-control">
            <div v-if="errorMessage.username" class="text-danger">{{errorMessage.username}}</div>
          </div>
  
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" v-model="email" class="form-control">
            <div v-if="errorMessage.email" class="text-danger">{{errorMessage.email}}</div>
          </div>
  
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" v-model="password" class="form-control">
            <div v-if="errorMessage.password" class="text-danger">{{errorMessage.password}}</div>
          </div>
  
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" id="confirmPassword" v-model="confirmPassword" class="form-control">
            <div v-if="errorMessage.confirmPassword" class="text-danger">{{errorMessage.confirmPassword}}</div>
          </div>
  
          <button type="submit" class="btn btn-primary w-100">Sign Up</button>
          <div v-if="errorMessage.general" class="text-danger mt-3">{{errorMessage.general}}</div>
          <div v-if="successMessage" class="text-success mt-3">{{successMessage}}</div>
        </form>
  
        <p class="text-center mt-4">Already have an account? <router-link to="/login">Login</router-link></p>
      </div>
    `
  };
  
  export default Signup;
  