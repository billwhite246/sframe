import { properties } from './properties.js'

const globalUrl = {

  domain: () => {
    return 'https://domain/'
  },

  domainTest: () => {
    return 'http://127.0.0.1/vsCode/printshop/'
  },

  getCurrentDomain: () => {
    return properties.useDomain == 'test' ? globalUrl.domainTest() : globalUrl.domain()
  },

  // =========================================

  // code 转 token
  code2token: (code) => {
    return globalUrl.getCurrentDomain() + 'wxmn/getUserInfo/' + code
  },

  // verify token
  verifyToken: (token) => {
    return globalUrl.getCurrentDomain() + 'user/isTokenValid/' + token
  }


}

export { globalUrl }
