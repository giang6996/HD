const Catalog = {
  data() {
    return {
      parkingLots: [],
      selectedSlot: null,
      startdate: '',
      duration: 1,
      errorMessage: ''
    };
  },
  created() {
    this.fetchParkingLots();
  },

  methods: {
    async fetchParkingLots() {
      try {
        const response = await fetch('./backend(OOP)/handler/parkingslotHandler.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'getParkingSlotAvailable'
          })
        });

        const result = await response.json();
        if (result.success) {
          this.parkingLots = result.data;
        } else {
          console.error('Failed to fetch parking lots:', result.message);
        }
      } catch (error) {
        console.error('Error:', error);
      }
    },
    selectSlot(slot) {
      this.selectedSlot = slot;
    },
    
    async createInvoice() {
      if (!this.startdate || !this.duration) {
        this.errorMessage = 'Please fill in all fields';
        return;
      }

      const user = JSON.parse(localStorage.getItem('user'));

      if (!user) {
        alert('Please log in to proceed');
        this.$router.push('/login');
        return;
      }

      try {
        const invoiceResponse = await fetch('./backend(OOP)/handler/invoiceHandler.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            accountId: user.id,
            parkingSlotId: this.selectedSlot.id,
            bookingDate: this.startdate,
            duration: this.duration,
            amount: this.selectedSlot.price,
            action: 'createInvoice'
          })
        });
        const invoiceResult = await invoiceResponse.json();
        if (invoiceResult.success) {
          this.$router.push('/Payment' );
        } else {
          alert('Failed to create invoice: ' + invoiceResult.message);
        }
      } catch (error) {
        console.error('Error:', error);
        this.errorMessage = 'An error occurred while creating the invoice: ' + error.message;
      }
    }
  },
  template: `
    <div class="container my-5">
    <h1 class="text-center mb-4">Catalog</h1>
    <div class="d-flex flex-wrap justify-content-around">
      <div v-for="lot in parkingLots" :key="lot.id" class="card m-2 shadow-lg" style="width: 18rem;">
        <div class="card-body text-center">
          <h5 class="card-title">Slot name: {{ lot.slotName }}</h5>
          <h6 class="card-subtitle mb-2 text-muted">Type ID: {{ lot.slotTypeId }}</h6>
          <p class="card-text">Status: {{ lot.status ? 'Available' : 'Not Available' }}</p>
          <p class="card-text">Price: {{ lot.price }}</p>
          <button @click="selectSlot(lot)" class="btn btn-primary">Select</button>
        </div>
      </div>
    </div>

    <div v-if="selectedSlot" class="mt-4">
      <h2 class="mb-3">Select Details for {{ selectedSlot.slotName }}</h2>
      <div class="mb-3">
        <label for="startDate" class="form-label">Start Date:</label>
        <input type="datetime-local" id="startDate" v-model="startdate" class="form-control">
      </div>
      <div class="mb-3">
        <label for="duration" class="form-label">Duration (hours):</label>
        <input type="number" id="duration" v-model="duration" class="form-control">
      </div>
      <div class="d-flex justify-content-between">
        <button @click="createInvoice" class="btn btn-success">Proceed to Payment</button>
        <button @click="selectedSlot = null" class="btn btn-secondary">Cancel</button>
      </div>
      <p v-if="errorMessage" class="text-danger mt-3">{{ errorMessage }}</p>
    </div>
  </div>


  `
};

export default Catalog;
