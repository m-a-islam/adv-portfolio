<!-- frontend/src/views/AdminView.vue -->
<script setup>
import { ref } from 'vue';
import axios from 'axios';

const companyName = ref('');
const jobTitle = ref('');
const startDate = ref('');
const endDate = ref('');
const description = ref('');
const message = ref('');

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
    const response = await axios.post('http://localhost:8080/api/admin/experience', payload);
    console.log('Success:', response.data);
    message.value = `Success! New experience with ID ${response.data.id} created.`;
    companyName.value = '';
    jobTitle.value = '';
    startDate.value = '';
    endDate.value = '';
    description.value = '';
  } catch (error) {
    console.error('Error submitting form:', error);
    message.value = 'Error: Could not submit the form. Check console for details.';
  }
}
</script>

<template>
  <div class="admin-form">
    <h1>Add New Experience</h1>
    <form @submit.prevent="handleSubmit">
      <div>
        <label for="companyName">Company Name:</label>
        <input id="companyName" type="text" v-model="companyName" required />
      </div>
      <div>
        <label for="jobTitle">Job Title:</label>
        <input id="jobTitle" type="text" v-model="jobTitle" required />
      </div>
      <div>
        <label for="startDate">Start Date:</label>
        <input id="startDate" type="date" v-model="startDate" required />
      </div>
      <div>
        <label for="endDate">End Date:</label>
        <input id="endDate" type="date" v-model="endDate" />
      </div>
      <div>
        <label for="description">Description:</label>
        <textarea id="description" v-model="description"></textarea>
      </div>
      <button type="submit">Save Experience</button>
    </form>

    <p v-if="message">{{ message }}</p>
  </div>
</template>

<style scoped>
.admin-form {
  max-width: 500px;
  margin: auto;
  padding: 20px;
}
.admin-form div {
  margin-bottom: 15px;
}
label {
  display: block;
  margin-bottom: 5px;
}
input, textarea {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}
</style>