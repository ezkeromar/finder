import { http } from '../../plugins/http'
import { getData } from '../../utils/get'

export const searchFrontConsultants = () =>
  http.get('search/front/consultants')
 .then(getData)

export const newsFront = () =>
  http.get('news/front')
 .then(getData)


 export const getHomeMissions = () => http.get('get/mission/home', {
    params: {
      page: 1,
      user_id: 13
    }
  }).then(getData);