# Devourer
Sitemap generation tool.

### Composer CLI

```

composer require gayaru/devourer

```

## Basic use

``` php
use Gayaru\Devourer\Sitemap;
use Gayaru\Devourer\ChangeFrequency;

$sitemap = new Sitemap();
$sitemap->add('site.local', '17.04.2022', ChangeFrequency::WEEKLY, '1');
$sitemap->add('site.local/about', '17.04.2002', '0.1');
$sitemap->putContent('upload', 'sitemap','.xml', $sitemap);
"upload" - Folder to be created
"sitemap" - File name
".xml" - Extension -> xml json csv <-
