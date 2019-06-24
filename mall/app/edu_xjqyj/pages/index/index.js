const App = getApp();

Page({

  data: {
    companyTitle: App.globalData.companyTitle,
    // 页面元素
    items: {},
    list:[{
      image: '/images/icon/i1.png', id:'10004'
    }, {
        image: '/images/icon/i2.png', id: '10005'
      },{
        image: '/images/icon/i3.png', id: '10006'
      }],
    scrollTop: 0,
  },
  onPulling() {
    console.log('onPulling')

  },
  //下拉完成时的回调
  onRefresh() {
    console.log('onRefresh')
    //停止下拉刷新
    App.$stopWuxRefresher()
  },
  bkf(){
    wx.showModal({
      title: '提示',
      content: '暂不开放',
      showCancel:false,
      confirmText:'关闭'
    })
  },
  
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(e) {
    console.log(e)
    // 加载页面数据
    this.getPageData();
    // this.getCategoryList()
  },
  /**
   * 获取分类列表
   */
  getCategoryList: function () {
    let _this = this;
    App._get('category/index', {}, function (result) {
      let data = result.data;
      _this.setData({
        catelist: data.list,
        
      });
    });
  },
  navTo(e) {
    App.com.navTo(e)
  },
  /**
   * 加载页面数据
   */
  getPageData: function(callback) {
    let _this = this;
    App._get('page/home', {}, function(result) {
      // 设置顶部导航栏栏
      
      _this.setPageBar(result.data.page);
      // wx.setStorageSync('carousel', result.data.items[3].data)
      _this.setData(result.data);
      // 回调函数
      typeof callback === 'function' && callback();
    });
  },

  /**
   * 设置顶部导航栏
   */
  setPageBar: function(page) {
    // 设置页面标题
    wx.setNavigationBarTitle({
      title: page.params.title
    });
    // 设置navbar标题、颜色
    wx.setNavigationBarColor({
      frontColor: page.style.titleTextColor === 'white' ? '#ffffff' : '#000000',
      backgroundColor: page.style.titleBackgroundColor
    })
  },

  /**
   * 分享当前页面
   */
  onShareAppMessage: function() {
    let params = this.data.items.page.params;
    return {
      title: params.share_title,
      path: "/pages/index/index?referee_id=" + App.getUserId()
    };
  },

  /**
   * 下拉刷新
   */
  onPullDownRefresh: function() {
    // 获取首页数据
    this.getPageData(function() {
      wx.stopPullDownRefresh();
    });
  }

  // /**
  //  * 返回顶部
  //  */
  // goTop: function(t) {
  //   this.setData({
  //     scrollTop: 0
  //   });
  // },

  // scroll: function(t) {
  //   this.setData({
  //     indexSearch: t.detail.scrollTop
  //   }), t.detail.scrollTop > 300 ? this.setData({
  //     floorstatus: !0
  //   }) : this.setData({
  //     floorstatus: !1
  //   });
  // },

});