<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit38d6e9ac07f069125d8013037edb5acc
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit38d6e9ac07f069125d8013037edb5acc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit38d6e9ac07f069125d8013037edb5acc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit38d6e9ac07f069125d8013037edb5acc::$classMap;

        }, null, ClassLoader::class);
    }
}
