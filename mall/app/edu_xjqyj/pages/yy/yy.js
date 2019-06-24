const App = getApp();
let _this;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    yyFlag: 0,
    startDate: '',
    date: '',
    mdflag: -1,
    md: []
  },
  bindDateChange(e) {
    this.setData({
      date: e.detail.value
    })
    _this.getTime()
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    let date = new Date()
    _this = this
    _this.setData({
      startDate: date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate()
    })
    App.com.getWindowSize(this);
    this.getList(options.id, options.state);
  },
  getList(id, state) {
    App._get('school.appoint/lists', {
      state: state,
      appoint_id: id
    }, function(res) {
      _this.setData({
        list: res.data.list
      })
      
      _this.getMd()
    });
  },
  getTime() {
    App._get('school.timeform/gettime', { 
      store_id: this.data.kc[this.data.mdflag].store_id, 
      subject_id: this.data.list.data[0].subject_id,
      day: this.data.date
      }, function(res) {
      _this.setData({
        time: res.data.list
      })
    });
  },
  changeTag(e) {
    this.setData({
      yyFlag: e.currentTarget.dataset.index
    })
  },
  yy() {
    if (this.data.mdflag == -1) {
      wx.showToast({
        title: '请选择门店',
        icon: 'none'
      })
    } else if (this.data.date == '') {
      wx.showToast({
        title: '请选择预约日期',
        icon: 'none'
      })
    } else {

      App._get('school.appoint/appoint', {
        subjecttime_id: this.data.time[this.data.yyFlag].subjecttime_id,
        appoint_id: this.data.list.data[0].appoint_id,
        day: this.data.date,
        store_id: this.data.kc[this.data.mdflag].store_id,
        teacher_id: this.data.time[this.data.yyFlag].teacher_id
      }, function(res) {
        if (res.code == 1) {
          wx.showToast({
            title: '预约成功',
          })
          wx.navigateBack({
            detla: 1
          })
        }
      });
    }
  },
  bindPickerChange(e) {
    this.setData({
      mdflag: e.detail.value
    })
    _this.getTime()
  },
  getMd() {
    App._get('school.store/getlist', {

    }, function(res) {
      if (res.code == 1) {
        let arr = []
        for (let i in res.data.list.data) {
          arr.push(res.data.list.data[i].store_name)
        }
        _this.setData({
          kc: res.data.list.data,
          md: arr
        })
      }
    });
  }
})