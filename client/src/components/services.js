import { http } from '../plugins/http'
import { getData } from '../utils/get'

export const getNotifications = ($user_id, $type_profile) => http.get('get/notifications', {
    params: {
      user_id: $user_id,
      type_profile: $type_profile
    }
  }).then(getData);

export const readNotifications = ($user_id) => http.get('get/read/notifications', {
    params: {
      user_id: $user_id
    }
  }).then(getData);

export const sendContact = ( name, email, message ) =>
  http.post('/get/contact/send', { name, email, message })
  .then(getData)


export const newsFront = () =>
  http.get('news/front')
 .then(getData)