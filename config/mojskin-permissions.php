<?php
return [
    'permissions' => [
        'adminPanel' => [
            'section' => 'adminPanel',
            'dependency' => 'isAdmin',
            'title' => 'پنل مدیریت',
            'child' => [
                // Dashboard
                [
                    'title' => 'پیشخوان',
                    'group' => 'dashboard',
                    'permission_titles' => ['مشاهده'],
                    'permissions' => [
                        ['id' => 'ucv', 'title' => 'مشاهده داشبورد', 'disabled' => true, 'default' => true],
                    ]
                ], // Dashboard
                // Form Builder
                [
                    'title' => 'فرم‌ساز',
                    'group' => 'forms',
                    'permission_titles' => ['مشاهده', 'افزودن', 'ویرایش', 'حذف', 'خروجی', 'تغییر وضعیت'],
                    'subMenu' => [
                        [
                            'title' => 'منوی فرم‌ساز',
                            'group' => 'formMenu',
                            'permissions' => [
                                ['id' => 'uha', 'title' => 'مشاهده'],
                                ['id' => 'uca', 'title' => 'افزودن', 'disabled' => true, 'default' => false],
                                ['id' => 'uce', 'title' => 'ویرایش', 'disabled' => true, 'default' => false],
                                ['id' => 'ucd', 'title' => 'حذف', 'disabled' => true, 'default' => false],
                                ['id' => 'ucx', 'title' => 'خروجی', 'disabled' => true, 'default' => false],
                                ['id' => 'ucs', 'title' => 'تغییر وضعیت', 'disabled' => true, 'default' => false],
                            ]
                        ],
                        [
                            'title' => 'مدیریت فرم‌ها',
                            'group' => 'builder',
                            'dependency' => 'uhaFormsMenu',
                            'permissions' => [
                                ['id' => 'ucv', 'title' => 'مشاهده'],
                                ['id' => 'uca', 'title' => 'افزودن'],
                                ['id' => 'uce', 'title' => 'ویرایش'],
                                ['id' => 'ucd', 'title' => 'حذف'],
                                ['id' => 'ucx', 'title' => 'خروجی', 'disabled' => true, 'default' => false],
                                ['id' => 'ucs', 'title' => 'تغییر وضعیت'],
                            ]
                        ],
                        [
                            'title' => 'صندوق ورودی',
                            'group' => 'inbox',
                            'dependency' => 'uhaFormsMenu',
                            'permissions' => [
                                ['id' => 'ucv', 'title' => 'مشاهده'],
                                ['id' => 'uca', 'title' => 'افزودن'],
                                ['id' => 'uce', 'title' => 'ویرایش', 'disabled' => true, 'default' => false],
                                ['id' => 'ucd', 'title' => 'حذف'],
                                ['id' => 'ucx', 'title' => 'خروجی'],
                                ['id' => 'ucs', 'title' => 'تغییر وضعیت'],
                            ]
                        ],
                        [
                            'title' => 'پیگیری فرم',
                            'group' => 'track',
                            'dependency' => 'uhaFormsMenu',
                            'permissions' => [
                                ['id' => 'ucv', 'title' => 'مشاهده'],
                                ['id' => 'uca', 'title' => 'ویرایش', 'disabled' => true, 'default' => false],
                                ['id' => 'uce', 'title' => 'ویرایش', 'disabled' => true, 'default' => false],
                                ['id' => 'ucd', 'title' => 'ویرایش', 'disabled' => true, 'default' => false],
                                ['id' => 'ucx', 'title' => 'خروجی'],
                                ['id' => 'ucs', 'title' => 'تغییر وضعیت'],
                            ]
                        ],
                    ]
                ], // Form Builder
                // Shop
                [
                    'title' => 'فروشگاه',
                    'group' => 'shop',
                    'permission_titles' => ['مشاهده', 'افزودن', 'ویرایش', 'حذف', 'خروجی', 'تغییر وضعیت'],
                    'subMenu' => [
                        [
                            'title' => 'محصولات',
                            'group' => 'products',
                            'dependency' => 'uhaShopMenu',
                            'permissions' => [
                                ['id' => 'ucv', 'title' => 'مشاهده'],
                                ['id' => 'uca', 'title' => 'افزودن'],
                                ['id' => 'uce', 'title' => 'ویرایش'],
                                ['id' => 'ucd', 'title' => 'حذف'],
                                ['id' => 'ucx', 'title' => 'خروجی'],
                                ['id' => 'ucs', 'title' => 'تغییر وضعیت', 'disabled' => true, 'default' => false],
                            ]
                        ],
                        [
                            'title' => 'دسته‌بندی محصولات',
                            'group' => 'productCategories',
                            'dependency' => 'uhaShopMenu',
                            'permissions' => [
                                ['id' => 'ucv', 'title' => 'مشاهده'],
                                ['id' => 'uca', 'title' => 'افزودن'],
                                ['id' => 'uce', 'title' => 'ویرایش'],
                                ['id' => 'ucd', 'title' => 'حذف'],
                                ['id' => 'ucx', 'title' => 'خروجی', 'disabled' => true, 'default' => false],
                                ['id' => 'ucs', 'title' => 'تغییر وضعیت', 'disabled' => true, 'default' => false],
                            ]
                        ],
                        [
                            'title' => 'واحدهای کالا',
                            'group' => 'productUnits',
                            'dependency' => 'uhaShopMenu',
                            'permissions' => [
                                ['id' => 'ucv', 'title' => 'مشاهده'],
                                ['id' => 'uca', 'title' => 'افزودن'],
                                ['id' => 'uce', 'title' => 'ویرایش'],
                                ['id' => 'ucd', 'title' => 'حذف'],
                                ['id' => 'ucx', 'title' => 'خروجی', 'disabled' => true, 'default' => false],
                                ['id' => 'ucs', 'title' => 'تغییر وضعیت', 'disabled' => true, 'default' => false],
                            ]
                        ],
                    ]
                ], // Shop
                // Users
                [
                    'title' => 'کاربران',
                    'group' => 'users',
                    'permission_titles' => ['مشاهده', 'افزودن', 'ویرایش', 'حذف', 'خروجی', 'درون‌ریز', 'تغییر وضعیت'],
                    'subMenu' => [
                        [
                            'title' => 'منوی کاربران',
                            'group' => 'usersMenu',
                            'permissions' => [
                                ['id' => 'uha', 'title' => 'مشاهده'],
                                ['id' => 'uca', 'title' => 'افزودن', 'disabled' => true, 'default' => false],
                                ['id' => 'uce', 'title' => 'ویرایش', 'disabled' => true, 'default' => false],
                                ['id' => 'ucd', 'title' => 'حذف', 'disabled' => true, 'default' => false],
                                ['id' => 'ucx', 'title' => 'خروجی', 'disabled' => true, 'default' => false],
                                ['id' => 'uci', 'title' => 'درون‌ریز', 'disabled' => true, 'default' => false],
                                ['id' => 'ucs', 'title' => 'تغییر وضعیت', 'disabled' => true, 'default' => false],
                            ]
                        ],
                        [
                            'title' => 'مدیریت کاربران',
                            'group' => 'list',
                            'dependency' => 'uhaUsersMenu',
                            'permissions' => [
                                ['id' => 'ucv', 'title' => 'مشاهده'],
                                ['id' => 'uca', 'title' => 'افزودن'],
                                ['id' => 'uce', 'title' => 'ویرایش'],
                                ['id' => 'ucd', 'title' => 'حذف'],
                                ['id' => 'ucx', 'title' => 'خروجی'],
                                ['id' => 'uci', 'title' => 'درون‌ریز'],
                                ['id' => 'ucs', 'title' => 'تغییر وضعیت', 'disabled' => true, 'default' => false],
                            ]
                        ],
                        [
                            'title' => 'نقش‌های کاربری',
                            'group' => 'roles',
                            'dependency' => 'uhaUsersMenu',
                            'permissions' => [
                                ['id' => 'ucv', 'title' => 'مشاهده'],
                                ['id' => 'uca', 'title' => 'افزودن'],
                                ['id' => 'uce', 'title' => 'ویرایش'],
                                ['id' => 'ucd', 'title' => 'حذف'],
                                ['id' => 'ucx', 'title' => 'خروجی', 'disabled' => true, 'default' => false],
                                ['id' => 'uci', 'title' => 'درون‌ریز', 'disabled' => true, 'default' => false],
                                ['id' => 'ucs', 'title' => 'تغییر وضعیت', 'disabled' => true, 'default' => false],
                            ]
                        ],
                    ]
                ], // Users
                [
                    'title' => 'تنظیمات سیستم',
                    'group' => 'settings',
                    'permission_titles' => ['مشاهده', 'ویرایش'],
                    'permissions' => [
                        ['id' => 'uha', 'title' => 'مشاهده'],
                        ['id' => 'uce', 'title' => 'ویرایش'],
                    ]
                ], // Users
            ]
        ],
    ]
];
