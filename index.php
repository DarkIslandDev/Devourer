<?php

require_once 'vendor/autoload.php';

use Gayaru\Devourer\Sitemap;
use Gayaru\Devourer\ChangeFrequency;

$sitemap = new Sitemap();
$sitemap->add('da.local/index',  ' ', ChangeFrequency::WEEKLY, '1');
$sitemap->add('da.local/about',  ' ',ChangeFrequency::WEEKLY, '.7');

$sitemap->putContent('upload', 'sitemap','.xml', $sitemap);

?>

<html>
<style>

</style>
</html>
