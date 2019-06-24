const App = getApp();
let _this;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    score1: 0,
    score2: 0,
    content:''
  },
  onLoad: function (options) {
    _this = this
    this.setData({
      order_id:options.id,
      teacher_id: options.teacher_id,
      goods_id:options.goods_id
    })
    this.getGd()
  },
  getGd(){
    //school.comment/order
    App._get('school.comment/order', {
      teacher_id:0,
      order_id:this.data.order_id
    }, function (res) {
      if (res.code == 1) {
        
        _this.setData({
          gl:res.data.goodsList[0]
        })
      }
    });
  },
  textInput(e){
    this.setData({
      content:e.detail.value
    })
  },
  changeScore(e){
    if(e.currentTarget.dataset.type == 1){
      if (this.data.score1 == e.currentTarget.dataset.num){
        this.setData({
          score1: 0
        })
      }else{
        this.setData({
          score1: e.currentTarget.dataset.num
        })
      }
    }else{
      if (this.data.score2 == e.currentTarget.dataset.num) {
        this.setData({
          score2: 0
        })
      } else {
        this.setData({
          score2: e.currentTarget.dataset.num
        })
      }
    }
  },
  submitScore(){
    if(this.data.content == ''){
      wx.showToast({
        title: '请输入评价信息',
        icon:'none'
      })
    } else if (this.data.score1 == 0 ){
      wx.showToast({
        title: '请评价课程',
        icon: 'none'
      })
    } else if (this.data.score2 == 0){
      wx.showToast({
        title: '请评价老师',
        icon: 'none'
      })
    }else{

      App._post_form('school.comment/order', {
        order_id: this.data.order_id,
        teacher_id: this.data.teacher_id,
        formData: JSON.stringify(
          [{ "goods_id": this.data.gl.goods_id, "order_goods_id": this.data.gl.order_goods_id, "score": 10, "subject_star": parseInt(this.data.score1), "teacher_star": parseInt(this.data.score2), "content": this.data.content,  "uploaded": ["10286"] }])
      }, function (res) {
        if (res.code == 1) {
          wx.showToast({
            title: res.msg,
            mask:true
          })
          setTimeout(function(){
            wx.navigateBack({
              detla: 1
            })
          },800)
          
        }
      });
    }
  },
})