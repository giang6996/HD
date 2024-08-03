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
      console.log(user.id);
      if (!user.id) {
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
    <div>
      <h1>Catalog</h1>
      <ul>
        <li v-for="lot in parkingLots" :key="lot.id">
          Slot name: {{ lot.slotName }} - Type ID: {{ lot.slotTypeId }} - Status: {{ lot.status }}
          <button @click="selectSlot(lot)">Select</button>
        </li>
      </ul>

      <div v-if="selectedSlot">
        <h2>Select Details for {{ selectedSlot.slotName }}</h2>
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" v-model="startdate">
        <label for="duration">Duration (days):</label>
        <input type="number" id="duration" v-model="duration">
        <button @click="createInvoice">Proceed to Payment</button>
        <button @click="selectedSlot = null">Cancel</button>
        <p v-if="errorMessage">{{ errorMessage }}</p>
      </div>
    </div>
  `
};

export default Catalog;
