const App = getApp()
let _this;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    imgUrls: [
      'http://picyun.90sheji.com/design/00/00/35/67/s_1024_550c44fd4083a.jpg',
      'http://img.zcool.cn/community/0183e859533918a8012193a3595a8f.jpg@2o.jpg',
      'http://img5.imgtn.bdimg.com/it/u=1437931417,2405555356&fm=26&gp=0.jpg'
    ],
    current: 0,
    msg:{}
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
    _this = this;
    this.getList(options.id)
  },
  getList(id) {
    App._get('comment/sunlist', { comment_id:id}, function (result) {
      // 设置顶部导航栏栏
      _this.setData({
        msg: result.data.list.data[0]
      });
      // 回调函数
      typeof callback === 'function' && callback();

      _this.getShop(result.data.list.data[0].order_goods.goods_id)
    });
  },
  navToGoods(){
    wx.navigateTo({
      url: '/pages/goods/index?goods_id=' + this.data.msg.order_goods.goods_id,
    })
  },
  getShop(id) {
    App._get('goods/detail', {
      goods_id: id
    }, function (result) {
      // 初始化商品详情数据
      console.log(result)
      _this.setData({
        goods:result.data.detail
      });
    });
  }

})