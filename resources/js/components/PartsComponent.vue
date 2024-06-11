<template>
  <div class="d-flex justify-content-center align-items-center mt-5">
    <div>
      <AlertComponent ref="alertComponent" />
      <h1 class="text-center mb-4">Parts</h1>
      <table class="table table-bordered" v-if="parts.length">
        <thead>
          <tr>
            <th>Name</th>
            <th>Serial Number</th>
            <th>Car Id</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="part in parts" :key="part.id">
            <td>{{ part.name }}</td>
            <td>{{ part.serialnumber }}</td>
            <td>{{ part.car_id }}</td>
            <td>
              <button class="btn btn-danger me-2" @click="confirmDelete(part.id)">Delete</button>
              <button class="btn btn-warning" @click="openEditModal(part)">Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else>
        <p>Loading...</p>
      </div>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartModal">
        Add New Part
      </button>

      <!-- create part modal -->
      <div class="modal fade" id="addPartModal" tabindex="-1" aria-labelledby="addPartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="addPartModalLabel">Add New Part</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="addPart">
                <div class="mb-3">
                  <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="name" v-model="newPart.name" required>
                </div>
                <div class="mb-3">
                  <label for="serialnumber" class="form-label">Serial Number <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="serialnumber" v-model="newPart.serialnumber" required>
                </div>
                <div class="mb-3">
                  <label for="car_id" class="form-label">Car ID</label>
                  <select class="form-select" id="car_id" v-model="newPart.car_id">
                    <option value="">Select a car</option>
                    <option v-for="car in cars" :value="car.id">{{ car.name }}</option>
                  </select>
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


      <!-- edit part modal -->
      <div class="modal fade" id="editPartModal" tabindex="-1" aria-labelledby="editPartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="editPartModalLabel">Edit Part</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="updatePart">
                <div class="mb-3">
                  <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="edit_name" v-model="currentPart.name" required>
                </div>
                <div class="mb-3">
                  <label for="edit_serialnumber" class="form-label">Serial Number <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="edit_serialnumber" v-model="currentPart.serialnumber" required>
                </div>
                <div class="mb-3">
                  <label for="edit_car_id" class="form-label">Car</label>
                  <select class="form-select" id="edit_car_id" v-model="currentPart.car_id">
                    <option v-for="car in cars" :value="car.id">{{ car.name }}</option>
                  </select>
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
  name: 'Parts',
  components: {
    AlertComponent,
  },
  data() {
    return {
      parts: [],
      cars: [],
      newPart: {
        name: '',
        serialnumber: '',
        car_id: ''
      },
      currentPart: {
        id: null,
        name: '',
        serialnumber: '',
        car_id: ''
      }
    };
  },
  created() {
    this.fetchCars();
    this.fetchParts();
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
    async addPart() {
      try {
        const response = await axios.post('/api/parts', this.newPart);
        this.parts.push(response.data);
        this.newPart = { name: '', serialnumber: '', car_id: '' }; 
        this.$refs.alertComponent.showAlert('Part added successfully', 'success');
      } catch (error) {
        console.error('Error adding part', 'danger');
      }
    },
    confirmDelete(partId) {
      if (confirm('Are you sure you want to delete this part?')) {
        this.deletePart(partId);
      }
    },
    async deletePart(partId) {
      try {
        await axios.delete(`/api/parts/${partId}`);
        this.parts = this.parts.filter(part => part.id !== partId);
        this.$refs.alertComponent.showAlert('Part deleted successfully', 'success');
      } catch (error) {
        console.error('Error deleting part:', error);
      }
    },
    openEditModal(part) {
      this.currentPart = { ...part };
      let modal = new bootstrap.Modal(document.getElementById('editPartModal'));
      modal.show();
    },
    async updatePart() {
      try {
        await axios.put(`/api/parts/${this.currentPart.id}`, this.currentPart);
        this.parts.findIndex(part => part.id === this.currentPart.id);
        this.fetchParts()
        this.$refs.alertComponent.showAlert('Part updated successfully', 'success');
      } catch (error) {
        console.error('Error updating part:', error);
      }
    }
  }
};
</script>


<style scoped>
</style>
