<?php

return [
    //网站名称
    'website' => [
        'name' => '数据资料管理系统',
        'keywords' => '数据资料',
        'description' => '组件化开发，数据浏览，数据下载'
    ],

    //模板布局
    'template' => [
        // 模板路径
        'view_path'    => '../application/index/view/',
        'layout_on' => false,
        'layout_name' => 'layout',
    ],

    // 视图输出字符串内容替换
    'view_replace_str' => [
        '__CSS__' => STATIC_PATH . 'index/css',
        '__JS__' => STATIC_PATH . 'index/js',
        '__IMG__' => STATIC_PATH . 'img',
        '__PLUGINS__' => STATIC_PATH . 'index/plugins',
    ],
];