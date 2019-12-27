<?php
define('BOT_TOKEN', '987026308:AAEm90_C8aRdImJN4MdHUZSGkOlE5r8yxmA');
define('API_URL', "https://api.telegram.org/bot".BOT_TOKEN."/");
function processMessage($message) {
  // processa a mensagem recebida
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    
    $text = $message['text'];//texto recebido na mensagem
    if (strpos($text, "/start") === 0) {
		//envia a mensagem ao usuário
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Olá, '. $message['from']['first_name'].
		'! Eu sou um bot que informa o resultado do último sorteio da Mega Sena. Será que você ganhou dessa vez? Para começar, escolha qual loteria você deseja ver o resultado', 'reply_markup' => array(
        'keyboard' => array(array('Mega-Sena', 'Quina'),array('Lotofácil','Lotomania')),
        'one_time_keyboard' => true)));
    } else if ($text === "Mega-Sena") {
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => '1'));
    } else if ($text === "Quina") {
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => '2'));
    } else if ($text === "Lotomania") {
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => '3'));
    } else if ($text === "Lotofacil") {
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => '4'));
    } else {
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Desculpe, mas não entendi essa mensagem. :('));
    }
  } else {
    sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Desculpe, mas só compreendo mensagens em texto'));
  }
}
function sendMessage($method, $parameters) {
  $options = array(
  'http' => array(
    'method'  => 'POST',
    'content' => json_encode($parameters),
    'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
    )
);
$context  = stream_context_create( $options );
file_get_contents(API_URL.$method, false, $context );
}
//obtém as atualizações do bot
$update_response = file_get_contents(API_URL."getupdates");
$response = json_decode($update_response, true);
$length = count($response["result"]);
//obtém a última atualização recebida pelo bot
$update = $response["result"][$length-1];
if (isset($update["message"])) {
  processMessage($update["message"]);
}
?>