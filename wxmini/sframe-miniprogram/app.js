//app.js
import { globalUrl } from './utils/globalUrl.js'

App({
  onLaunch: function () {
    var that= this
    wx.checkSession({
      success() {
        //session_key 未过期，并且在本生命周期一直有效
        var token = wx.getStorageSync('token')
        wx.request({
          url: globalUrl.verifyToken(token),
          success: function (res) {
            if (res.data.err_code != 100000) {
              // 令牌 已经失效，需要重新执行登录流程
              that.reLogin();
            }
          }
        })
      },
      fail() {
        // session_key 已经失效，需要重新执行登录流程
        that.reLogin();
      }
    })

    // 最终令牌输出
    // 阻塞获取 确保 进入到小程序内时 token 是存在的
    // 如果获取20次都无响应（10秒超时）那么停止循环
    var getTimes = 0
    var exactToken = ''
    var token_interval = setInterval(function () {
      exactToken = wx.getStorageSync('token')
      // console.log('exactToken: ' + exactToken)
      if (exactToken != '') {
        // 存储到全局变量
        that.globalData.token = exactToken
        clearInterval(token_interval)
      }
      getTimes++;
      if (getTimes >= 20) {
        // 10秒超时 停止向下进行
        clearInterval(token_interval)
        // 1min 一次 死循环 不往下进行
        var dieCycle = setInterval(function () {
          // pass
        }, 60000)
      }
    }, 500) //循环间隔 单位ms

    // 获取用户信息
    wx.getSetting({
      success: res => {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
          wx.getUserInfo({
            success: res => {
              // 可以将 res 发送给后台解码出 unionId
              this.globalData.userInfo = res.userInfo

              // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
              // 所以此处加入 callback 以防止这种情况
              if (this.userInfoReadyCallback) {
                this.userInfoReadyCallback(res)
              }
              // wx.switchTab({
              //   url: '/pages/sframeIndex/sframeIndex',
              // })
            }
          })
        }else{
          wx.redirectTo({
            url: '/pages/sframeAuthorize/sframeAuthorize',
          })
        }
      }
    })
  },

  reLogin: function () {
    // 登录
    wx.login({
      success: res => {
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
        // console.log(res)
        var code = res.code
        wx.request({
          url: globalUrl.code2token(code),
          success: (res) => {
            if (res.data.err_code == 100000) {
              var token = res.data.info.token
              // 存起来
              wx.setStorageSync('token', token)
            } else {
              // 服务器错误
              console.log(res.data.err_code + ': ' + res.data.err_msg)
            }
          }
        })
      }
    })
  },

  globalData: {
    userInfo: null,
    token: null
  }
})