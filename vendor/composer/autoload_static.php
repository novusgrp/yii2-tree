<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3540cf3b5eeed172c3ceafb2e5d12710
{
    public static $files = array (
        '2cffec82183ee1cea088009cef9a6fc3' => __DIR__ . '/..' . '/ezyang/htmlpurifier/library/HTMLPurifier.composer.php',
    );

    public static $prefixLengthsPsr4 = array (
        'y' => 
        array (
            'yii\\jui\\' => 8,
            'yii\\composer\\' => 13,
            'yii\\' => 4,
        ),
        'n' => 
        array (
            'novusgrp\\' => 9,
        ),
        'c' => 
        array (
            'cebe\\markdown\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'yii\\jui\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-jui',
        ),
        'yii\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2-composer',
        ),
        'yii\\' => 
        array (
            0 => __DIR__ . '/..' . '/yiisoft/yii2',
        ),
        'novusgrp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
        'cebe\\markdown\\' => 
        array (
            0 => __DIR__ . '/..' . '/cebe/markdown',
        ),
    );

    public static $prefixesPsr0 = array (
        'H' => 
        array (
            'HTMLPurifier' => 
            array (
                0 => __DIR__ . '/..' . '/ezyang/htmlpurifier/library',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3540cf3b5eeed172c3ceafb2e5d12710::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3540cf3b5eeed172c3ceafb2e5d12710::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit3540cf3b5eeed172c3ceafb2e5d12710::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
