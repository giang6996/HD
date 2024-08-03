const Book = {
    data() {
      return {
        bookItems: [],
        selectedItem: null,
        receiptDetails: null,
        showReceipt: false
      };
    },
    created() {
      this.fetchBookItems();
    },
    methods: {
      async fetchBookItems() {
        try {
          const user = JSON.parse(localStorage.getItem('user'));
          if (!user) {
            alert('Please log in to add items to your cart');
            this.$router.push('/login');
            return;
          }
  
          const response = await fetch('./backend(OOP)/handler/bookingHandler.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'getBookingDetailsByAccountId',
              accountId: user.id
            })
          });
  
          const result = await response.json();
          if (result.success) {
            this.bookItems = result.data;
          } else {
            console.error('Failed to fetch book items:', result.message);
          }
        } catch (error) {
          console.error('Error:', error);
        }
      },
  
      async deleteBookItems(item) {
        this.selectedItem = item;
  
        try {
          const response = await fetch('./backend(OOP)/handler/bookingHandler.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'cancelBooking',
              id: this.selectedItem.id,
              parkingSlotId: this.selectedItem.parkingSlotId
            })
          });
  
          const result = await response.json();
          if (result.success) {
            alert('Delete item successfully!');
            this.$router.push('/catalog');
          } else {
            console.error('Failed to delete book items:', result.message);
          }
        } catch (error) {
          console.error('Error:', error);
        }
      },
  
      async fetchReceiptDetails(item) {

        console.log(item)
        try {
          const response = await fetch('./backend(OOP)/handler/receiptHandler.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'getReceiptDetails',
              id: item.paymentId // Ensure this matches the receipt ID key
            })
          });
  
          const result = await response.json();
          if (result.success) {
            this.receiptDetails = result.data;
            this.showReceipt = true;
          } else {
            console.error('Failed to fetch receipt details:', result.message);
          }
        } catch (error) {
          console.error('Error:', error);
        }
      }
    },
  
    template: `
      <div>
        <h1>Book</h1>
        <ul v-if="bookItems != ''">
          <li v-for="item in bookItems" :key="item.bookid">
            Slot: {{ item.slotName }} - Start Date: {{ item.bookingTime }} - Duration: {{ item.duration }} Hours
            <button @click="deleteBookItems(item)">Delete</button>
            <button @click="fetchReceiptDetails(item)">View Receipt</button>
          </li>
        </ul>

        <h2 v-else class="text-danger"> There are no book items, please choose a slot </h2>
        <div v-if="showReceipt && receiptDetails">
          <h2>Receipt Details</h2>
          <p><strong>Username:</strong> {{ receiptDetails.customerName }}</p>
          <p><strong>Payment Amount:</strong> {{ receiptDetails.paymentAmount }}$</p>
          <p><strong>Receipt Date:</strong> {{ receiptDetails.receiptDate }}</p>
          <p><strong>Start Date:</strong> {{ receiptDetails.startDate }}</p>
          <p><strong>Duration:</strong> {{ receiptDetails.duration }} hours</p>
          <button @click="showReceipt = false">Close</button>
        </div>
      </div>
    `
  };
  
  export default Book;
  