<template>
  <div class="register-container">
    <form @submit.prevent="submitForm" class="register-form">
      <h2>Create Account</h2>
      <div class="form-group">
        <label for="name">Name</label>
        <input v-model="form.name" type="text" id="name" required placeholder="Your Name" />
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input v-model="form.email" type="email" id="email" required placeholder="you@email.com" />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input v-model="form.password" type="password" id="password" required placeholder="Password" />
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input v-model="form.password_confirmation" type="password" id="password_confirmation" required placeholder="Confirm Password" />
      </div>
      <button type="submit" :disabled="loading">
        <span v-if="loading">Registering...</span>
        <span v-else>Register</span>
      </button>
      <div v-if="error" class="error-message">{{ error }}</div>
      <div v-if="success" class="success-message">Registration successful! You can now log in.</div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});
const loading = ref(false);
const error = ref('');
const success = ref(false);

const submitForm = async () => {
  error.value = '';
  success.value = false;
  loading.value = true;
 try {
    const response = await axios.post('/register', form.value);
    success.value = true;
    form.value = { name: '', email: '', password: '', password_confirmation: '' };
    if (response.data.redirect) {
      window.location.href = response.data.redirect;
      return;
    }
  } catch (e) {
    error.value = e.response?.data?.message || 'Registration failed.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
}
.register-form {
  background: #fff;
  padding: 2.5rem 2rem;
  border-radius: 1rem;
  box-shadow: 0 4px 32px rgba(60, 72, 88, 0.12);
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}
.register-form h2 {
  text-align: center;
  margin-bottom: 1rem;
  color: #3730a3;
}
.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}
.form-group label {
  font-weight: 500;
  color: #6366f1;
}
.form-group input {
  padding: 0.7rem 1rem;
  border: 1px solid #c7d2fe;
  border-radius: 0.5rem;
  font-size: 1rem;
  outline: none;
  transition: border 0.2s;
}
.form-group input:focus {
  border-color: #6366f1;
}
button[type="submit"] {
  background: linear-gradient(90deg, #6366f1 0%, #3730a3 100%);
  color: #fff;
  padding: 0.8rem;
  border: none;
  border-radius: 0.5rem;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}
button[disabled] {
  opacity: 0.7;
  cursor: not-allowed;
}
.error-message {
  color: #dc2626;
  background: #fee2e2;
  padding: 0.5rem 1rem;
  border-radius: 0.4rem;
  margin-top: 0.5rem;
  text-align: center;
}
.success-message {
  color: #16a34a;
  background: #dcfce7;
  padding: 0.5rem 1rem;
  border-radius: 0.4rem;
  margin-top: 0.5rem;
  text-align: center;
}
</style> 