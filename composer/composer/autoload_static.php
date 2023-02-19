<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8aa24f20669d43ac224d3291c759d196
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Petpoint\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Petpoint\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8aa24f20669d43ac224d3291c759d196::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8aa24f20669d43ac224d3291c759d196::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
