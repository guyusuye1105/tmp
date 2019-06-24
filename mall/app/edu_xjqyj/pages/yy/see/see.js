const App = getApp();
let _this;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    score1: 0,
    score2: 0,
    content: ''
  },
  onLoad: function (options) {
    _this = this
    this.setData({
      order_id: options.id,
      teacher_id: options.teacher_id,
      goods_id: options.goods_id
    })
    this.getGd()
  },
    
  getGd() {
    App._get('school.comment/lists', {
      goods_id: this.data.goods_id,
      order_id:this.data.order_id
    }, function (res) {
      if (res.code == 1) {
        _this.setData({
          gl: res.data.list,
          score1: res.data.list[0].subject_star,
          score2: res.data.list[0].teacher_star
        })
      }
    });
  },
  textInput(e) {
    this.setData({
      content: e.detail.value
    })
  },
  
  
})