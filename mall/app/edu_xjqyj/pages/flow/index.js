const App = getApp();
let _this ;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navBar:[
      { label: '未使用', state: 'nouse' }, { label: '预约中', state: 'ing' }, { label: '待评价', state: 'comment' }, { label: '已结束',state:'end' }
    ],
    navBarIndex:0,
    list:[],
    flag:0,
    show:false
  },
  seecomment(e){
    wx.navigateTo({
      url: '/pages/yy/see/see?id=' + e.currentTarget.dataset.id + '&teacher_id=' + e.currentTarget.dataset.tea + '&goods_id=' + e.currentTarget.dataset.gid,
    })
  },
  docomment(e){
    wx.navigateTo({
      url: '/pages/yy/pj?id=' + e.currentTarget.dataset.id + '&teacher_id=' + e.currentTarget.dataset.tea + '&goods_id=' + e.currentTarget.dataset. gid,
    })
  },
  clse(){
    this.setData({
      show: false,
    })
  },
  doId(e){
    this.setData({
      show:true,
      flag:e.currentTarget.dataset.index
    })
  },
  changeTag(e){
    this.setData({
      navBarIndex: e.currentTarget.dataset.index
    })
    this.getList()
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    _this = this;
    
  },
  getList(){
    App._get('school.appoint/lists', {
      state: this.data.navBar[this.data.navBarIndex].state,
      appoint_id:''
    }, function (res) {
      _this.setData({
        list: res.data.list.data
      })
    });
  },  
  dopointer(e){
    wx.navigateTo({
      url: '/pages/yy/yy?id='+e.currentTarget.dataset.id+'&state='+e.currentTarget.dataset.state,
    })
  },
  cancelpo(e){
    wx.showModal({
      title: '提示',
      content: '确定要取消预约？',
      success(res){
        if(res.confirm){
          App._get('school.appoint/cancelappoint', {
            appoint_id: e.currentTarget.dataset.id
          }, function (res) {
            if (res.code == 1) {
              wx.showToast({
                title: '取消预约成功',
              })
              _this.getList()
              wx.navigateBack({
                detla: 1
              })
            }
          });
        }
      }
    }) 
    
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    this.getList()
  },

  
})