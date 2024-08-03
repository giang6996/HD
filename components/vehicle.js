const Vehicle = {
    data() {
      return {
        vehicles: [],
        newVehicle: {
          vehicleTypeId: '',
          vehicleName: '',
        },
        vehicleTypes: [],
        errorMessage: '',
        successMessage: ''
      };
    },
    created() {
      this.fetchVehicles();
      this.fetchVehicleTypes();
    },
    methods: {
      async fetchVehicles() {
        try {
          const user = JSON.parse(localStorage.getItem('user'));
          if (!user) {
            alert('Please log in to manage your vehicles');
            this.$router.push('/login');
            return;
          }
  
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
            console.error('Failed to fetch vehicles:', result.message);
          }
        } catch (error) {
          console.error('Error:', error);
        }
      },
  
      async fetchVehicleTypes() {
        try {
          const response = await fetch('./backend(OOP)/handler/vehicleHandler.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'getVehicleTypes'
            })
          });
  
          const result = await response.json();
          
          console.log(result);
          if (result.success) {
            this.vehicleTypes = result.data;
          } else {
            console.error('Failed to fetch vehicle types:', result.message);
          }

        } catch (error) {
          console.error('Error:', error);
        }
      },
  
      async createVehicle() {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
          alert('Please log in to add a vehicle');
          this.$router.push('/login');
          return;
        }
  
        if (!this.newVehicle.vehicleTypeId || !this.newVehicle.vehicleName) {
          this.errorMessage = 'Please fill in all fields';
          return;
        }
  
        try {
          const response = await fetch('./backend(OOP)/handler/vehicleHandler.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'createVehicle',
              vehicleTypeId: this.newVehicle.vehicleTypeId,
              vehicleName: this.newVehicle.vehicleName,
              accountId: user.id
            })
          });
  
          const result = await response.json();
          if (result.success) {
            this.successMessage = 'Vehicle added successfully';
            this.errorMessage = '';
            this.newVehicle.vehicleTypeId = '';
            this.newVehicle.vehicleName = '';
            this.fetchVehicles();
          } else {
            this.errorMessage = 'Failed to add vehicle: ' + result.message;
          }
        } catch (error) {
          console.error('Error:', error);
          this.errorMessage = 'An error occurred while adding the vehicle: ' + error.message;
        }
      },
  
      async deleteVehicle(vehicle) {
        try {
          const response = await fetch('./backend(OOP)/handler/vehicleHandler.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'deleteVehicle',
              id: vehicle.id
            })
          });
  
          const result = await response.json();
          if (result.success) {
            this.successMessage = 'Vehicle deleted successfully';
            this.errorMessage = '';
            this.$router.push('/vehicle')
          } else {
            this.errorMessage = 'Failed to delete vehicle: ' + result.message;
          }
        } catch (error) {
          console.error('Error:', error);
          this.errorMessage = 'An error occurred while deleting the vehicle: ' + error.message;
        }
      }
    },
    template: `
      <div>
        <h1>My Vehicles</h1>
        <ul v-if="vehicles.length">
          <li v-for="vehicle in vehicles" :key="vehicle.id">
            {{ vehicle.vehicleName }} (Type: {{ vehicle.typename }})
            <button @click="deleteVehicle(vehicle)">Delete</button>
          </li>
        </ul>
        <p v-else>No vehicles found.</p>
  
        <h2>Add New Vehicle</h2>
        <label for="vehicleTypeId">Vehicle Type:</label>
        <select id="vehicleTypeId" v-model="newVehicle.vehicleTypeId">
          <option v-for="type in vehicleTypes" :key="type.typeid" :value="type.typeid">{{ type.typename }}</option>
        </select>
        <label for="vehicleName">Vehicle Name:</label>
        <input type="text" id="vehicleName" v-model="newVehicle.vehicleName">
        <button @click="createVehicle">Add Vehicle</button>
        <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
        <p v-if="successMessage" class="success">{{ successMessage }}</p>
      </div>
    `
  };
  
  export default Vehicle;
  