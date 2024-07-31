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
            const response = await fetch('database/API/api-slots.php');
            const result = await response.json();
            if (result.success) {
              this.parkingLots = result.parkingslots;
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
        async addToCart() {
          if (!this.startdate || !this.duration) {
            this.errorMessage = 'Please fill in all field';
            return;
          }
    
          const user = JSON.parse(localStorage.getItem('user'));
          if (!user) {
            alert('Please log in to add items to your cart');
            this.$router.push('/login');
            return;
          }
    
          try {
            const response = await fetch('database/API/api-cart.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                userid: user.userid,  // Ensure this matches the user ID key in your login response
                slotid: this.selectedSlot.parkingslotid,
                startdate: this.startdate,
                duration: this.duration,
                slotprice: this.selectedSlot.price,
              })
            });
            const result = await response.json();
            if (result.success) {
              alert('Added to cart');
              this.errorMessage = '';
              this.$router.push('/cart');
            } else {
              alert('Failed to add to cart: ' + result.message);
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
                <li v-for="lot in parkingLots" :key="lot.parkingslotid">
                    {{ lot.slotname }} - Type ID: {{ lot.typeid }} - Location ID: {{ lot.locationid }} - Price: {{ lot.price }}
                    <button @click="selectSlot(lot)">Select</button>
                </li>
            </ul>

            <div v-if="selectedSlot">
                <h2>Select Details for {{ selectedSlot.slotname }}</h2>
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" v-model="startdate">
                <label for="duration">Duration (days):</label>
                <input type="number" id="duration" v-model="duration">
                <button @click="addToCart">Add to Cart</button>
                <button @click="selectedSlot = null">Cancel</button>
                <p v-if="errorMessage">{{ errorMessage }}</p>
            </div>
        </div>
    `
};

export default Catalog;
