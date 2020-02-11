<?php

$queryString = http_build_query([
  'access_key' => 'd3b1a84694cd9bcec0760ae769316abf',
  'url' => 'https://www.alexa.com/siteinfo/buscape.com.br',
  // 'render_js' => 0,
]);

$ch = curl_init(sprintf('%s?%s', 'http://api.scrapestack.com/scrape', $queryString));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$website_content = curl_exec($ch);
curl_close($ch);

echo $website_content;

?>