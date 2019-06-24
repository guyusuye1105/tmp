const App = getApp()
let _this;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    imgUrls: [
      
    ],
    page:1,
    current: 0,
    list: [
      { url: '/images/demo/t4.png', tit: '冬季绝对少不了的内搭精品——大衣…', price: '854' },
    ],
    whiteNum: 0
  },
  navTo(e) {
    let index = e.currentTarget.dataset.index
    wx.navigateTo({
      url: '/pages/find/detail/detail?id=' + this.data.list[index].comment_id,
    })
  },
  swiperChange(e) {
    this.setData({
      current: e.detail.current
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    _this = this
    this.setData({
      whiteNum: this.data.list.length % 4 > 0 ? parseInt(this.data.list.length / 4) + 1 : this.data.list.length,
      imgUrls:wx.getStorageSync("carousel")
    })
    _this.getList()
  },

  getList(isp){
    if(isp){
      this.data.page +=1
    }else{
      this.data.page =1
    }
    App._get('comment/sunlist', {page:this.data.page}, function (result) {
      // 设置顶部导航栏栏
      wx.stopPullDownRefresh()
      let arr = []
      let res = result.data.list.data
      if(isp){
        arr = _this.data.list
        for(let i in res){
          arr.push(res[i])
        }
      }else{
        arr = res
      }
      _this.setData({
        list: arr,
        whiteNum: arr.length % 4 > 0 ? parseInt(arr.length / 4) + 1 : arr.length
      });
      // 回调函数
      typeof callback === 'function' && callback();
      
    });
  },
  onPullDownRefresh(){
    this.getList(false)
  },
  onReachBottom(){
    this.getList(true)
  }
  
})