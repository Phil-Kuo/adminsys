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
        'name' => '行政管理系统',
        'keywords' => '行政管理，后台管理',
        'description' => '后台管理，后台模版，组件化开发，软删除，验证器'
    ],

    //模板布局
    'template' => [
        'layout_on' => false,
        'layout_name' => 'layout',
        // 模板后缀
        // 'view_suffix'  => 'html',
//        'taglib_pre_load' => 'think\template\taglib\Cx,app\admin\taglib\Tool',
//        'taglib_build_in' => 'think\template\taglib\Cx,app\admin\taglib\Tool',
    ],

    // 视图输出字符串内容替换
    'view_replace_str' => [
        '__CSS__' => STATIC_PATH . 'admin/css',
        '__JS__' => STATIC_PATH . 'admin/js',
        '__IMG__' => STATIC_PATH . 'images',
        '__PLUGINS__' => STATIC_PATH . 'admin/plugins',
    ],
];