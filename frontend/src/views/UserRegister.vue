<template>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 350px;">
      <h2 class="text-center mb-3">Register</h2>
      <form @submit.prevent="register">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input v-model="name" type="text" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input v-model="email" type="email" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input v-model="password" type="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-success w-100">Register</button>
      </form>
      <div class="text-center mt-3">
        <router-link to="/login">Already have an account? Login</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { API_BASE_URL } from "../config";

export default {
  data() {
    return { email: '', password: '' };
  },
  methods: {
    async register() {
      try {
          const response = await fetch(`${API_BASE_URL}/register`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name: this.name, email: this.email, password: this.password })
        });
        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || "Register failed");
        }
        this.$router.push({ path: '/login' });
      } catch(error) {
        console.error("Login error:", error.message);
        alert(error.message);
      }
    }
  }
};
</script>
