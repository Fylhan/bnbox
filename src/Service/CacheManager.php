<?php
namespace Bnoger\Service;

class CacheManager
{

    /**
     * Full URL of the page to cache (typically the value returned by pageUrl())
     *
     * @var string
     */
    private $url;

    /**
     * Name of the cache file for this url
     *
     * @var string
     */
    private $filename;

    /**
     * Should this url be cached ?
     *
     * @var boolean
     */
    private $shouldBeCached;

    /**
     * Flag to know if a cache is in progress or not
     *
     * @var boolean
     */
    private $caching;

    /**
     *
     * @param $url =
     *            url (typically the value returned by pageUrl())
     * @param $shouldBeCached =
     *            boolean. If false, the cache will be disabled.
     */
    public function __construct($url, $subFolder = '', $shouldBeCached = true)
    {
        $this->url = $url;
        $this->cacheFolder = CACHE_PATH . '/' . (NULL != $subFolder && '' != $subFolder ? $subFolder . (endsWith($subFolder, '/') ? '' : '/') : '');
        $this->filename = $this->cacheFolder . md5($url) . '.cache';
        $this->shouldBeCached = $shouldBeCached;
        $this->caching = $this->isEnabled();
    }

    /**
     * If the page should be cached and a cached version exists
     * returns the cached version (otherwise, return null).
     *
     * @return string null version
     */
    public function cachedVersion()
    {
        if (! $this->isEnabled())
            return null;
        if (is_file($this->filename)) {
            $this->caching = false;
            return file_get_contents($this->filename);
            exit();
        }
        return null;
    }

    /**
     * Put a page in the cache.
     */
    public function cache($page)
    {
        if (! $this->isEnabled())
            return;
        if (! is_dir($this->cacheFolder)) {
            mkdir($this->cacheFolder, 0705, true);
            chmod($this->cacheFolder, 0705);
        }
        file_put_contents($this->filename, $page);
    }

    /**
     * Purge the whole cache.
     * (call with pageCache::purgeCache())
     */
    public static function purgeCache($subFolder)
    {
        $cacheFolder = CACHE_PATH . '/' . (NULL != $subFolder && '' != $subFolder ? $subFolder . (endsWith($subFolder, '/') ? '' : '/') : '');
        CacheManager::rrmdir($cacheFolder);
        // if (is_dir($cacheFolder)) {
        // $handler = opendir($cacheFolder);
        // if ($handler !== false) {
        // while (($filename = readdir($handler)) !== false) {
        // if (endsWith($filename, '.cache')) {
        // unlink($cacheFolder . $filename);
        // }
        // }
        // closedir($handler);
        // }
        // }
    }

    public static function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        CacheManager::rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function isEnabled()
    {
        return CacheEnabled && $this->shouldBeCached;
    }

    public function isCaching()
    {
        return $this->caching;
    }
}
