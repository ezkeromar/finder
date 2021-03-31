
<script>
  import { mapState, mapActions } from 'vuex'

  export default {
    computed: {
      ...mapState(['messages']),
      hasSuccessMessage() {
        return this.messages.success !== ''
      },
      hasErrorMessages() {
        return this.messages.error.length > 0
      },
      hasValidationMessages() {
        return this.messages.validation.length > 0
      },
      formatedErrorMessage() {
        return this.messages.error.map(msg => `&bull; ${msg}`).join('<br>')
      },
      formatedValidationMessage() {
        return this.messages.validation.map(msg => `&bull; ${msg}`).join('<br>')
      },
    },
    methods: {
      ...mapActions(['setMessage']),
      dismiss(type) {
        let obj
        if (type === 'error') {
          obj = { type, message: [] }
        } else if (type === 'validation') {
          obj = { type, message: {} }
        } else {
          obj = { type, message: '' }
        }
        this.setMessage(obj)
      },
    },
  }
</script>

<template>
  <div class="alerts-container" v-if="$route.name != 'home.index'">

    <div class="alert alert-success" v-show="hasSuccessMessage">
      <strong>Success!</strong> {{messages.success}}.
    </div>

    <div class="alert alert-error" v-show="hasErrorMessages">
      <strong>Error!</strong> {{formatedErrorMessage}}.
    </div>

    <div class="alert alert-warning" v-show="hasValidationMessages">
      <strong>Warning!</strong> {{formatedErrorMessage}}.
    </div>

  </div>
</template>

<style scoped>
  .alerts-container {
    padding-top: 16px;
  }
</style>
