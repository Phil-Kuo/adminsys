<?php
/**
 * Created by PhpStorm.
 * User: YM
 * Date: 2018/12/24
 * Time: 23:29
 */

return [
    //网站名称
    'website' => [
        'name' => '数据资料管理系统',
        'keywords' => '数据管理，后台管理',
        'description' => '后台管理，后台模版，组件化开发，软删除，验证器'
    ],

    //模板布局
    'template' => [
        // 模板路径
        'view_path'    => '../application/admin/view/',
        'layout_on' => false,
        'layout_name' => 'layout',
    ],

    // 视图输出字符串内容替换
    'view_replace_str' => [
        '__CSS__' => STATIC_PATH . 'admin/css',
        '__JS__' => STATIC_PATH . 'admin/js',
        '__IMG__' => STATIC_PATH . 'img',
        '__PLUGINS__' => STATIC_PATH . 'admin/plugins',
    ],
];