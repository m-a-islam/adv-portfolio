<!-- frontend/src/views/AdminView.vue -->
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const companyName = ref('');
const jobTitle = ref('');
const startDate = ref('');
const endDate = ref('');
const description = ref('');
const message = ref('');

// --- List Data ---
const experiences = ref([]);
const isLoading = ref(true);
const isEditing = ref(false);
const editingId = ref(null);


// --- Methods ---

// This function now fetches all experiences to display them.
async function fetchExperiences() {
  isLoading.value = true;
  try {
    const response = await axios.get('http://localhost:8080/api/experience');
    experiences.value = response.data;
  } catch (error) {
    message.value = 'Error: Could not fetch experiences.';
  } finally {
    isLoading.value = false;
  }
}

// Function to start the editing process
function startEdit(experience) {
  isEditing.value = true;
  editingId.value = experience.id;

  // Populate the form with the data from the experience object
  companyName.value = experience.companyName;
  jobTitle.value = experience.jobTitle;
  // Dates need to be formatted as YYYY-MM-DD for the <input type="date">
  startDate.value = new Date(experience.startDate).toISOString().split('T')[0];
  endDate.value = experience.endDate ? new Date(experience.endDate).toISOString().split('T')[0] : '';
  description.value = experience.description;

  // Scroll to the form for better UX
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Function to cancel editing and clear the form
function cancelEdit() {
  isEditing.value = false;
  editingId.value = null;

  // Clear all form fields
  companyName.value = '';
  jobTitle.value = '';
  startDate.value = '';
  endDate.value = '';
  description.value = '';
  message.value = ''; // Also clear any messages
}



async function handleSubmit() {
  message.value = 'Submitting...';
  const payload = {
    companyName: companyName.value,
    jobTitle: jobTitle.value,
    startDate: startDate.value,
    endDate: endDate.value ? endDate.value : null,
    description: description.value,
  };

  try {
    if (isEditing.value) {
      // --- UPDATE LOGIC ---
      await axios.put(`http://localhost:8080/api/admin/experience/${editingId.value}`, payload);
      message.value = 'Success! Experience has been updated.';
    } else {
      // --- CREATE LOGIC (same as before) ---
      await axios.post('http://localhost:8080/api/admin/experience', payload);
      message.value = 'Success! New experience created.';
    }
    cancelEdit(); // Clear form and reset editing state
    await fetchExperiences(); // Refresh the list
  } catch (error) {
    message.value = 'Error: Could not submit the form.';
  }
}

// NEW function to handle deleting an experience
async function deleteExperience(id) {
  if (!confirm('Are you sure you want to delete this item?')) {
    return;
  }
  try {
    await axios.delete(`http://localhost:8080/api/admin/experience/${id}`);
    message.value = 'Experience deleted successfully.';
    // Refresh the list to remove the deleted item
    await fetchExperiences();
  } catch (error) {
    message.value = 'Error: Could not delete the experience.';
  }
}

// Fetch the initial list when the component is mounted
onMounted(fetchExperiences);

</script>

<template>
  <!-- Main container with padding and a max-width -->
  <div class="max-w-4xl mx-auto p-4 md:p-8">

    <!-- FORM SECTION with a nice card style -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
      <h1 class="text-2xl font-bold text-gray-800 mb-6">
        {{ isEditing ? 'Edit Experience' : 'Add New Experience' }}
      </h1>

      <!-- Display success/error messages -->
      <p v-if="message" class="mb-4 p-3 rounded-md bg-blue-100 text-blue-800">{{ message }}</p>

      <form @submit.prevent="handleSubmit" class="space-y-4">

        <!-- Grid layout for form fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="companyName" class="block text-sm font-medium text-gray-700">Company Name</label>
            <input id="companyName" type="text" v-model="companyName" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"/>
          </div>

          <div>
            <label for="jobTitle" class="block text-sm font-medium text-gray-700">Job Title</label>
            <input id="jobTitle" type="text" v-model="jobTitle" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"/>
          </div>

          <div>
            <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input id="startDate" type="date" v-model="startDate" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"/>
          </div>

          <div>
            <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
            <input id="endDate" type="date" v-model="endDate"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"/>
          </div>
        </div>

        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
          <textarea id="description" v-model="description" rows="4"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
        </div>

        <div class="flex space-x-4">
          <button type="submit"
                  class="flex-1 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ isEditing ? 'Update Experience' : 'Save Experience' }}
          </button>
          <!-- NEW: Cancel button, only shown when editing -->
          <button v-if="isEditing" @click="cancelEdit" type="button"
                  class="flex-1 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Cancel
          </button>
        </div>
      </form>
    </div>

    <!-- LIST SECTION -->
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-xl font-bold text-gray-800 mb-4">Manage Existing Experiences</h2>
      <div v-if="isLoading">Loading...</div>
      <ul v-else class="space-y-3">
        <li v-for="exp in experiences" :key="exp.id" class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
          <div>
            <p class="font-semibold text-gray-900">{{ exp.jobTitle }}</p>
            <p class="text-sm text-gray-600">{{ exp.companyName }}</p>
          </div>
          <div class="actions flex space-x-2">
            <!-- NEW: Edit Button -->
            <button @click="startEdit(exp)"
                    class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none">
              Edit
            </button>
            <button @click="deleteExperience(exp.id)"
                    class="px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none">
              Delete
            </button>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>