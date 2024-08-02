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
            // const response = await fetch('database/API/api-slots.php');
            const response = await fetch('./backend(OOP)/handler/parkingslotHandler.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                action: 'getParkingSlotAll'
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
        async createBooking() {
          if (!this.startdate || !this.duration) {
            this.errorMessage = 'Please fill in all field';
            return;
          }
    
          const user = JSON.parse(localStorage.getItem('user'));
          if (!user.data.id) {
            alert('Please log in to add items to your cart');
            this.$router.push('/login');
            return;
          }
    
          try {
            const response = await fetch('./backend(OOP)/handler/bookingHandler.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                accountId: user.data.id,  // Ensure this matches the user ID key in your login response
                parkingSlotId: this.selectedSlot.id,
                bookingTime: this.startdate,
                duration: this.duration,
                action: 'createBooking'
              })
            });
            const result = await response.json();
            if (result.success) {
              alert('Buying Completed');
              this.errorMessage = '';
              this.$router.push('/book');
            } else {
              alert('Failed to book: ' + result.message);
            }
          } catch (error) {
            console.error('Error:', error);
            this.errorMessage = 'An error occurred while adding to the cart: ' + error.message;
          }
        }
    },
    template: `
        <div>
            <h1>Catalog</h1>
            <ul>
                <li v-for="lot in parkingLots" :key="lot.id">
                    Slot name: {{ lot.slotName }} - Type ID: {{ lot.slotTypeId }} - Status: {{ lot.status }} - Price: 3.  99
                    <button @click="selectSlot(lot)">Select</button>
                </li>
            </ul>

            <div v-if="selectedSlot">
                <h2>Select Details for {{ selectedSlot.slotname }}</h2>
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" v-model="startdate">
                <label for="duration">Duration (days):</label>
                <input type="number" id="duration" v-model="duration">
                <button @click="createBooking">Book</button>
                <button @click="selectedSlot = null">Cancel</button>
                <p v-if="errorMessage">{{ errorMessage }}</p>
            </div>
        </div>
    `
};

export default Catalog;
