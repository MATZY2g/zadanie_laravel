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
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'Search',
    data() {
      return {
        cars: [],
        parts: [],
        selectedCar: '',
        filteredParts: [],
      };
    },
    methods: {
      async fetchParts() {
        try {
          const response = await axios.get('/api/parts');
          this.parts = response.data;
        } catch (error) {
          console.error('Error fetching parts:', error);
        }
      },
      async fetchCars() {
        try {
          const response = await axios.get('/api/cars');
          this.cars = response.data;
        } catch (error) {
          console.error('Error fetching cars:', error);
        }
      },
      filterParts() {
        this.filteredParts = this.parts.filter(part => part.car_id === this.selectedCar);
      },
    },
    async mounted() {
      await this.fetchCars();
      await this.fetchParts();
    },
  };
  </script>
  
  <style scoped>
  </style>
  