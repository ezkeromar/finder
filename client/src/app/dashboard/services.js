import { http } from '../../plugins/http'
import { getData } from '../../utils/get'

export const updateClient = ( email, name, city, phone, is_client, picture ) =>
  http.post('/update/client', { email, name, city, phone, is_client, picture })
  .then(getData)

export const addUserToClient = ( email, password, firstname, lastname, position, phone, is_client, picture ) =>
  http.post('/add/user/client', { email, password, firstname, lastname, position, phone, is_client, picture })
  .then(getData)

export const searchConsultants = ( $searchTerm, $page, $seniorities, $disponibilities, $tjmMin, $tjmMax, $rates, $skills, $technologies, $secteur, $client_id ) =>
  http.get('search/consultants', {
  	params: {
  		page: $page, 
  		searchTerm: $searchTerm,
  		seniorities: $seniorities,
  		disponibilities: $disponibilities,
  		tjmmax: $tjmMax,
  		tjmmin: $tjmMin,
      rates: $rates,
      skills: $skills,
  		technologies: $technologies,
      secteur: $secteur,
      client_id: $client_id
  	}
  })
 .then(getData)


 export const searchMissions = ( $searchTerm, $page, $tjmMin, $tjmMax ) =>
  http.get('search/missions', {
    params: {
      page: $page, 
      searchTerm: $searchTerm,
      tjmmax: $tjmMax,
      tjmmin: $tjmMin,
    }
  })
 .then(getData)


export const loadProfile = ($id) =>
  http.get('/get/consultent', {
    params: {
      id:$id
    }
  })

export const loadSkills = () =>
	http.get('/get/skills')
	.then(getData)

export const loadDiplomas = () =>
  http.get('/get/diplomas')
  .then(getData)

export const loadTechnologies = () =>
  http.get('/get/technologies')
  .then(getData)

export const loadCities = () =>
  http.get('/get/cities')
  .then(getData)

export const loadSecteurs = () =>
  http.get('/get/secteurs')
  .then(getData)

export const loadCountries = () =>
  http.get('/get/countries')
  .then(getData)

export const RateConsultent = (satisfaction, competance, methodology, reach_goal, respect_details, respect_rules, qualities, consultent_id, mission_id) => 
http.post('/consultant/rate', {satisfaction, competance, methodology, reach_goal, respect_details, respect_rules, qualities, consultent_id, mission_id}).then(getData)

export const loadClient = (user_id, client_id) => http.post('/get/profile/client', {user_id, client_id}).then(getData)

//export const loadConsultent = (user_id) => http.post('/get/profile/consultent', {user_id}).then(getData)
export const loadConsultent = ($user_id, $page) => http.get('/get/profile/consultent', {
      params: {
        page: $page, 
        user_id: $user_id
      }
    }).then(getData)

export const loadConsultentInfo = (user_id) => http.post('/load/consultent', {user_id}).then(getData)

export const loadMissions = (client_id, user_id) => http.post('/get/client/missions', {client_id, user_id}).then(getData)

export const submitRequest = (client_id, consultant_id, subject) =>http.post('/get/request/send', {client_id, consultant_id, subject}).then(getData)

export const meetRequest = (client_id, consultant_id, mission_id, date_start, date_end, hour_start, hour_end, min_start, min_end) => http.post('/get/client/request_contact', {client_id, consultant_id, mission_id, date_start, date_end, hour_start, hour_end, min_start, min_end}).then(getData)

export const deleteUser = (user_id) =>http.post('/get/profile/deleteuser', {user_id}).then(getData)

export const deleteExperience = (experience_id) =>http.post('/get/profile/deleteexperience', {experience_id}).then(getData)

export const deleteDiploma = (diploma_id) =>http.post('/get/profile/deletediploma', {diploma_id}).then(getData)

export const deleteCertif = (certif_id) =>http.post('/get/profile/deletecertif', {certif_id}).then(getData)

export const addUserToMission = (user_id, mission_id, type) =>http.post('/add/user/mission', {user_id, mission_id, type}).then(getData)

export const getMissions = ($client_id, $isArchive,$page, $user_id) => http.get('get/mission/list', {
    params: {
      client_id: $client_id,
      page: $page,
      archive: $isArchive,
      user_id: $user_id
    }
  }).then(getData);

export const getMessages = ($user_id) => http.get('get/messages', {
    params: {
      user_id: $user_id
    }
  }).then(getData);

export const sendMessage = (user_id, message, user_from) =>http.post('/get/message/add', {user_id, message, user_from}).then(getData)

export const getConsultantMissions = ($user_id, $page) => http.get('get/mission/consultant/list', {
    params: {
      page: $page,
      user_id: $user_id
    }
  }).then(getData);



export const deleteOffer = (mission_id) => http.post('get/mission/delete', {mission_id}).then(getData)

export const toggleOffer = (mission_id) => http.post('get/mission/toggle', {mission_id}).then(getData)

export const getMission = (mission_id, archive = null) => http.post('get/mission', {mission_id, archive}).then(getData)

export const getOnlyMission = (mission_id) => http.post('/get/only/mission', {mission_id}).then(getData)

export const addMissionExperience = (user_id, mission_id) => http.post('get/mission/addexperience', {user_id, mission_id}).then(getData)

export const UpdateConsultentSelection = (shortlist, select, consultant, condidat, mission) => http.post('update/consultant/select', {shortlist, select, consultant, condidat, mission}).then(getData)

export const deleteSelection = (consultant, mission) => http.post('delete/consultant/select', {consultant, mission}).then(getData)

export const addOffer = (id, title, description, duration, tjm, date_start, city_id, client_id, technologies, skills, user_id) => http.post('/get/mission/add', {id, title, description, duration, tjm, date_start, city_id, client_id, technologies, skills, user_id}).then(getData)

//export const editConsultant = (consultant_id, firstname, lastname, email, phone, address, disponibility_date, experience) => http.post('consultant/edit', {consultant_id, firstname, lastname, email, phone, address, disponibility_date, experience}).then(getData)

export const editConsultant = (consultantInfo) => http.post('consultant/edit', {consultantInfo}).then(getData)

export const revokeToken = () => http.post('/logout').then(getData)

export const ressetPass = (email) => http.post('/reset-pass', {email}).then(getData)

export const getMeetings = (client_id, mission_id, user_id) => http.post('/get/client/agenda', {client_id, mission_id, user_id}).then(getData)

export const updatePass = (password, token) => http.post('/update-pass', {password, token}).then(getData)