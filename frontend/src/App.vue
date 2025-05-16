<script setup>
import HelloWorld from './components/HelloWorld.vue'
import TheWelcome from './components/TheWelcome.vue'
import { ref, onMounted } from 'vue'

// Reactive variables to store API responses
const rootData = ref(null)
const dbTestData = ref(null)
const pingData = ref(null)

// Loading and error states
const isLoading = ref(true)
const error = ref(null)

// API base URL from environment variables
const API_BASE_URL = import.meta.env.VITE_API_BASE_URL;

// Function to fetch data from a specific endpoint
async function fetchData(endpoint, dataRef) {
  try {
    const response = await fetch(`${API_BASE_URL}${endpoint}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status} for ${endpoint}`);
    }
    dataRef.value = await response.json();
  } catch (e) {
    console.error(`Error fetching ${endpoint}:`, e);
    error.value = e.message || `Failed to fetch ${endpoint}`;
    // Optionally set specific error for specific dataRef if needed
  }
}

// Fetch all data when the component is mounted
onMounted(async () => {
  isLoading.value = true;
  error.value = null; // Reset error on new fetch attempt

  // Fetch in parallel
  await Promise.all([
    fetchData('/', rootData),
    fetchData('/dbtest', dbTestData), // Your route is /dbtest, not /db-test
    fetchData('/ping', pingData)
  ]);

  isLoading.value = false;
});
</script>

<template>
  <div id="app-container">
    <header>
      <h1>Laravel API Data</h1>
    </header>

    <main>
      <div v-if="isLoading" class="loading">
        <p>Loading API data...</p>
      </div>

      <div v-if="error" class="error-message">
        <p>Error fetching data: {{ error }}</p>
        <p>Please check the console for more details and ensure your Laravel API is running and accessible.</p>
        <p>API Base URL configured: <code>{{ API_BASE_URL }}</code></p>
      </div>

      <div v-if="!isLoading && !error" class="api-sections">
        <section class="api-section">
          <h2>Root Route (<code>{{ API_BASE_URL }}/</code>)</h2>
          <pre v-if="rootData">{{ JSON.stringify(rootData, null, 2) }}</pre>
          <p v-else>No data received.</p>
        </section>

        <section class="api-section">
          <h2>DB Test Route (<code>{{ API_BASE_URL }}/dbtest</code>)</h2>
          <pre v-if="dbTestData">{{ JSON.stringify(dbTestData, null, 2) }}</pre>
          <p v-else>No data received.</p>
        </section>

        <section class="api-section">
          <h2>Ping Route (<code>{{ API_BASE_URL }}/ping</code>)</h2>
          <pre v-if="pingData">{{ JSON.stringify(pingData, null, 2) }}</pre>
          <p v-else>No data received.</p>
        </section>
      </div>
    </main>
  </div>
</template>

<style scoped>
#app-container {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: left;
  color: #2c3e50;
  margin-top: 20px;
  padding: 20px;
}

header {
  text-align: center;
  margin-bottom: 30px;
}

.loading, .error-message {
  text-align: center;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-bottom: 20px;
}

.error-message {
  background-color: #ffebee;
  border-color: #ef5350;
  color: #c62828;
}

.api-sections {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.api-section {
  border: 1px solid #eee;
  padding: 20px;
  border-radius: 8px;
  background-color: #f9f9f9;
}

.api-section h2 {
  margin-top: 0;
  color: #35495e;
  border-bottom: 2px solid #41b883;
  padding-bottom: 10px;
  margin-bottom: 15px;
}

.api-section h2 code {
  font-size: 0.9em;
  color: #555;
}

pre {
  background-color: #fff;
  border: 1px solid #ddd;
  padding: 15px;
  border-radius: 4px;
  overflow-x: auto;
  white-space: pre-wrap; /* Allows wrapping long lines */
  word-wrap: break-word; /* Ensures words break correctly */
}
</style>
