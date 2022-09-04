<?php

namespace Gayaru\Devourer;

class ChangeFrequency
{
    const ALWAYS  = 'always';
    const HOURLY  = 'hourly';
    const DAILY   = 'daily';
    const WEEKLY  = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY  = 'yearly';
    const NEVER   = 'never';
}

class Sitemap extends AbstractSitemap
{
    protected function getRootNodeName()
    {
        return 'urlset';
    }

    protected function getNodeName()
    {
        return 'url';
    }

    /**
     * Adds the URL to the urlset.
     * @param  string     $loc
     * @param  string|int $lastmod
     * @param  string     $changefreq
     * @param  float      $priority
     * @return $this
     */
    public function add($loc, $lastmod = null, $changefreq = null, $priority = null)
    {
//        $dir = "upload";
//        if(!is_dir($dir))
//        {
//            mkdir($dir,0777,true);
//        }

        $loc     = $this->escapeString($loc);
//        $lastmod = !is_null($lastmod) ? $this->formatDate($lastmod) : null;

        return $this->addUrlToDocument(compact('loc', 'lastmod', 'changefreq', 'priority'));
    }

    public function putContent($path, $fileName, $sitemap)
    {
        if(!is_dir($path))
        {
            mkdir($path, 0777, true);
        }

        file_put_contents($fileName, (string) $sitemap);
        move_uploaded_file($fileName, $path);
    }
}
