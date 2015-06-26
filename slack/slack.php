<?php

require_once(INCLUDE_DIR . 'class.plugin.php');
require_once(INCLUDE_DIR . 'class.signal.php');

require_once('config.php');

class SlackPlugin extends Plugin {
    var $config_class = "SlackPluginConfig";

    function bootstrap() {		
        Signal::connect('model.created', array($this, 'onTicketCreated'));		
    }

    function onTicketCreated($answer){		
        try {			
            global $ost;

            if (!($ticket = Ticket::lookup($answer->object_id))) {
                die('Unknown or invalid ticket ID.');
            }

            //Slack payload                 
            $payload = array(
                'attachments' =>
                array (
                    array (	
                        'pretext' => "New Ticket <" . $ost->getConfig()->getUrl() . "scp/tickets.php?id=" 
                        . $ticket->getId() . "|#" . $ticket->getId() . "> in ". Format::htmlchars($ticket->getDept() instanceof Dept ? $ticket->getDept()->getName() : '') 
                        ." via " . $ticket->getSource() . " (" . Format::db_datetime($ticket->getCreateDate()) . ")",
                        'fallback' => "New Ticket <" . $ost->getConfig()->getUrl() . "scp/tickets.php?id=" 
                        . $ticket->getId() . "|#" . $ticket->getId() . "> in ". Format::htmlchars($ticket->getDept() instanceof Dept ? $ticket->getDept()->getName() : '') 
                        ." via " . $ticket->getSource() . " (" . Format::db_datetime($ticket->getCreateDate()) . ")",
                        'color' => "#D00000",
                        'fields' => 
                        array(
                            array (
                                'title' => "From: " . mb_convert_case(Format::htmlchars($ticket->getName()), MB_CASE_TITLE) . " (".$ticket->getEmail().")",
                                'value' => '',//TODO Format::htmlchars($ticket->getSubject()),
                                'short' => false
                            ),											
                        ),
                    ),
                ),
            );

            //Curl to webhook
            $data_string = utf8_encode(json_encode($payload));
            $url = $this->getConfig()->get('slack-webhook-url');

            if (!function_exists('curl_init')){
                error_log('osticket slackplugin error: cURL is not installed!');
            }

            $ch = curl_init($url);                                      
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    

            $result = curl_exec($ch);

            if($result === false){
                error_log($url . ' - ' . curl_error($ch));
            }
            else{
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if($statusCode != '200'){
                    error_log($url . ' Http code: ' . $statusCode);					
                }				
            }
            curl_close($ch);
        }
        catch(Exception $e) {
            error_log('Error posting to Slack. '. $e->getMessage());
        }
    }	
}

?>