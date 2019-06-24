// components/Dialog/dialog.js
let App = getApp();
Component({
  options: {
    multipleSlots: true // 在组件定义时的选项中启用多slot支持
  },
  /**
   * 组件的属性列表
   * 用于组件自定义设置
   */
  properties: {
    // 弹窗标题
    title: { // 属性名
      type: String, // 类型（必填），目前接受的类型包括：String, Number, Boolean, Object, Array, null（表示任意类型）
      value: '弹窗标题'
    }
  },

  /**
   * 私有数据,组件的初始数据
   * 可用于模版渲染
   */
  data: {
    // 弹窗显示控制
    isShow: false,
    transparent: true
  },

  /**
   * 组件的方法列表
   * 更新属性和数据的方法与更新页面数据的方法类似
   */
  methods: {
    /**
     * 获取弹窗显示
     */
    _commonNav: function () {
      this.setData({
        isShow: !this.data.isShow,
        transparent: false
      })
    },
    nav: function (e) {
      let index = e.currentTarget.dataset.index;
      "home" == index ? wx.switchTab({
        url: "/pages/index/index"
      }) : "fenlei" == index ? wx.switchTab({
        url: "/pages/category/index"
      }) : "cart" == index ? wx.switchTab({
        url: "/pages/flow/index"
      }) : "profile" == index && wx.switchTab({
        url: "/pages/user/index"
      });
    },
    onToggleRules:function(e){
      App.saveFormId(e.detail.formId);
    }
  }
})