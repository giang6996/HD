const Cart = {
    data() {
        return {
            cartItems: [],
            cartid: ''
        };
    },
    created() {
        this.fetchCartItems();
    },
    methods: {
        selectedItem(item){
            this.selectedItem = item;
        },

        async fetchCartItems() {
            try {
                const response = await fetch('database/API/api-cart.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    this.cartItems = result.cartItems;
                } else {
                    console.error('Failed to fetch cart items:', result.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },

        async deleteCartItems(item) {
            try {
                const response = await fetch('database/API/api-cart.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        slotid: item.parkingslotid,
                        cartid: item.cartid
                    })
                });
                const result = await response.json();
                if (result.success) {
                    this.$router.go();
                    alert('Delete item successfully!')
                } else {
                    console.error('Failed to delete cart items:', result.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    },

    computed: {
        totalCost() {
            return this.cartItems.reduce((total, item) => {
                // Assuming item.slotprice and item.duration are defined
                return total + (item.slotprice * item.duration);
            }, 0);
        }
    },

    template: `
        <div>
            <h1>Cart</h1>
            <ul>
                <li v-for="item in cartItems" :key="item.cartid">
                    Slot: {{ item.slotname }} - Start Date: {{ item.startdate }} - Duration: {{ item.duration }} - Price: {{ item.slotprice * item.duration }}$
                    <button @click="deleteCartItems(item)">Delete</button>
                </li>
            </ul>
            <h2>Total Cost: {{ totalCost }}$</h2>
        </div>
    `
};

export default Cart;