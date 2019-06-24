/**
 * 工具类
 */
module.exports = {
  getWindowSize(that) {
    wx.getSystemInfo({
      success: function (res) {
        console.log(res.model)
        let hei = res.windowHeight
        if (res.model.indexOf('Plus') > 0 || res.model.indexOf('xus 5x') > 0 || res.model.indexOf('xus 6') > 0){
          hei = res.windowHeight - 50
        } else if (res.model.indexOf('IX 3') > 0){
          hei = res.windowHeight - 25
        }
        console.log(res.model.indexOf('hone'))
        if (res.model.indexOf('X') > 0 && res.model.indexOf('hone') > 0){
          res.model = 'iPhone X'
        }
        that.setData({
          width: res.windowWidth,
          height: hei,
          model:res.model
        })
      },
    })
  },
  navTo(e) {
    let type = e.currentTarget.dataset.type
    let names = e.currentTarget.dataset.names
    let path = e.currentTarget.dataset.path
    let nav = 'navigateTo'
    if (type == 1) {
      nav = 'navigateTo'
    } else if (type == 2) {
      nav = 'redirectTo'
    } else if (type == 3) {
      nav = 'switchTab'
    }
    let url = path
    let _names = []
    if (names) {
      _names = names.split(',')
    }
    if (_names.length > 0) {
      url += '?'
      for (let i in _names) {
        if (i == 0) {
          url += _names[i] + '=' + e.currentTarget.dataset[_names[i]]
        } else {
          url += '&' + _names[i] + '=' + e.currentTarget.dataset[_names[i]]
        }
      }
    }
    wx[nav]({
      url: url,
    })
  },
  /**
   * scene解码
   */
  scene_decode: function(e) {
    if (e === undefined)
      return {};
    let scene = decodeURIComponent(e),
      params = scene.split(','),
      data = {};
    for (let i in params) {
      var val = params[i].split(':');
      val.length > 0 && val[0] && (data[val[0]] = val[1] || null)
    }
    return data;
  },

  /**
   * 格式化日期格式 (用于兼容ios Date对象)
   */
  format_date: function(time) {
    // 将xxxx-xx-xx的时间格式，转换为 xxxx/xx/xx的格式 
    return time.replace(/\-/g, "/");
  },

};