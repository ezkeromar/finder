<script>
import { mapActions, mapGetters } from 'vuex'
import { getMessages, sendMessage, loadConsultent } from '../../services.js'
import { baseUrl } from '../../../../config'
import { bus } from '../../../../plugins/eventbus'
import moment from 'moment'

  export default { 
    data() {
	    return {
	        consultentInfo: null, 
	        baseUrlAttr: baseUrl,
	        messages: null,
	        message_text: null,
	        user_from: 'consultant'
	    }
    },
    computed: {
      ...mapGetters(['currentUser', 'currentClient']),
    },
    mounted() {
      this.loadMessages()
    },
    methods: {
      ...mapActions(['navigateTab']),
      	moment: function (date) {
		    return moment(date).format('MMMM YYYY');
		},
    	loadMessages(pageNum = 1) {
    		if(this.currentClient != undefined && this.currentClient != null) {
    			this.user_from = 'client';
    		}

	        var pf = this; 
	        if(this.currentUser.id) {
	        	console.log(this.currentUser.id);
	        	loadConsultent(this.currentUser.id, pageNum).then(function(response) {
	        		console.log(response);
		        	pf.consultentInfo = response.consultent_info;
		        });
	        	getMessages(this.currentUser.id).then(function(response) {
	        		pf.messages = response.messages;
	        	});
	        }
    	},
    	send_message() {
    		console.log(this.currentClient);
    		var pf = this;
		    sendMessage(this.currentUser.id, this.message_text, this.user_from).then(function(response){
		    	pf.message_text = '';
		    	pf.loadMessages();
		    });
    	},
    },    
    components:{
        
    },
  }
</script>
<template>
	<div class="main-content">
			<div class="container page-chat">
				<div class="content row">
					<span class="title black padding">Assistance</span>
					<div class="col-md-3">
				        <div class="sidebar sidebar-profile">
				          <div class="sidebar-profile-holder">
				          	<div class="profile-avatar">
				            <div class="img-holder">
				              <img v-if="consultentInfo.picture" :src="baseUrlAttr+consultentInfo.picture">
				            </div>
				          </div>
				          <span class="profile-username">{{consultentInfo.firstname}} {{consultentInfo.lastname}}</span>
				          <span>{{consultentInfo.profession}}</span>
				          <p v-if="consultentInfo.city">{{consultentInfo.city['title']}}</p>
				          <p class="profile-email">{{consultentInfo.email}}</p>
				          <p class="profile-phone">{{consultentInfo.phone}}</p><br/><br/><br/><br/>
				          <span>TJM</span>
				          <p>{{consultentInfo.tjm}} DHs</p>
				          <span>Date disponibilité</span>
				          <p>{{moment(consultentInfo.disponibility_date)}}</p>
				          <div v-if="!currentClient">
				          	<span>Compétence</span>
					          <p><i v-for='skill in consultentInfo.skill' v-if="skill.title">{{skill.title}}, </i></p>
					          <span>Technologies</span>
					          <p><i v-for='technology in consultentInfo.technology' v-if="technology.title">{{technology.title}}, </i></p>
				          </div>
				          </div>
				          <div class="siderbar-btns">
				            <router-link tag="a" class="btn-grey" :to="{ name: 'dashboard.profile.edit' }">
				              Voir mon CV
				            </router-link>
				            <router-link tag="a" class="btn-edit" :to="{ name: 'dashboard.profile.edit' }"></router-link>
				          </div>
				        </div>
				      </div>
					<div class="page-chat-content col-md-9">
						<div class="conversation-holder">
							<!--<div class="msg left">
								<div class="img-holder">
									<img src="http://placehold.it/150x150">
								</div>
								<div class="msg-content">
									<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, extremely painful. Nor again is there </p>
									<span class="msg-time">15:52</span>
								</div>
							</div>
							<div class="msg right">
								<div class="img-holder">
									<img src="http://placehold.it/150x150">
								</div>
								<div class="msg-content">
									<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, extremely painful. Nor again is there </p>
									<span class="msg-time">15:52</span>
								</div>
							</div>-->

							<div v-for="message in messages" class="msg " v-bind:class="[message.user_from == 'admin' ? 'right' : 'left']">
								<div class="img-holder">
									<img v-if="message.user_from == 'admin'" src="/static/img/profile.png">
									<img v-if="message.user_from != 'admin'" :src="baseUrlAttr+consultentInfo.picture">
								</div>
								<div class="msg-content">
									<p>{{ message.message }}</p>
									<span class="msg-time">{{ message.created_date }}</span>
								</div>
							</div>

						</div>
						<div class="message-field">
							<input type="text" v-model="message_text" placeholder="Tapez votre message">
							<a @click="send_message" class="send-message cursor"></a>
						</div>
 					</div>
				</div>
			</div>
		</div>
</template>