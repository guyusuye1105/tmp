let App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    phone:'',
    type:1
  },
  getPhone(e) {
    let _this = this
    App._post_form('user/getphone', {
      iv: e.detail.iv,
      encryptedData: e.detail.encryptedData
    }, function (res) {
      if (res.code == 1) {
        _this.setData({
          phone: res.data.phoneNumber
        })
        wx.navigateBack({
          detla: 1
        })
      }
    })
    
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    this.setData({
      type:options.type || 1
    })
  },

  /**
   * 授权登录
   */
  authorLogin: function(e) {
    let _this = this;
    if (e.detail.errMsg !== 'getUserInfo:ok') {
      return false;
    }
    // if (this.data.phone == '') {
    //   return false;
    // }
    wx.showLoading({
      title: "正在登录",
      mask: true
    });
    // 执行微信登录
    wx.login({
      success: function(res) {
        // 发送用户信息
        App._post_form('user/login', {
          code: res.code,
          user_info: e.detail.rawData,
          encrypted_data: e.detail.encryptedData,
          iv: e.detail.iv,
          signature: e.detail.signature,
          referee_id: wx.getStorageSync('referee_id'),
          phone:_this.data.phone
        }, function(result) {
          // 记录token user_id
          wx.setStorageSync('token', result.data.token);
          wx.setStorageSync('user_id', result.data.user_id);
          // 跳转回原页面
          console.log(1)
          wx.redirectTo({
            url: '/pages/login/login?type=2',
          })
        }, false, function() {
          wx.hideLoading();
        });
      }
    });
  },

  /**
   * 授权成功 跳转回原页面
   */
  navigateBack: function() {
    wx.navigateBack();
    // let currentPage = wx.getStorageSync('currentPage');
    // wx.redirectTo({
    //   url: '/' + currentPage.route + '?' + App.urlEncode(currentPage.options)
    // });
  },

})