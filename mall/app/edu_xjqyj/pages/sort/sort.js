let App = getApp()
let _this;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    slist:[]
  },
  navTo(e) {
    App.com.navTo(e)
  },
  getGoodsList: function () {
    wx.showLoading({
      title: '加载中',
      task: true
    })
    App._get('goods/lists', {
      page: 1,
      sortType: 'sales',
      sortPrice: 0,
      category_id:  0,
      search: '',
    }, function (result) {
      wx.hideLoading()
      let res = result.data.list.data;
      let arr = [];
      for (let i in res) {
        if (i < 10) {
          arr.push(res[i])
        }
      }

      _this.setData({
        slist: arr
      });
    });
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    _this = this;
    _this.getGoodsList()
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
  
})