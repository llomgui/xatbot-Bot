<?php

namespace OceanProject;

class Extensions
{
    private static $extensionsList;

    public static function readExtensions()
    {
        self::$extensionsList = [];

        $extensionsDirectories = ['Modules', 'Commands'];

        foreach ($extensionsDirectories as $extensionsDir) {
            $callbacks = json_decode(
                file_get_contents('.' . DIRECTORY_SEPARATOR . strtolower($extensionsDir) . '.json', true),
                true
            );

            $dir = opendir('src' . DIRECTORY_SEPARATOR . $extensionsDir);

            while (($file = readdir($dir)) !== false) {
                $url = 'src' . DIRECTORY_SEPARATOR . $extensionsDir . DIRECTORY_SEPARATOR . $file;

                if (!is_file($url)) {
                    continue;
                }

                $pos = strrpos($file, '.');

                if ($pos === false) {
                    continue;
                }

                if (substr($file, $pos + 1) != 'php') {
                    continue;
                }

                self::$extensionsList = self::loadExtension(
                    self::$extensionsList,
                    $extensionsDir,
                    substr($file, 0, $pos),
                    $url,
                    $callbacks
                );
            }
        }
    }

    private static function loadExtension($data, $type, $name, $url, $callbacks)
    {
        require($url);

        for ($i = 0; $i < sizeof($callbacks); $i++) {
            if (isset(${$callbacks[$i]})) {
                $data[$type][$callbacks[$i]][$name] = ${$callbacks[$i]};
            } else {
                unset($data[$type][$callbacks[$i]][$name]);
            }
        }

        return $data;
    }

    public static function getExtensionsList()
    {
        return self::$extensionsList;
    }
}
