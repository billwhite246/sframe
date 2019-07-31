# sframe - miniprogram
## 微信小程序端配置说明

> * sframe - miniprogram 已集成微信授权登陆操作
> * 只需修改几处参数即可配合sframe-wxmini版框架进行使用

## 1. 配置appid
`/project.config.json`
```json
"appid": "Your appid", // 替换成你的appid
```
## 2 .配置域名参数
`/util/globalUrl.js`
```js
domain: () => {
    return 'https://prod-domain/'
  },

domainTest: () => {
    return 'http://test-domain/'
},
```
配置测试域名和生产环境域名信息

## 3. 指定当前运行环境(prod / test)
`/util/properties.js`
```js
/**使用的域名 test为测试域名, prod为正式域名 */
useDomain: 'test'
```

## 4. 测试运行
配置完毕上述参数后，保存并编译。弹出授权提示时，点击确认授权后，跳转到主页面，切换底部选项卡至"我的"，正常展示用户信息及token，则配置环境成功。

