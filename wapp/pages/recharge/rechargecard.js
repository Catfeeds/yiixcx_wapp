function setOnShowScene(e) {
  getApp().onShowData || (getApp().onShowData = {}), getApp().onShowData.scene = e;
}

Page({
  data: {
    card_id: '',
    card_password: '',
    selected: -1,
    peisong: 0,
  },
  onLoad: function (e) {
    getApp().page.onLoad(this, e);
    var n = this;
    getApp().core.showLoading({
      title: "加载中"
    }), getApp().request({
      url: getApp().api.recharge.list,
      success: function (e) {
        var t = e.data;
        t.balance && 0 != t.balance.status || getApp().core.showModal({
          title: "提示",
          content: "充值功能未开启，请联系管理员！",
          showCancel: !1,
          success: function (e) {
            e.confirm && getApp().core.navigateBack({
              delta: 1
            });
          }
        }), n.setData(e.data);
      },
      complete: function (e) {
        getApp().core.hideLoading();
      }
    });
  },
  onReady: function () {
    getApp().page.onReady(this);
  },
  onShow: function () {
    getApp().page.onShow(this);
  },
  onHide: function () {
    getApp().page.onHide(this);
  },
  onUnload: function () {
    getApp().page.onUnload(this);
  },
  click: function (e) {
    this.setData({
      selected: e.currentTarget.dataset.index
    });
  },
  pay: function (e) {
    var that = this;
    getApp().request({
      url: getApp().api.user.activate,
      data: {
        card_id: that.data.card_id,
        card_password: that.data.card_password
      },
      method: "POST",
      success: function (e) {
        if (0 == e.code) {
          console.log(e.data);
          that.setData({
            __user_info: e.data
          }), getApp().setUser(e.data);

          getApp().core.showModal({
            title: "提示",
            content: '激活成功!',
            showCancel: !1,
            success: function (e) {
              e.confirm && getApp().core.navigateBack({
                delta: -1
              });
            }
          });
        } else {
          getApp().core.showModal({
            title: "提示",
            content: e.msg
          });

        }
      }
    })
  },
  inputcardid: function (e) {
    this.setData({
      card_id: e.detail.value
    });
  },
  inputcardpass: function (e) {
    this.setData({
      card_password: e.detail.value
    });
  }
});