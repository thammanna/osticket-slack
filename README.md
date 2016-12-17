osTicket-slack
==============
An plugin for [osTicket](https://osticket.com) which posts notifications to a [Slack](https://slack.com) channel.

Install
--------
Clone this repo or download the zip file and place the contents into your `include/plugins` folder.
Login (admin level) in your Osticket activate and visit settings:
* Webhook URL
Required: this is your Slack webhook URL
* Ignore when subject equals regex
Optional: do you want to ignore specific subjects? Set a PHP regex here.
leave empty to allow all tickets to be sent to slack

Changelog
---------
0.3 - 17 december 2016
[bugfix] "PHP syntax error" by ramonfincken

0.2 - 17 december 2016
[feature] "Ignore when subject equals regex" by ramonfincken

0.1 - 3 december 2014
[release] "Init" by thammanna

Authors
-------
thammanna https://github.com/thammanna/osticket-slack
ramonfincken https://github.com/ramonfincken/osticket-slack

Info
------
This plugin uses CURL and tested on osTicket-1.8 and osTicket-1.9.12.
