<!-- frontend/src/views/CvView.vue -->
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Create a reactive variable to store our list of experiences.
const experiences = ref([]);
const isLoading = ref(true);
const error = ref(null);

// This function fetches the data from our Symfony API.
async function fetchExperiences() {
  try {
    const response = await axios.get('http://localhost:8080/api/experience');
    experiences.value = response.data;
  } catch (err) {
    console.error('Failed to fetch experiences:', err);
    error.value = 'Could not load work experience.';
  } finally {
    isLoading.value = false;
  }
}

// The onMounted hook runs this code as soon as the component is added to the page.
onMounted(() => {
  fetchExperiences();
});
</script>

<template>
  <div class="cv-container">
    <h1>Work Experience</h1>

    <div v-if="isLoading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="!isLoading && experiences.length > 0">
      <!-- We loop through each 'exp' in the 'experiences' array -->
      <div v-for="exp in experiences" :key="exp.id" class="experience-item">
        <h3>{{ exp.jobTitle }} at {{ exp.companyName }}</h3>
        <p class="dates">
          {{ new Date(exp.startDate).getFullYear() }} -
          {{ exp.endDate ? new Date(exp.endDate).getFullYear() : 'Present' }}
        </p>
        <p class="description">{{ exp.description }}</p>
      </div>
    </div>

    <div v-if="!isLoading && experiences.length === 0">
      <p>No work experience has been added yet.</p>
    </div>
  </div>
</template>

<style scoped>
.cv-container {
  max-width: 800px;
  margin: auto;
  padding: 20px;
}
.experience-item {
  margin-bottom: 30px;
  border-left: 3px solid #42b983;
  padding-left: 15px;
}
.dates {
  font-style: italic;
  color: #666;
}
.error {
  color: red;
}
</style>