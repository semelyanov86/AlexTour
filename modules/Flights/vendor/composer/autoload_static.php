<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit630308651e43c15ed93906e3007f5613
{
    public static $prefixesPsr0 = array (
        'A' => 
        array (
            'AmadeusDahabtours' => 
            array (
                0 => __DIR__ . '/..' . '/dahabtours/amadeusclient',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit630308651e43c15ed93906e3007f5613::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
