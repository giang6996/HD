export default {
  data() {
    return {
      user: [],
      vehicles: [],
      showAccountInfo: true,
      showVehicleInfo: false,
      errorMessage: ''
    };
  },
  created() {
    this.fetchUserData();
  },
  methods: {
    fetchUserData() {
      const user = JSON.parse(localStorage.getItem('user'));

      if (localStorage.getItem("user") === null) {
        alert('Please log in to view your account details');
        this.$router.push('/login');
      } else {
        this.user = user;
        this.fetchVehicles();
      }
    },
    
    async fetchVehicles() {
      try {
        const response = await fetch('./backend(OOP)/handler/vehicleHandler.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'getVehiclesByAccountId',
            accountId: this.user.id
          })
        });

        const result = await response.json();
        if (result.success) {
          this.vehicles = result.data;
        } else {
          this.errorMessage = 'Failed to fetch vehicles: ' + result.message;
        }
      } catch (error) {
        console.error('Error:', error);
        this.errorMessage = 'An error occurred while fetching vehicles: ' + error.message;
      }
    },
    toggleAccountInfo() {
      if(this.showAccountInfo == false){
          this.showAccountInfo = !this.showAccountInfo;
          this.showVehicleInfo = false;
      }
      
    },
    toggleVehicleInfo() {
      if(this.showVehicleInfo == false){
          this.showVehicleInfo = !this.showVehicleInfo;
          this.showAccountInfo = false
      }
    }
  },
  template: `
    <div class="container my-5" v-if="user !== null">
      <h1 class="text-center mb-4">Home</h1>
      
      <div class="row">
        <div class="col-md-3">
          <div class="text-center mb-4 d-flex flex-column">
            <button class="btn btn-primary mb-2" @click="toggleAccountInfo">
              Account Information
            </button>
            <button class="btn btn-primary" @click="toggleVehicleInfo">
              Vehicle Information
            </button>
          </div>
        </div>

        <div class="col-md-9">
          <div v-if="showAccountInfo" class="card mb-4">
            <div class="card-body">
              <h2 class="card-title">Account Information</h2>
              <p><strong>Name:</strong> {{ user.customerName }}</p>
              <p><strong>Email:</strong> {{ user.customerEmail }}</p>
            </div>
          </div>

          <div v-if="showVehicleInfo" class="card mb-4">
            <div class="card-body">
              <h2 class="card-title">My Vehicles</h2>
              <ul v-if= "vehicles != ''" class="list-group">
                <li v-for="vehicle in vehicles" :key="vehicle.id" class="list-group-item">
                  {{ vehicle.vehicleName }} - {{vehicle.licensePlate}} (Type: {{ vehicle.typename }})
                </li>
              </ul>
              <p class="text-danger" v-else>No vehicles found.</p>

              <router-link class="link-primary" to="/vehicle">Manage your vehicles</router-link>
            </div>
          </div>

        </div>
      </div>
    </div>
  `
};