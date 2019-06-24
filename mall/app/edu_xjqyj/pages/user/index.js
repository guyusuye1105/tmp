let App = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    userInfo: {},
    orderCount: {},
    items: [
      { icon: 1, tit: '我的订单', page: '/pages/order/index?type=all', border: true, shadow: false },
      // { icon: 5, tit: '拼团订单', page: '/pages/sharing/order/index', border: true, shadow: false },
      { icon: 3, tit: '售后/退款', page: '/pages/order/refund/index', border: true, shadow: false },
      { icon: 2, tit: '我的地址', page: '/pages/address/index', border: true, shadow: false },
      { icon: 4, tit: '我的优惠券', page: '/pages/user/coupon/coupon', border: true, shadow: false },
      // { icon: 5, tit: '分销中心', page: '/pages/dealer/index/index', border: false, shadow: true }
    ]
  },
  navTo(e) {
    App.com.navTo(e)
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    // 获取当前用户信息
    this.getUserDetail();
  },

  /**
   * 获取当前用户信息
   */
  getUserDetail: function() {
    let _this = this;
    App._get('user.index/detail', {}, function(result) {
      delete result.data.menus.sharing_order;
      result.data.menus.address.name = '报名资料';
      _this.setData(result.data);
      wx.setStorageSync("moblie", result.data.userInfo.mobile)
    });
  },

  /**
   * 订单导航跳转
   */
  onTargetOrder(e) {
    // 记录formid
    App.saveFormId(e.detail.formId);
    let urls = {
      all: '/pages/order/index?type=all',
      payment: '/pages/order/index?type=payment',
      received: '/pages/order/index?type=received',
      refund: '/pages/order/refund/index',
    };
    // 转跳指定的页面
    wx.navigateTo({
      url: urls[e.currentTarget.dataset.type]
    })
  },

  /**
   * 菜单列表导航跳转
   */
  onTargetMenus(e) {
    // 记录formId
    App.saveFormId(e.detail.formId);
    wx.navigateTo({
      url: '/' + e.currentTarget.dataset.url
    })
  },

})