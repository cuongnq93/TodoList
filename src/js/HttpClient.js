import axios from 'axios'
import NProgress from 'nprogress'
import {REQUEST_HEADER, API_URL} from './constants'

NProgress.configure({showSpinner: true})
const calculatePercentage = (loaded, total) => (Math.floor(loaded * 1.0) / total)

axios.defaults.onDownloadProgress = (e) => {
  const percentage = calculatePercentage(e.loaded, e.total)
  NProgress.set(percentage)
}


class HttpClient {
  constructor() {
    this.axios = axios.create({
      baseURL: API_URL,
      timeout: 60000,
      headers: REQUEST_HEADER
    });

    this.setupInterceptors();
  }

  setupInterceptors() {
    this.axios.interceptors.request.use(
      (config) => {
        NProgress.start()
        return config
      },
      error => Promise.reject(error)
    )

    this.axios.interceptors.response.use((response) => {
        NProgress.done()
        return response.data
      },(error) => {
        NProgress.done()
        return Promise.reject(error.response && error.response.data)
      })
  }

  get(path, params = {}) {
    const requestConfig = {params}
    return this.axios.get(path, requestConfig)
  }

  post(path, data = {}) {
    return this.axios.post(path, data)
  }
}

export default new HttpClient()