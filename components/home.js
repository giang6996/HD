export default {
    data() {
      return {
        user: null,
        vehicles: [],
        showAccountInfo: true,
        showVehicleInfo: false,
        errorMessage: ''
      };
    },
    created() {
      this.fetchUserData();
      this.fetchVehicles();
    },
    methods: {
      fetchUserData() {
        const user = JSON.parse(localStorage.getItem('user'));
        if (user) {
          this.user = user;
        } else {
          this.$router.push('/login');
        }
      },
      
      async fetchVehicles() {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
          this.errorMessage = 'User not logged in';
          return;
        }
  
        try {
          const response = await fetch('./backend(OOP)/handler/vehicleHandler.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'getVehiclesByAccountId',
              accountId: user.id
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
      <div class="container my-5">
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
                    {{ vehicle.vehicleName }} (Type: {{ vehicle.typename }})
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
  