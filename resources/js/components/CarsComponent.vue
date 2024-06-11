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
import axios from 'axios';
import AlertComponent from './AlertComponent.vue'; 

export default {
  name: 'Cars',
  components: {
    AlertComponent,
  },
  data() {
    return {
      cars: [],
      newCar: {
        name: '',
        registration_number: '',
        is_registered: false
      },
      currentCar: {
        id: null,
        name: '',
        registration_number: '',
        is_registered: false
      }
    };
  },
  created() {
    this.fetchCars();
  },
  methods: {
    async fetchCars() {
      try {
        const response = await axios.get('/api/cars');
        this.cars = response.data;
      } catch (error) {
        this.$refs.alertComponent.showAlert('Error fetching cars', 'danger');
      }
    },
    async addCar() {
      try {
        const response = await axios.post('/api/cars', this.newCar);
        this.cars.push(response.data);
        this.newCar = { name: '', registration_number: '', is_registered: false };
        this.$refs.alertComponent.showAlert('Car added successfully', 'success');
      } catch (error) {
        this.$refs.alertComponent.showAlert('Error adding car', 'danger');
      }
    },
    confirmDelete(carId) {
      if (confirm('Are you sure you want to delete this car?')) {
        this.deleteCar(carId);
      }
    },
    async deleteCar(carId) {
      try {
        await axios.delete(`/api/cars/${carId}`);
        this.cars = this.cars.filter(car => car.id !== carId);
        this.$refs.alertComponent.showAlert('Car deleted successfully', 'success');
      } catch (error) {
        this.$refs.alertComponent.showAlert('Error deleting car', 'danger');
      }
    },
    openEditModal(car) {
      this.currentCar = { ...car };
      let modal = new bootstrap.Modal(document.getElementById('editCarModal'));
      modal.show();
    },
    async updateCar() {
      try {
        await axios.put(`/api/cars/${this.currentCar.id}`, this.currentCar);
        this.cars.findIndex(car => car.id === this.currentCar.id);
        this.fetchCars();
        this.$refs.alertComponent.showAlert('Car updated successfully', 'success');
      } catch (error) {
        this.$refs.alertComponent.showAlert('Error updating car', 'danger');
      }
    },
    openEditModal(car) {
      this.currentCar.id = car.id;
      this.currentCar.name = car.name;
      this.currentCar.registration_number = car.registration_number;
      this.currentCar.is_registered = car.is_registered === 1; 
      
      let modal = new bootstrap.Modal(document.getElementById('editCarModal'));
      modal.show();
    }
  }
};
</script>


<style scoped>
</style>
