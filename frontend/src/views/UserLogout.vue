<template>
  <div class="text-center my-4">
    <h1>Logging out...</h1>
    <p>You will be redirected to the login page</p>
  </div>
</template>

<script>

export default {
  created() {
    setTimeout(() => {
      this.$router.push('/login');
    }, 500);
  }
};
</script>