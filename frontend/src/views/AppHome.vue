<template>
  <div></div>
</template>

<script>
export default {
  created() {
    if (this.$store.state.token) {
      this.$router.push('/dashboard');
    } else {
      this.$router.push('/login');
    }
  }
};
</script>
