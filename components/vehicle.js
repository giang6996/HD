const Vehicle = {
  data() {
    return {
      vehicles: [],
      newVehicle: {
        vehicleTypeId: '',
        vehicleName: '',
        licensePlate: ''
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

      if (!this.newVehicle.vehicleTypeId || !this.newVehicle.vehicleName || !this.newVehicle.licensePlate) {
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
            licensePlate: this.newVehicle.licensePlate,
            accountId: user.id
          })
        });

        const result = await response.json();
        if (result.success) {
          this.successMessage = 'Vehicle added successfully';
          this.errorMessage = '';
          this.newVehicle.vehicleTypeId = '';
          this.newVehicle.vehicleName = '';
          this.newVehicle.licensePlate = '';
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
          this.$router.go();
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
    <div class="container my-5">
      <h1 class="text-center mb-4">My Vehicles</h1>

      <ul v-if="vehicles.length" class="list-group">
        <li v-for="vehicle in vehicles" :key="vehicle.id" class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            {{ vehicle.vehicleName }} (Type: {{ vehicle.typename }}) - License Plate: {{ vehicle.licensePlate }}
          </div>
          <div>
            <button @click="deleteVehicle(vehicle)" class="btn btn-danger btn-sm">Delete</button>
          </div>
        </li>
      </ul>
      <p v-else class="text-danger text-center">No vehicles found.</p>

      <div class="card mt-4">
        <div class="card-body">
          <h2 class="card-title">Add New Vehicle</h2>
          <div class="mb-3">
            <label for="vehicleTypeId" class="form-label">Vehicle Type:</label>
            <select id="vehicleTypeId" v-model="newVehicle.vehicleTypeId" class="form-select">
              <option v-for="type in vehicleTypes" :key="type.typeid" :value="type.typeid">{{ type.typename }}</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="vehicleName" class="form-label">Vehicle Name:</label>
            <input type="text" id="vehicleName" v-model="newVehicle.vehicleName" class="form-control">
          </div>
          <div class="mb-3">
            <label for="licensePlate" class="form-label">License Plate:</label>
            <input type="text" id="licensePlate" v-model="newVehicle.licensePlate" class="form-control">
          </div>
          <button @click="createVehicle" class="btn btn-primary">Add Vehicle</button>
          <p v-if="errorMessage" class="text-danger mt-3">{{ errorMessage }}</p>
          <p v-if="successMessage" class="text-success mt-3">{{ successMessage }}</p>
        </div>
      </div>
    </div>
  `
};

export default Vehicle;
