const Signout = {

    created() {
        this.signOut();
      },

    methods: {  
        signOut() {
            // Clear user data from local storage
            localStorage.removeItem('user');
            // Redirect to the login page
            this.$router.push('/login');
          }
    },
}

export default Signout;
