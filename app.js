export default {
    inject: ['auth'],
    computed: {
      isLoggedIn() {
        return this.auth.isLoggedIn;
      },
      userName() {
        const user = JSON.parse(localStorage.getItem('user'));
        return user ? user.customerName : '';
      }
    },
    template: `
      <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container">
            <a class="navbar-brand" href="/">My App</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav me-auto">
                <li class="nav-item">
                  <router-link class="nav-link" to="/">Home</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link" to="/catalog">Catalog</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link" to="/book">Book</router-link>
                </li>
                <li class="nav-item">
                  <router-link class="nav-link" to="/payment">Payments</router-link>
                </li>
              </ul>
              <ul class="navbar-nav ms-auto">
                <li class="nav-item my-auto" v-if="isLoggedIn">
                  <span class="navbar-text fw-bold">Welcome, {{ userName }}</span>
                </li>
                <li class="nav-item" v-if="!isLoggedIn">
                  <router-link class="nav-link" to="/login">Login</router-link>
                </li>
                <li class="nav-item" v-if="isLoggedIn">
                  <router-link class="nav-link" to="/signout">Signout</router-link>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <router-view></router-view>
      </div>
    `
  };
  