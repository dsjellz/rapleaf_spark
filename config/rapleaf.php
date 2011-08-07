<?php
//Rapleaf API Key
$config['rapleaf_api_key'] = '5abb7d999423a727bab84d236346c686';
// Rapleaf Debug Mode
$config['rapleaf_debug_mode'] = TRUE;
// Rapleaf email encoding to be used (options: md5, sha1, url)
$config['rapleaf_email_encoding'] = 'url';
// Return fields that are available for individuals with Rapleaf premium
$config['rapleaf_show_available'] = FALSE;

/*
 * ------------------------------------
 * Do not modify below here
 * ------------------------------------
 */
$config['rapleaf_base_uri'] = 'https://personalize.rapleaf.com/v4/dr';
$config['rapleaf_base_uri_bulk'] = 'https://personalize.rapleaf.com/v4/bulk';
$config['rapleaf_query_fields'] = array('email', 'first', 'middle', 'last', 'street', 'city', 'state', 'zip4');
