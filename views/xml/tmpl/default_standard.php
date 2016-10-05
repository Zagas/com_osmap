<?php
/**
 * @package   OSMap
 * @copyright 2007-2014 XMap - Joomla! Vargas - Guillermo Vargas. All rights reserved.
 * modified by Giuseppe Z
 * @copyright 2016 Open Source Training, LLC. All rights reserved.
 * @contact   www.alledia.com, support@alledia.com
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

use Alledia\OSMap;

global $showExternalLinks;

unlink('sitemap.xml');
$showExternalLinks = (int)$this->osmapParams->get('show_external_links', 0);
$file = 'sitemap.xml';

$current = file_get_contents($file);

	$current .= '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
	$current .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
	
file_put_contents($file, $current);	
$printNodeCallback = function ($node) {
    global $showExternalLinks;

    $display = !$node->ignore
        && $node->published
        && (!$node->duplicate || ($node->duplicate && !$this->osmapParams->get('ignore_duplicated_uids', 1)))
        && $node->visibleForRobots
        && $node->visibleForXML
        && trim($node->fullLink) != '';

    // Check if is external URL and if should be ignored
    if ($display && !$node->isInternal) {
        $display = $showExternalLinks === 1;
    }

    if (!$display) {
        return false;
    }

$file = 'sitemap.xml';

$current = file_get_contents($file);


    // Print the item
    echo '<url>';
	$current .= '<url>'.PHP_EOL;
    echo '<loc><![CDATA[' . $node->fullLink . ']]></loc>';
	$current .= '    <loc>' . $node->fullLink . '</loc>'.PHP_EOL;

    if (!OSMap\Helper\General::isEmptyDate($node->modified)) {
        echo '<lastmod>' . $node->modified . '</lastmod>';
		$current .= '    <lastmod>' . $node->modified . '</lastmod>'.PHP_EOL;
    }

    echo '<changefreq>' . $node->changefreq . '</changefreq>';
	$current .= '    <changefreq>' . $node->changefreq . '</changefreq>'.PHP_EOL;
    echo '<priority>' . $node->priority . '</priority>';
    $current .= '    <priority>' . $node->priority . '</priority>'.PHP_EOL;
    echo '</url>';
	$current .= '</url>'.PHP_EOL;

file_put_contents($file, $current);
    return true;
};
	$current .= '</urlset>';
// Do we need to apply XSL?
if ($this->params->get('add_styling', 1)) {
    echo '<?xml-stylesheet type="text/xsl" href="' . JUri::base() . 'index.php?option=com_osmap&amp;view=xsl&amp;format=xsl&amp;tmpl=component&amp;layout=standard&amp;id=' . $this->sitemap->id . '"?>';
}

// Start the URL set
echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
/*$current .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
*/

$this->sitemap->traverse($printNodeCallback);
$file = 'sitemap.xml';

$current = file_get_contents($file);
echo '</urlset>';
$current .= '</urlset>';
file_put_contents($file, $current);