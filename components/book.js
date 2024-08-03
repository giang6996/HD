const Book = {
    data() {
        return {
            bookItems: [],
            bookid: ''
        };
    },
    created() {
        this.fetchBookItems();
    },
    methods: {

        async fetchBookItems() {
            try {

                const user = JSON.parse(localStorage.getItem('user'));
                if (!user.id) {
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
                            action: 'getBookingDetailsByAccountId',  // Ensure this matches the action you're using in your bookingHandler.php
                            accountId: user.id,  // Ensure this matches the user ID key in your login response
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
                    this.$router.go();
                    alert('Delete item successfully!')
                } else {
                    console.error('Failed to delete book items:', result.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    },

    computed: {
        // totalCost() {
        //     return this.bookItems.reduce((total, item) => {
        //         // Assuming item.slotprice and item.duration are defined
        //         return total + (item.slotprice * item.duration);
        //     }, 0);
        // }
    },

    template: `
        <div>
            <h1>Book</h1>
            <ul>
                <li v-for="item in bookItems" :key="item.bookid">
                    Slot: {{ item.slotName }} - Start Date: {{ item.bookingTime }} - Duration: {{ item.duration }} Hours - Price: {{ item.slotprice * item.duration }}$
                    <button @click="deleteBookItems(item)">Delete</button>
                </li>
            </ul>
            <h2>Total Cost: {{ totalCost }}$</h2>
        </div>
    `
};

export default Book;