const Payment = {
  data() {
    return {
      invoice: null,
      cardNumber: '',
      cardExpiry: '',
      cardCVV: '',
      errorMessage: '',
      successMessage: ''
    };
  },
  created() {
    this.fetchInvoiceDetails();
  },

  
  methods: {
    async fetchInvoiceDetails() {
      const user = JSON.parse(localStorage.getItem('user'));
      if (!user) {
        alert('Please log in to proceed');
        this.$router.push('/login');
        return;
      }

      try {
        const response = await fetch('./backend(OOP)/handler/invoiceHandler.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            accountId: user.id,
            action: 'getUserInvoice'
          })
        });

        const result = await response.json();
        if (result.success) {
          this.invoice = result.data;
        } else {
          this.errorMessage = 'Failed to fetch invoice details: ' + result.message;
        }
      } catch (error) {
        console.error('Error:', error);
        this.errorMessage = 'An error occurred while fetching the invoice details: ' + error.message;
      }
    },

    async completePayment() {
      if (!this.cardNumber || !this.cardExpiry || !this.cardCVV) {
        this.errorMessage = 'Please fill in all payment fields';
        return;
      }
    
      try {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
          alert('Please log in to proceed');
          this.$router.push('/login');
          return;
        }
    
        const paymentData = {
          accountId: user.id,
          amount: this.invoice.amount,
          action: 'createPayment'
        };
    
        // Create payment
        const paymentResult = await this.apiRequest('./backend(OOP)/handler/paymentHandler.php', paymentData);
        if (!paymentResult.success) {
          this.errorMessage = 'Failed to complete payment: ' + paymentResult.message;
          return;
        }
        this.successMessage = 'Payment completed successfully';
    
        // Create booking after successful payment
        const bookingData = {
          accountId: user.id,
          paymentId: paymentResult.paymentId,
          parkingSlotId: this.invoice.parkingSlotId,
          bookingTime: this.invoice.bookDate,
          duration: this.invoice.duration,
          action: 'createBooking'
        };
        const bookingResult = await this.apiRequest('./backend(OOP)/handler/bookingHandler.php', bookingData);
        if (!bookingResult.success) {
          this.errorMessage = 'Payment completed but failed to create booking: ' + bookingResult.message;
          return;
        }
        this.successMessage += ' and Booking created successfully';
    
        // Delete the invoice after successful booking creation
        const deleteInvoiceData = {
          id: this.invoice.invoiceId,
          action: 'deleteInvoice'
        };
        const deleteInvoiceResult = await this.apiRequest('./backend(OOP)/handler/invoiceHandler.php', deleteInvoiceData);
        if (!deleteInvoiceResult.success) {
          this.errorMessage = 'Payment and booking completed, but failed to delete invoice: ' + deleteInvoiceResult.message;
          return;
        }
        this.successMessage += ' and Invoice deleted successfully';
    
        // Create receipt after successful invoice deletion
        const receiptData = {
          paymentId: paymentResult.paymentId,
          accountId: user.id,
          receiptDate: new Date().toISOString().split('T')[0], // Today's date
          amount: this.invoice.amount,
          startDate: this.invoice.bookDate,
          duration: this.invoice.duration,
          action: 'createReceipt'
        };
        const receiptResult = await this.apiRequest('./backend(OOP)/handler/receiptHandler.php', receiptData);
        if (!receiptResult.success) {
          this.errorMessage = 'Payment, booking, and invoice deletion completed, but failed to create receipt: ' + receiptResult.message;
          return;
        }
        this.successMessage += ' and Receipt created successfully';
    
      } catch (error) {
        console.error('Error:', error);
        this.errorMessage = 'An error occurred while processing the payment: ' + error.message;
      }
    },


    async apiRequest(url, data) {
      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        });
        return await response.json();
      } catch (error) {
        console.error('Error:', error);
        return { success: false, message: 'An error occurred while making the request.' };
      }
    },
  },
  template: `
    <div v-if="invoice">
      <h1>Invoice Details</h1>
      <p><strong>Username:</strong> {{ invoice.customerName }}</p>
      <p><strong>Item Name:</strong> {{ invoice.itemName }}</p>
      <p><strong>Booking Date:</strong> {{ invoice.bookDate }}</p>
      <p><strong>Duration:</strong> {{ invoice.duration }} hours</p>
      <p><strong>Pay Amount:</strong> {{ invoice.amount}}$</p>


      <h2>Payment Details</h2>
      <label for="cardNumber">Card Number:</label>
      <input type="text" id="cardNumber" v-model="cardNumber">
      <label for="cardExpiry">Card Expiry:</label>
      <input type="text" id="cardExpiry" v-model="cardExpiry">
      <label for="cardCVV">Card CVV:</label>
      <input type="text" id="cardCVV" v-model="cardCVV">
      <button @click="completePayment">Complete Payment</button>
      <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
      <p v-if="successMessage" class="success">{{ successMessage }}</p>
    </div>
  `
};

export default Payment;
