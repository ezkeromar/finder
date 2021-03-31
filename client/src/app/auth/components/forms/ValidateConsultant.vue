<script>
  import { mapActions } from 'vuex'
  import {ValidateConsultant} from '../../services'
  import hello from 'hellojs'
  import { baseUrl } from '../../../../config'

  export default {

    data() {
      return {
        token:null
      }
    },
    mounted() {
      this.token = this.$route.params.token;
      this.validate()
    },
    methods: {
      ...mapActions(['attemptLogin', 'setMessage', 'navigateTab']),
      
      
      validate() {
        const { token } = this
        ValidateConsultant(token) 
          .then(() => {
            this.setMessage({ type: 'error', message: [] }) 
            this.$router.push({ name: 'dashboard.index' })
          })
      },
    }
  }
</script>

<template>
  <div></div>
</template>