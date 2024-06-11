<template>
  <div class="d-flex justify-content-center align-items-center mt-5">
    <div>
      <AlertComponent ref="alertComponent" />
      <h1 class="text-center mb-4">Search</h1>
      <div style="display: flex;">
        <select v-model="selectedCar" class="form-select" aria-label="Default select example" style="margin-right: 10px;" @change="filterParts">
          <option value="" selected>-- Select car --</option>
          <option v-for="car in cars" :key="car.id" :value="car.id">{{ car.name }}</option>
        </select>
      </div>
      <div v-if="filteredParts.length > 0" class="mt-4">
        <h2 class="text-center mb-4">Parts for Selected Car</h2>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Part Name</th>
              <th scope="col">Serial Number</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(part, index) in filteredParts" :key="part.id">
              <th scope="row">{{ index + 1 }}</th>
              <td>{{ part.name }}</td>
              <td>{{ part.serialnumber }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else-if="selectedCar !== ''" class="mt-4">
        <h3 class="text-center mb-4">No parts for Selected Car</h3>
      </div>
      <div v-else class="mt-4">
        <h3 class="text-center mb-4">Select car</h3>
      </div>
    </div>
  </div>
</template>
  
  
<script>
  import axios from 'axios';
  
  export default {
    name: 'Search', // Názov komponentu
    data() {
      return {
        cars: [], // Zoznam áut
        parts: [], // Zoznam dielov
        selectedCar: '', // Vybrané auto
        filteredParts: [], // Filtrované diely podľa vybraného auta
      };
    },
    methods: {
      // Metóda na načítanie dielov z API
      async fetchParts() {
        try {
          const response = await axios.get('/api/parts'); // Požiadavka na API pre načítanie dielov
          this.parts = response.data; // Uloženie dielov do data vlastnosti
        } catch (error) {
          console.error('Error fetching parts:', error); // Výpis chyby do konzoly v prípade zlyhania
        }
      },
      // Metóda na načítanie áut z API
      async fetchCars() {
        try {
          const response = await axios.get('/api/cars'); // Požiadavka na API pre načítanie áut
          this.cars = response.data; // Uloženie áut do data vlastnosti
        } catch (error) {
          console.error('Error fetching cars:', error); // Výpis chyby do konzoly v prípade zlyhania
        }
      },
      // Metóda na filtrovanie dielov podľa vybraného auta
      filterParts() {
        this.filteredParts = this.parts.filter(part => part.car_id === this.selectedCar); // Filtrovanie dielov podľa car_id
      },
    },
    // Životný cyklus komponentu - vykoná sa po pripojení komponentu do DOM
    async mounted() {
      await this.fetchCars(); // Načítanie áut po pripojení komponentu
      await this.fetchParts(); // Načítanie dielov po pripojení komponentu
    },
  };
</script>

<style scoped> 
</style>
  