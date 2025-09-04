<!-- frontend/src/App.vue -->
<template>
  <header>
    <nav>
      <router-link to="/">Home</router-link> |
      <router-link to="/admin">Admin</router-link> |
      <router-link to="/cv">CV</router-link>
    </nav>
  </header>
  <main>
    <!-- This is where your page components (HomeView, AdminView) will be rendered -->
    <router-view />
  </main>
</template>

<style scoped>
header {
  text-align: center;
  padding: 20px;
  border-bottom: 1px solid #ccc;
}
nav {
  font-size: 1.2rem;
}
nav a {
  margin: 0 10px;
}
</style>