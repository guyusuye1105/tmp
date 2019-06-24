const App = getApp();
let _this ;
Page({
  data: {
    // 搜索框样式
    searchColor: "rgba(0,0,0,0.4)",
    searchSize: "15",
    searchName: "搜索商品",

    // 列表高度
    scrollHeight: 0,

    // 一级分类：指针
    curNav: true,
    curIndex: 0,

    // 分类列表
    list: [],
    flag: -1,
    // show
    notcont: false
  },
  navTo(e) {
    App.com.navTo(e)
  },
  choose(e) {
    if (e.currentTarget.dataset.index == this.data.flag) {
      this.setData({
        flag: -1
      })
    } else {
      this.setData({
        flag: e.currentTarget.dataset.index
      })
      this.getGoodsList(false, 1, this.data.list[e.currentTarget.dataset.index].category_id)
    }

  },
  getGoodsList: function (isPage, page, cate_id) {
    wx.showLoading({
      title: '加载中',
      task:true
    })
    App._get('goods/lists', {
      page: 1,
      sortType: 'all',
      sortPrice: 0,
      category_id: cate_id || 0,
      search: '',
    }, function (result) {
      wx.hideLoading()
      let res = result.data.list.data;
      let arr = [];
      for(let i in res){
        if(i<4){
          arr.push(res[i])
        }
      }

      _this.setData({
        slist: arr
      });
    });
  },
  onLoad: function () {
    _this = this;
    // 设置分类列表高度
    _this.setListHeight();
    // 获取分类列表
    _this.getCategoryList();
  },

  /**
   * 设置分类列表高度
   */
  setListHeight: function () {
    let _this = this;
    wx.getSystemInfo({
      success: function (res) {
        _this.setData({
          scrollHeight: res.windowHeight - 47,
        });
      }
    });
  },

  /**
   * 获取分类列表
   */
  getCategoryList: function () {
    let _this = this;
    App._get('category/index', {}, function (result) {
      let data = result.data;
      _this.setData({
        list: data.list,
        templet: data.templet,
        curNav: data.list.length > 0 ? data.list[0].category_id : true,
        notcont: !data.list.length
      });
    });
  },

  /**
   * 一级分类：选中分类
   */
  selectNav: function (t) {
    let curNav = t.target.dataset.id,
      curIndex = parseInt(t.target.dataset.index);
    this.setData({
      curNav,
      curIndex,
      scrollTop: 0
    });
  },

  /**
   * 设置分享内容
   */
  onShareAppMessage: function () {
    let templet = this.data.templet;
    return {
      title: templet.share_title,
      path: "/pages/category/index?referee_id=" + App.getUserId()
    };
  }

});