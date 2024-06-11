<template>
  <div class="d-flex justify-content-center align-items-center mt-5">
    <div>
      <AlertComponent ref="alertComponent" />
      <h1 class="text-center mb-4">Cars</h1>
      <table class="table table-bordered" v-if="cars.length">
        <thead>
          <tr>
            <th>Name</th>
            <th class="d-none d-lg-table-cell">Registration Number</th>
            <th class="d-none d-lg-table-cell">Is Registered</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="car in cars" :key="car.id">
            <td>{{ car.name }}</td>
            <td class="d-none d-lg-table-cell">{{ car.registration_number }}</td>
            <td class="d-none d-lg-table-cell">{{ car.is_registered ? 'Yes' : 'No' }}</td>
            <td>
              <button class="btn btn-danger me-2" @click="confirmDelete(car.id)">Delete</button>
              <button class="btn btn-warning" @click="openEditModal(car)">Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else>
        <p>Loading...</p>
      </div>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarModal">
        Add New Car
      </button>

      <!-- car create modal -->
      <div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="addCarModalLabel">Add New Car</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="addCar">
                <div class="mb-3">
                  <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="name" v-model="newCar.name" required>
                </div>
                <div class="mb-3">
                  <label for="registration_number" class="form-label">Registration Number</label>
                  <input type="text" class="form-control" id="registration_number" v-model="newCar.registration_number" :required="newCar.is_registered">
                </div>
                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="is_registered" v-model="newCar.is_registered">
                  <label class="form-check-label" for="is_registered">Is Registered</label>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Create car</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- car edit modal -->
      <div class="modal fade" id="editCarModal" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="editCarModalLabel">Edit Car</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="updateCar">
                <div class="mb-3">
                  <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="edit_name" v-model="currentCar.name" required>
                </div>
                <div class="mb-3 d-none d-lg-block">
                  <label for="edit_registration_number" class="form-label">Registration Number</label>
                  <input type="text" class="form-control" id="edit_registration_number" v-model="currentCar.registration_number" :required="currentCar.is_registered === 'yes'">
                </div>
                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="edit_is_registered" v-model="currentCar.is_registered">
                  <label class="form-check-label" for="edit_is_registered">Is Registered</label>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>      
    </div>
  </div>
</template>


<script>
import axios from 'axios'; // Importovanie axios pre HTTP požiadavky
import AlertComponent from './AlertComponent.vue'; // Importovanie komponentu AlertComponent

export default {
  name: 'Cars', // Názov komponentu
  components: {
    AlertComponent, // Definovanie AlertComponent ako vnútorný komponent
  },
  data() {
    return {
      cars: [], // Zoznam áut
      newCar: { // Nové auto na pridanie
        name: '',
        registration_number: '',
        is_registered: false
      },
      currentCar: { // Aktuálne upravované auto
        id: null,
        name: '',
        registration_number: '',
        is_registered: false
      }
    };
  },
  created() {
    // Životný cyklus komponentu - vykoná sa pri vytvorení komponentu
    this.fetchCars(); // Načítanie áut
  },
  methods: {
    // Metóda na načítanie áut z API
    async fetchCars() {
      try {
        const response = await axios.get('/api/cars'); // Požiadavka na API pre načítanie áut
        this.cars = response.data; // Uloženie áut do data vlastnosti
      } catch (error) {
        this.$refs.alertComponent.showAlert('Error fetching cars', 'danger'); // Zobrazenie alertu o chybe
      }
    },
    // Metóda na pridanie nového auta
    async addCar() {
      try {
        const response = await axios.post('/api/cars', this.newCar); // Požiadavka na API pre pridanie auta
        this.cars.push(response.data); // Pridanie nového auta do zoznamu
        this.newCar = { name: '', registration_number: '', is_registered: false }; // Resetovanie formulára
        this.$refs.alertComponent.showAlert('Car added successfully', 'success'); // Zobrazenie alertu o úspechu
      } catch (error) {
        this.$refs.alertComponent.showAlert('Error adding car', 'danger'); // Zobrazenie alertu o chybe
      }
    },
    // Metóda na potvrdenie zmazania auta
    confirmDelete(carId) {
      if (confirm('Are you sure you want to delete this car?')) { // Potvrdenie zmazania
        this.deleteCar(carId); // Zmazanie auta
      }
    },
    // Metóda na zmazanie auta
    async deleteCar(carId) {
      try {
        await axios.delete(`/api/cars/${carId}`); // Požiadavka na API pre zmazanie auta
        this.cars = this.cars.filter(car => car.id !== carId); // Odstránenie auta zo zoznamu
        this.$refs.alertComponent.showAlert('Car deleted successfully', 'success'); // Zobrazenie alertu o úspechu
      } catch (error) {
        this.$refs.alertComponent.showAlert('Error deleting car', 'danger'); // Zobrazenie alertu o chybe
      }
    },
    // Metóda na otvorenie modálneho okna na úpravu auta
    openEditModal(car) {
      this.currentCar = { ...car }; // Nastavenie aktuálne upravovaného auta
      let modal = new bootstrap.Modal(document.getElementById('editCarModal')); // Získanie modálneho okna podľa ID
      modal.show(); // Zobrazenie modálneho okna
    },
    // Metóda na aktualizáciu auta
    async updateCar() {
      try {
        await axios.put(`/api/cars/${this.currentCar.id}`, this.currentCar); // Požiadavka na API pre aktualizáciu auta
        this.cars.findIndex(car => car.id === this.currentCar.id); // Aktualizácia auta v zozname (môže byť redundantné)
        this.fetchCars(); // Aktualizácia zoznamu áut
        this.$refs.alertComponent.showAlert('Car updated successfully', 'success'); // Zobrazenie alertu o úspechu
      } catch (error) {
        this.$refs.alertComponent.showAlert('Error updating car', 'danger'); // Zobrazenie alertu o chybe
      }
    }
  }
};
</script>


<style scoped>
</style>
