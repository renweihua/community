import request from '@/utils/request'
import axios from "axios";
import {getToken} from '@/utils/auth';

export function getUploadUrl() {
  return process.env.VUE_APP_BASE_API + '/upload_file'
}

export function getBatchUploadUrl() {
  return process.env.VUE_APP_BASE_API + '/upload_files'
}

export function uploads(formData, url, headers) {
    return request({
        url: getBatchUploadUrl(),
        method: 'post',
        data:formData,
    });

    axios.post(url || getBatchUploadUrl(),
        formData,
        headers ||  {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': getToken(),
            }
        }
    ).then( res => {
        console.log(res)
    }).catch( res => {
        console.log(res)
    })
}

export function getMonthLists() {
    return request({
        url: '/get_month_lists',
        method: 'get'
    })
}
