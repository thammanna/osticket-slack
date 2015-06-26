<?php

/**
 * Plugin that sends osticket tickets to slack
 *
 * @Quivr.be
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__file__).'/include');

return array(
    'id' =>           'osticket:slack', 
    'version' =>      '1.0',
    'name' =>         'Slack notifier',
    'author' =>       'Quivr',
    'description' =>  'Notify Slack on new ticket.',
    'url' =>          'https://github.com/Quivr/osticket-slack',
    'plugin' =>       'slack.php:SlackPlugin',  //file name:class name
);

?>