<script>
  import { mapGetters } from 'vuex'
  import Meeting from './Meeting.vue'
  import CvIframe from './CvIframe.vue'
  import { bus } from '../../../../plugins/eventbus'
  import { submitRequest } from '../../services'
  import moment from 'moment'

	export default {
		props: ['consultent', 'baseUrlattr', 'disponibility'],
      	data(){
          return{
            showSingle:false,
            showloader:false
          }
        },
        mounted() {
          bus.$on('see.single', ($event) => {
            this.showSingle = !this.showSingle;
          })
      	},
      	watch: {
      	},
        computed: {
          ...mapGetters(['currentClient', 'currentUser']),
        },
      	methods: {
          close() {
            $('body').removeClass('no-scroll');
            bus.$emit('see.single');
          },
          fixMeeting() {
            bus.$emit('see.single');
            bus.$emit('fix.meeting');
          },
          SeeUserCv() {
            this.submitrequest(1, false)
            bus.$emit('see.single');
            bus.$emit('see.cv.user');
          },
          submitrequest(subject, showalert = true) {
            var sg = this;
            this.showloader=true;
            submitRequest(this.currentUser.id, this.currentClient.id, subject).then(function(response) {
              sg.showloader = false;
              if(showalert) {
                bus.$emit('see.single');
                swal("Votre demande est sous le traitement");
              }
            })
          }
      	},
        components:{
          'meeting':Meeting,
          'cv-iframe':CvIframe
        },
    }
</script>

<template>
<div :class="{loading:showloader}">
	<div v-if="showSingle" class="popup active">
      <div class="popup-container popup-consultant active">
        <div class="popup-consultant-header">
          <div class="img-holder">
            <img v-if="consultent.picture != null" :src="baseUrlattr+consultent.picture">
          </div>
          <div class="consultant-info">
            <div class="consutlant-name">
              <p>{{consultent.firstname}}. {{consultent.lastname}}.</p>
              <div class="consutlant-stars" v-if='consultent.rate'>
                <span v-for='rate in parseInt(consultent.rate)'><i class="fa fa-star"></i></span>
              </div>
            </div>
            <span>{{consultent.profession}}</span>
            <span>{{consultent.tjm}} Dh</span>
            <span><b>Disponible ({{ disponibility }})</b></span>
            <span>{{consultent.city}}</span>
            <img v-if="consultent.country != null" :src="'/static/img/flags/'+consultent.country+'.png'">
          </div>
        </div>
        <div class="popup-consultant-content">
          <div class="block">
            <div v-if="consultent.skill">
              <span class="title black">Compétence</span>
              <p><span v-for="skillval in consultent.skill">{{skillval.title}},</span>..</p>
            </div>
            <div v-if="consultent.technology">
              <span class="title black">Téchnologie</span>
              <p><span v-for="techval in consultent.technology">{{techval.title}},</span>..</p>
            </div>
          </div>
          <div class="block" v-if='consultent.experience'>
            <span class="title black">Expériences</span>
            <p v-for="(exp, key, index) in consultent.experience">
              de {{exp['date_start']}} à {{exp['date_end']}} <br/>
              <b>{{exp['client']}} - {{exp['title']}}</b>
            </p>
          </div>
          <div class="block" v-if='consultent.userdiplome'>
            <span class="title black">Formation</span>
            <p v-for="(dipl, key, index) in consultent.userdiplome">
              {{dipl['year']}} - {{dipl['diploma']['title']}} <br/>
              {{dipl['school']}} - {{dipl['speciality']}}
            </p>
          </div>
          <div v-if="consultent.certif" class="block">
            <span class="title black">Cértificats</span>
            <p v-for="certif in consultent.certif">{{certif.title}}<a @click="submitrequest(2)" class="certificat">Voir certificat</a></p>

          </div>
        </div>
        <div class="popup-btns">
          <a @click="SeeUserCv" class="consultant-cv cursor">Consutler le CV</a>
          <a @click="fixMeeting" class="consultant-postuler cursor">Contacter le consultant</a>
          <a href="javascript:void(0);" class="consultant-plus cursor">+</a>
        </div>
        <a @click="close" class="popup-close"><i class="fa fa-times cursor"></i></a>
      </div>
      <span @click="close" class="popup-click"></span>
  </div>
  <meeting :baseUrlattr="baseUrlattr" :consultent="consultent.id" :mission="null"></meeting>
  <cv-iframe :baseUrlattr="baseUrlattr" :cv="consultent.curriculum_vitae"></cv-iframe>
</div>
</template>