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

    public function add($loc, $lastmod = null, $changefreq = null, $priority = null)
    {

        $loc     = $this->escapeString($loc);

        return $this->addUrlToDocument(compact('loc', 'lastmod', 'changefreq', 'priority'));
    }

    public function putContent($path, $fileName, $fileExtension, $sitemap)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $fullNameFile = ($fileName . $fileExtension);

        switch ($fileExtension)
        {

            case '.xml':
                file_put_contents("$path/$fullNameFile", (string)$sitemap);
                break;

            case '.json':
                $xml = simplexml_load_string($sitemap, 'SimpleXMLElement', LIBXML_NOCDATA);
                $json = json_encode($xml);
                file_put_contents("$path/$fullNameFile", $json);
                break;
            case '.csv':
                file_put_contents("$path/$fullNameFile", $this->convertXmlToCsvString($sitemap));
                break;

            default:
                $fullNameFile = ('ERROOOOOOOR-NAME'.$fileExtension.'.txt');
                file_put_contents("$path/$fullNameFile",
                    'у вас расширение файла не правильное поменяйте');
        }
    }


}
