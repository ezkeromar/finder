<template>
  <div :class="{loading:showloader}">
    <div class="container" style="margin-bottom:20px;">
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
          <v-select v-if="this.missions" v-model="selected" :on-change="onChangeMissions" :options="this.missions" :searchable="true" placeholder="Nom de la mission"></v-select>
        </div>
      </div>
    </div>
    <vue-event-calendar title="Liste des entretien" v-if="demoEvents" :events="demoEvents"></vue-event-calendar>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex'
  import { loadMissions, getMeetings } from '../../services.js'
  import { bus } from '../../../../plugins/eventbus'
export default {
  data () {
    return {
      demoEvents: null,
      missions:null,
      selected:null,
      mission_id:null,
      showloader: false
    }
  },
  computed: {
    ...mapGetters(['currentClient', 'currentUser']),
  },
  mounted(){
    this.meetings();
    this.getMissions();
  },
  methods: {
    onChangeMissions(obj) {
      this.mission_id = obj.value;
      this.meetings();
    },
    getMissions() {
      var pf = this;
      if(this.currentClient) {
        loadMissions(this.currentClient.id, this.currentUser.id).then(function(response) {
          pf.missions = response.missions;
        });
      }
    },
    meetings(day = null) {
      var ag = this;
      var $date = (day) ? day.date : null;
      var client_id;
      this.showloader = true;
      if (this.currentClient == null) {
        client_id = 0;
      } else {
        client_id = this.currentClient.id;
      }
      getMeetings(client_id, this.mission_id, this.currentUser.id).then(function(response) {
        ag.demoEvents = response.data;
        ag.showloader = false;
      });
    }
  }
}
</script>