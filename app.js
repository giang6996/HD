export default {
    template: `
        <div>
            <nav>
                <ul style="list-style: none; padding: 0; display: flex; gap: 10px;">
                    <li><router-link to="/">Home</router-link></li>
                    <li><router-link to="/catalog">Catalog</router-link></li>
                    <li><router-link to="/book">Book</router-link></li>
                    <li><router-link to="/login">Login</router-link></li>
                    <li><router-link to="/payment">Payments</router-link></li>
                </ul>
            </nav>
            <router-view></router-view>
        </div>
    `
};