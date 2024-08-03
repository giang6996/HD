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
      if (!user.id) {
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
        if (!user.id) {
          alert('Please log in to proceed');
          this.$router.push('/login');
          return;
        }

        // Create payment
        const paymentResponse = await fetch('./backend(OOP)/handler/paymentHandler.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            accountId: user.id,
            invoiceId: this.invoice.invoiceId,
            amount: 3.99,
            action: 'createPayment'
          })
        });

        const paymentResult = await paymentResponse.json();
        if (paymentResult.success) {
          this.successMessage = 'Payment completed successfully';

          // Create booking after successful payment
          const bookingResponse = await fetch('./backend(OOP)/handler/bookingHandler.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              accountId: user.id,
              parkingSlotId: this.invoice.parkingSlotId,
              bookingTime: this.invoice.bookDate,
              duration: this.invoice.duration,
              action: 'createBooking'
            })
          });

          const bookingResult = await bookingResponse.json();
          if (bookingResult.success) {
            this.successMessage += ' and Booking created successfully';

            // Delete the invoice after successful booking creation
            const deleteInvoiceResponse = await fetch('./backend(OOP)/handler/invoiceHandler.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                id: this.invoice.invoiceId,
                action: 'deleteInvoice'
              })
            });

            const deleteInvoiceResult = await deleteInvoiceResponse.json();
            if (deleteInvoiceResult.success) {
              this.successMessage += ' and Invoice deleted successfully';
            } else {
              this.errorMessage = 'Payment and booking completed, but failed to delete invoice: ' + deleteInvoiceResult.message;
            }
          } else {
            this.errorMessage = 'Payment completed but failed to create booking: ' + bookingResult.message;
          }
        } else {
          this.errorMessage = 'Failed to complete payment: ' + paymentResult.message;
        }
      } catch (error) {
        console.error('Error:', error);
        this.errorMessage = 'An error occurred while processing the payment: ' + error.message;
      }
    }
  },
  template: `
    <div v-if="invoice">
      <h1>Invoice Details</h1>
      <p><strong>Username:</strong> {{ invoice.customerName }}</p>
      <p><strong>Item Name:</strong> {{ invoice.itemName }}</p>
      <p><strong>Booking Date:</strong> {{ invoice.bookDate }}</p>
      <p><strong>Duration:</strong> {{ invoice.duration }} days</p>
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
