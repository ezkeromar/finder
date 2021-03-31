<script>
  import { mapActions, mapGetters } from 'vuex'
  import {ressetPass} from '../../services'
  import hello from 'hellojs'
  import { baseUrl } from '../../../../config'

  export default {

    data() {
      return {
        showloader:false
      }
    },
    mounted() {
      this.submit();
    },
    methods: {
      ...mapActions(['tokenLogin']),

      submit() {
        this.showloader = !this.showloader
        var lg = this;
        const token = this.$route.query.token;
        this.tokenLogin({token})
          .then(() => {
            lg.showloader = !lg.showloader; 
            lg.$router.push({ name: 'dashboard.index' })
          
          })
      }
    }
  }
</script>

<template>
  <div :class="{loading:showloader}" style="width:100%; height:300px;">
  </div>
</template>