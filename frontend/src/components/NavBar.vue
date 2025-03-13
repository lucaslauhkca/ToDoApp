<template>
  <nav class="navbar navbar-dark bg-dark w-100 navbar-expand-lg fixed-top">
    <div class="container-fluid">
      <div>
        <router-link to="/" class="navbar-brand">ToDoApp</router-link>
        <router-link class="navbar-brand ms-3" v-if="$store.state.token" to="/dashboard">Dashboard</router-link>
      </div>
      <router-link class="btn btn-primary me-3" v-if="!$store.state.token" to="/login">Login</router-link>
      <router-link class="btn btn-danger me-3" v-if="$store.state.token" @click="$store.dispatch('logout')" to="/logout">Logout</router-link>
    </div>
    
  </nav>
</template>

