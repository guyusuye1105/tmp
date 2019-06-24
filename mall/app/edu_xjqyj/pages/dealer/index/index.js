const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    isData: false,
    showQr:false,
    words: {},
    user: {},
    dealer: {},
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    this.getOrderList()
  },
  closeQr(){
    this.setData({ showQr: false })
  },
  saveTup(){
    let _this = this;
    wx.downloadFile({
      url: _this.data.qrcode, // 仅为示例，并非真实的资源
      success(res) {
        // 只要服务器有响应数据，就会把响应内容写入文件并进入 success 回调，业务需要自行判断是否下载到了想要的内容
        if (res.statusCode === 200) {
        
          wx.saveImageToPhotosAlbum({
            filePath: res.tempFilePath,
            success(res) { 

            }
          })
        }
      }
    })
  },
  /**
   * 获取推广二维码
   */
  getPoster: function () {
    let _this = this;
    wx.showLoading({
      title: '加载中',
    });
    App._get('user.dealer.qrcode/poster', {}, function (result) {
      // 设置当前页面标题
      // result.data.qrcode = result.data.qrcode.substring(0, result.data.qrcode.indexOf('?'))
      _this.setData(result.data);
      _this.setData({showQr:true})
    }, null, function () {
      wx.hideLoading();
    });
  },
  getOrderList: function () {
    let _this = this;
    App._get('user.dealer.order/lists', {
      settled: 0,
      page:  1,
    }, function (result) {
      // 创建页面数据
      _this.setData({
        userg:result.data.list.data,
        totalg: result.data.list.total
      });
    });

    App._get('user.dealer.order/lists', {
      settled: -1,
      page: 1,
    }, function (result) {
      // 创建页面数据
      _this.setData({
        totalg2: result.data.list.total
      });
    });
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    // 获取分销商中心数据
    this.getDealerCenter();
  },

  /**
   * 获取分销商中心数据
   */
  getDealerCenter: function() {
    let _this = this;
    App._get('user.dealer/center', {}, function(result) {
      let data = result.data;
      data.isData = true;
      // 设置当前页面标题
      wx.setNavigationBarTitle({
        title: data.words.index.title.value
      });
      _this.setData(data);
    });
  },

  /**
   * 跳转到提现页面
   */
  navigationToWithdraw: function() {
    wx.navigateTo({
      url: '../withdraw/apply/apply',
    })
  },

  /**
   * 立即加入分销商
   */
  triggerApply: function(e) {
    // 记录formId
    App.saveFormId(e.detail.formId);
    wx.navigateTo({
      url: '../apply/apply',
    })
  },

})