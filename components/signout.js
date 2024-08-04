const Signout = {
  inject: ['auth'],
  created() {
    this.signOut();
  },
  methods: {
    signOut() {
      // Clear user data from local storage
      localStorage.removeItem('user');
      // Update the global state
      this.auth.isLoggedIn = false;
      this.auth.user = null;
      // Redirect to the login page
      this.$router.push('/login');
    }
  }
};

export default Signout;
