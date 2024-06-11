<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Cars system</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="#" :class="{ active: currentTab === 'cars' }" @click.prevent="navigate('cars')">Cars</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" :class="{ active: currentTab === 'parts' }" @click.prevent="navigate('parts')">Parts</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" :class="{ active: currentTab === 'search'}" @click.prevent="navigate('search')">Search</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="content mt-4">
      <component :is="currentTabComponent"></component>
    </div>
  </div>
</template>

<script>
import Cars from './CarsComponent.vue';
import Parts from './PartsComponent.vue';
import Search from './SearchComponent.vue';

export default {
  components: {
    Cars,
    Parts,
    Search
  },
  data() {
    return {
      currentTab: 'cars'
    };
  },
  computed: {
    currentTabComponent() {
      return this.currentTab === 'cars' ? 'Cars' : (this.currentTab === 'parts' ? 'Parts' : 'Search');
    }
  },
  methods: {
    navigate(tab) {
      this.currentTab = tab;
    }
  }
};
</script>

<style scoped>
.nav-link.active {
  font-weight: bold;
  color: #0056b3 !important;
}
</style>
