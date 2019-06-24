<?php
/**
 * 后台菜单配置
 *    'home' => [
 *       'name' => '首页',                // 菜单名称
 *       'icon' => 'icon-home',          // 图标 (class)
 *       'index' => 'index/index',         // 链接
 *     ],
 */
return [
    'index' => [
        'name' => '首页',
        'icon' => 'icon-home',
        'index' => 'index/index',
    ],
    'store' => [
        'name' => '管理员',
        'icon' => 'icon-guanliyuan',
        'index' => 'store.user/index',
        'submenu' => [
            [
                'name' => '管理员列表',
                'index' => 'store.user/index',
                'uris' => [
                    'store.user/index',
                    'store.user/add',
                    'store.user/edit',
                    'store.user/delete',
                ],
            ],
            [
                'name' => '角色管理',
                'index' => 'store.role/index',
                'uris' => [
                    'store.role/index',
                    'store.role/add',
                    'store.role/edit',
                    'store.role/delete',
                ],
            ],
        ]
    ],
    'goods' => [
        'name' => '商品管理',
        'icon' => 'icon-goods',
        'index' => 'goods/index',
        'submenu' => [
            [
                'name' => '商品列表',
                'index' => 'goods/index',
                'uris' => [
                    'goods/index',
                    'goods/add',
                    'goods/edit',
                    'goods/copy'
                ],
            ],
            [
                'name' => '商品分类',
                'index' => 'goods.category/index',
                'uris' => [
                    'goods.category/index',
                    'goods.category/add',
                    'goods.category/edit',
                ],
            ],
            [
                'name' => '商品评价',
                'index' => 'goods.comment/index',
                'uris' => [
                    'goods.comment/index',
                    'goods.comment/detail',
                ],
            ]
        ],
    ],
    'order' => [
        'name' => '订单管理',
        'icon' => 'icon-order',
        'index' => 'order/delivery_list',
        'submenu' => [
            [
                'name' => '全部订单',
                'index' => 'order/all_list',
            ],
            [
                'name' => '核销串码',
                'index' => 'order/cousume_sn_code',
            ],
            [
                'name' => '待发货',
                'index' => 'order/delivery_list',
            ],
            [
                'name' => '待收货',
                'index' => 'order/receipt_list',
            ],
            [
                'name' => '待付款',
                'index' => 'order/pay_list',
            ],
            [
                'name' => '已完成',
                'index' => 'order/complete_list',

            ],
            [
                'name' => '已取消',
                'index' => 'order/cancel_list',
            ],
            [
                'name' => '售后管理',
                'index' => 'order.refund/index',
                'uris' => [
                    'order.refund/index',
                    'order.refund/detail',
                ]
            ],

        ]
    ],
    'user' => [
        'name' => '用户管理',
        'icon' => 'icon-user',
        'index' => 'user/index',
    ],
    'content' => [
        'name' => '内容管理',
        'icon' => 'icon-wenzhang',
        'index' => 'content.article/index',
        'submenu' => [
            [
                'name' => '文章管理',
                'active' => true,
                'submenu' => [
                    [
                        'name' => '文章列表',
                        'index' => 'content.article/index',
                        'uris' => [
                            'content.article/index',
                            'content.article/add',
                            'content.article/edit',
                        ]
                    ],
                    [
                        'name' => '文章分类',
                        'index' => 'content.article.category/index',
                        'uris' => [
                            'content.article.category/index',
                            'content.article.category/add',
                            'content.article.category/edit',
                        ]
                    ],
                ]
            ],
        ]
    ],
    'market' => [
        'name' => '营销设置',
        'icon' => 'icon-marketing',
        'index' => 'market.coupon/index',
        'submenu' => [
            [
                'name' => '优惠券',
                'active' => true,
                'submenu' => [
                    [
                        'name' => '优惠券列表',
                        'index' => 'market.coupon/index',
                        'uris' => [
                            'market.coupon/index',
                            'market.coupon/add',
                            'market.coupon/edit',
                        ]
                    ],
                    [
                        'name' => '领取记录',
                        'index' => 'market.coupon/receive'
                    ],
                ]
            ],
        ],
    ],
    'wxapp' => [
        'name' => '小程序',
        'icon' => 'icon-wxapp',
        'color' => '#36b313',
        'index' => 'wxapp/setting',
        'submenu' => [
            [
                'name' => '小程序设置',
                'index' => 'wxapp/setting',
            ],
            [
                'name' => '页面管理',
                'active' => true,
                'submenu' => [
                    [
                        'name' => '页面设计',
                        'index' => 'wxapp.page/index',
                        'uris' => [
                            'wxapp.page/index',
                            'wxapp.page/add',
                            'wxapp.page/edit',
                        ]
                    ],
                    [
                        'name' => '分类模板',
                        'index' => 'wxapp.page/category'
                    ],
                    [
                        'name' => '页面链接',
                        'index' => 'wxapp.page/links'
                    ]
                ]
            ],
            [
                'name' => '帮助中心',
                'index' => 'wxapp.help/index',
                'uris' => [
                    'wxapp.help/index',
                    'wxapp.help/add',
                    'wxapp.help/edit'
                ]
            ],
        ],
    ],
    'apps' => [
        'name' => '应用中心',
        'icon' => 'icon-application',
        'is_svg' => true,   // 多色图标
        'index' => 'apps.dealer.apply/index',
        'submenu' => [
            [
                'name' => '预约中心',
                'submenu' => [
                    [
                        'name' => '预约项目列表',
                        'index' => 'apps.school.subject/index',
                        'uris' => [
                            'apps.school.subject/index',
                            'apps.school.subject/add',
                            'apps.school.subject/edit',
                        ]
                    ],
                    [
                        'name' => '教师列表',
                        'index' => 'apps.school.teacher/index',
                        'uris' => [
                            'apps.school.teacher/index',
                            'apps.school.teacher/add',
                            'apps.school.teacher/edit',
                        ]
                    ],
                    [
                        'name' => '预约时间',
                        'index' => 'apps.school.subjecttime/index',
                        'uris' => [
                            'apps.school.subjecttime/index',
                            'apps.school.subjecttime/add',
                            'apps.school.subjecttime/edit',
                        ]
                    ],
                    [
                        'name' => '课程表',
                        'index' => 'apps.school.timeform/index',
                        'uris' => [
                            'apps.school.timeform/index',
                            'apps.school.timeform/add',
                            'apps.school.timeform/edit',
                        ]
                    ],
                    [
                        'name' => '门店管理',
                        'index' => 'apps.school.store/index',
                        'uris' => [
                            'apps.school.store/index',
                            'apps.school.store/add',
                            'apps.school.store/edit',
                        ]
                    ],
                    [
                        'name' => '预约列表',
                        'index' => 'apps.school.appoint/index',
                        'uris' => [
                            'apps.school.appoint/index',
                            'apps.school.appoint/add',
                            'apps.school.appoint/edit',
                        ]
                    ],
                    [
                        'name' => '课程评价',
                        'index' => 'apps.school.comment/index',
                        'uris' => [
                            'apps.school.comment/index',
                            'apps.school.comment/detail',
                        ]
                    ],

                ]
            ],
            [
                'name' => '分销中心',
                'submenu' => [
                    [
                        'name' => '入驻申请',
                        'index' => 'apps.dealer.apply/index',
                    ],
                    [
                        'name' => '分销商用户',
                        'index' => 'apps.dealer.user/index',
                        'uris' => [
                            'apps.dealer.user/index',
                            'apps.dealer.user/fans',
                        ]
                    ],
                    [
                        'name' => '分销订单',
                        'index' => 'apps.dealer.order/index',
                    ],
                    [
                        'name' => '提现申请',
                        'index' => 'apps.dealer.withdraw/index',
                    ],
                    [
                        'name' => '分销设置',
                        'index' => 'apps.dealer.setting/index',
                    ],
                    [
                        'name' => '分销海报',
                        'index' => 'apps.dealer.setting/qrcode',
                    ],
                ]
            ],
            [
                'name' => '拼团管理',
                'submenu' => [
                    [
                        'name' => '商品分类',
                        'index' => 'apps.sharing.category/index',
                        'uris' => [
                            'apps.sharing.category/index',
                            'apps.sharing.category/add',
                            'apps.sharing.category/edit',
                        ]
                    ],
                    [
                        'name' => '商品列表',
                        'index' => 'apps.sharing.goods/index',
                        'uris' => [
                            'apps.sharing.goods/index',
                            'apps.sharing.goods/add',
                            'apps.sharing.goods/edit',
                            'apps.sharing.goods/copy',
                            'apps.sharing.goods/copy_master',
                        ]
                    ],
                    [
                        'name' => '拼单管理',
                        'index' => 'apps.sharing.active/index',
                        'uris' => [
                            'apps.sharing.active/index',
                            'apps.sharing.active/users',
                        ]
                    ],
                    [
                        'name' => '订单管理',
                        'index' => 'apps.sharing.order/index',
                        'uris' => [
                            'apps.sharing.order/index',
                            'apps.sharing.order/detail',
                            'apps.sharing.order.operate/batchdelivery'
                        ]
                    ],
                    [
                        'name' => '售后管理',
                        'index' => 'apps.sharing.order.refund/index',
                        'uris' => [
                            'apps.sharing.order.refund/index',
                            'apps.sharing.order.refund/detail',
                        ]
                    ],
                    [
                        'name' => '商品评价',
                        'index' => 'apps.sharing.comment/index',
                        'uris' => [
                            'apps.sharing.comment/index',
                            'apps.sharing.comment/detail',
                        ],
                    ],
                    [
                        'name' => '拼团设置',
                        'index' => 'apps.sharing.setting/index'
                    ]
                ]
            ],
            [
                'name' => '砍价',
                'submenu' => [
                    [
                        'name' => '商品分类',
                        'index' => 'apps.bargain.category/index',
                        'uris' => [
                            'apps.bargain.category/index',
                            'apps.bargain.category/add',
                            'apps.bargain.category/edit',
                        ]
                    ],
                    [
                        'name' => '商品列表',
                        'index' => 'apps.bargain.goods/index',
                        'uris' => [
                            'apps.bargain.goods/index',
                            'apps.bargain.goods/add',
                            'apps.bargain.goods/edit',
                            'apps.bargain.goods/copy',
                            'apps.bargain.goods/copy_master',
                        ]
                    ],
                    [
                        'name' => '砍价管理',
                        'index' => 'apps.bargain.active/index',
                        'uris' => [
                            'apps.bargain.active/index',
                            'apps.bargain.active/users',
                        ]
                    ],
                    [
                        'name' => '订单管理',
                        'index' => 'apps.bargain.order/index',
                        'uris' => [
                            'apps.bargain.order/index',
                            'apps.bargain.order/detail',
                            'apps.bargain.order.operate/batchdelivery'
                        ]
                    ],
                    [
                        'name' => '售后管理',
                        'index' => 'apps.bargain.order.refund/index',
                        'uris' => [
                            'apps.bargain.order.refund/index',
                            'apps.bargain.order.refund/detail',
                        ]
                    ],
                    [
                        'name' => '商品评价',
                        'index' => 'apps.bargain.comment/index',
                        'uris' => [
                            'apps.bargain.comment/index',
                            'apps.bargain.comment/detail',
                        ],
                    ],
                    [
                        'name' => '砍价设置',
                        'index' => 'apps.bargain.setting/index'
                    ]
                ]
            ],
            [
                'name' => '秒杀',
                'submenu' => [
                    [
                        'name' => '商品分类',
                        'index' => 'apps.flashsale.category/index',
                        'uris' => [
                            'apps.flashsale.category/index',
                            'apps.flashsale.category/add',
                            'apps.flashsale.category/edit',
                        ]
                    ],
                    [
                        'name' => '商品列表',
                        'index' => 'apps.flashsale.goods/index',
                        'uris' => [
                            'apps.flashsale.goods/index',
                            'apps.flashsale.goods/add',
                            'apps.flashsale.goods/edit',
                            'apps.flashsale.goods/copy',
                            'apps.flashsale.goods/copy_master',
                        ]
                    ],
                    [
                        'name' => '秒杀管理',
                        'index' => 'apps.flashsale.active/index',
                        'uris' => [
                            'apps.flashsale.active/index',
                            'apps.flashsale.active/users',
                        ]
                    ],
                    [
                        'name' => '订单管理',
                        'index' => 'apps.flashsale.order/index',
                        'uris' => [
                            'apps.flashsale.order/index',
                            'apps.flashsale.order/detail',
                            'apps.flashsale.order.operate/batchdelivery'
                        ]
                    ],
                    [
                        'name' => '售后管理',
                        'index' => 'apps.flashsale.order.refund/index',
                        'uris' => [
                            'apps.flashsale.order.refund/index',
                            'apps.flashsale.order.refund/detail',
                        ]
                    ],
                    [
                        'name' => '商品评价',
                        'index' => 'apps.flashsale.comment/index',
                        'uris' => [
                            'apps.flashsale.comment/index',
                            'apps.flashsale.comment/detail',
                        ],
                    ],
                    [
                        'name' => '秒杀设置',
                        'index' => 'apps.flashsale.setting/index'
                    ]
                ]
            ],

        ]
    ],
    'setting' => [
        'name' => '设置',
        'icon' => 'icon-setting',
        'index' => 'setting/store',
        'submenu' => [
            [
                'name' => '商城设置',
                'index' => 'setting/store',
            ],
            [
                'name' => '交易设置',
                'index' => 'setting/trade',
            ],
            [
                'name' => '配送设置',
                'index' => 'setting.delivery/index',
                'uris' => [
                    'setting.delivery/index',
                    'setting.delivery/add',
                    'setting.delivery/edit',
                ],
            ],
            [
                'name' => '物流公司',
                'index' => 'setting.express/index',
                'uris' => [
                    'setting.express/index',
                    'setting.express/add',
                    'setting.express/edit',
                ],
            ],
            [
                'name' => '用户额外字段',
                'index' => 'setting/user_extra_field'
            ],
            [
                'name' => '短信通知',
                'index' => 'setting/sms'
            ],
            [
                'name' => '模板消息',
                'index' => 'setting/tplmsg',
                'uris' => [
                    'setting/tplmsg',
                    'setting.help/tplmsg'

                ],
            ],
            [
                'name' => '退货地址',
                'index' => 'setting.address/index',
                'uris' => [
                    'setting.address/index',
                    'setting.address/add',
                    'setting.address/edit',
                ],
            ],
            [
                'name' => '商品海报',
                'index' => 'setting/poster',
            ],
            [
                'name' => '分享图片设置',
                'index' => 'setting/sharepic',
            ],
            /*
            [
                'name' => '上传设置',
                'index' => 'setting/storage',
            ],
            */
            [
                'name' => '其他',
                'active' => true,
                'submenu' => [
                    [
                        'name' => '清理缓存',
                        'index' => 'setting.cache/clear'
                    ]
                ]
            ]
        ],
    ],




];
