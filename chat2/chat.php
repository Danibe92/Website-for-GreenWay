<?php

/**
 * Simple chat example by Stephan Soller
 * See http://arkanis.de/projects/simple-chat/
 */

// Name of the message buffer file. You have to create it manually with read and write permissions for the webserver.
$messages_buffer_file = 'messages.json';
// Number of most recent messages kept in the buffer
$messages_buffer_size = 10;

if ( isset($_POST['content']) and isset($_POST['name']) )
{
	// Open, lock and read the message buffer file
	$buffer = fopen($messages_buffer_file, 'r+b');
	flock($buffer, LOCK_EX);
	$buffer_data = stream_get_contents($buffer);
	
	// Append new message to the buffer data or start with a message id of 0 if the buffer is empty
	$messages = $buffer_data ? json_decode($buffer_data, true) : array();
	$next_id = (count($messages) > 0) ? $messages[count($messages) - 1]['id'] + 1 : 0;
	$messages[] = array('id' => $next_id, 'time' => time(), 'name' => $_POST['name'], 'content' => $_POST['content']);
	
	// Remove old messages if necessary to keep the buffer size
	if (count($messages) > $messages_buffer_size)
		$messages = array_slice($messages, count($messages) - $messages_buffer_size);
	
	// Rewrite and unlock the message file
	ftruncate($buffer, 0);
	rewind($buffer);
	fwrite($buffer, json_encode($messages));
	flock($buffer, LOCK_UN);
	fclose($buffer);
	
	// Optional: Append message to log file (file appends are atomic)
	//file_put_contents('chatlog.txt', strftime('%F %T') . "\t" . strtr($_POST['name'], "\t", ' ') . "\t" . strtr($_POST['content'], "\t", ' ') . "\n", FILE_APPEND);
	
	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Simple Chat</title>
	<script type="text/javascript" src="jquery-1.4.2.min.js"></script>
	<script type="text/javascript">
		// <![CDATA[
		$(document).ready(function(){
			// Remove the "loading…" list entry
			$('ul#messages > li').remove();
			
			$('form').submit(function(){
				var form = $(this);
				var name =  form.find("input[name='name']").val();
				var content =  form.find("input[name='content']").val();
				
				// Only send a new message if it's not empty (also it's ok for the server we don't need to send senseless messages)
				if (name == '' || content == '')
					return false;
				
				// Append a "pending" message (not yet confirmed from the server) as soon as the POST request is finished. The
				// text() method automatically escapes HTML so no one can harm the client.
				$.post(form.attr('action'), {'name': name, 'content': content}, function(data, status){
					$('<li class="pending" />').text(content).prepend($('<small />').text(name)).appendTo('ul#messages');
					$('ul#messages').scrollTop( $('ul#messages').get(0).scrollHeight );
					form.find("input[name='content']").val('').focus();
				});
				return false;
			});
			
			// Poll-function that looks for new messages
			var poll_for_new_messages = function(){
				$.ajax({url: 'messages.json', dataType: 'json', ifModified: true, timeout: 2000, success: function(messages, status){
					// Skip all responses with unmodified data
					if (!messages)
						return;
					
					// Remove the pending messages from the list (they are replaced by the ones from the server later)
					$('ul#messages > li.pending').remove();
					
					// Get the ID of the last inserted message or start with -1 (so the first message from the server with 0 will
					// automatically be shown).
					var last_message_id = $('ul#messages').data('last_message_id');
					if (last_message_id == null)
						last_message_id = -1;
					
					// Add a list entry for every incomming message, but only if we not already inserted it (hence the check for
					// the newer ID than the last inserted message).
					for(var i = 0; i < messages.length; i++)
					{
						var msg = messages[i];
						if (msg.id > last_message_id)
						{
							var date = new Date(msg.time * 1000);
							$('<li/>').text(msg.content).
								prepend( $('<small />').text(date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() + ' ' + msg.name) ).
								appendTo('ul#messages');
							$('ul#messages').data('last_message_id', msg.id);
						}
					}
					
					// Remove all but the last 50 messages in the list to prevent browser slowdown with extremely large lists
					// and finally scroll down to the newes message.
					$('ul#messages > li').slice(0, -50).remove();
					$('ul#messages').scrollTop( $('ul#messages').get(0).scrollHeight );
				}});
			};
			
			// Kick of the poll function and repeat it every two seconds
			poll_for_new_messages();
			setInterval(poll_for_new_messages, 2000);
		});
		// ]]>
	</script>
<style type="text/css">
        html { margin: 0em; padding: 0; background-color: #eaf4eb; }
        body { margin: 2em; padding: 0; font-family: sans-serif; font-size: medium; color: #333; background-color: #fff; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        h1 { margin: 0; padding: 0; font-size: 2em; color: #2ecc71; }
        p.subtitle { margin: 0; padding: 0 0 0 0.125em; font-size: 0.77em; color: gray; }
        ul#messages { overflow: auto; height: 15em; margin: 1em 0; padding: 0 3px; list-style: none; border: 1px solid #2ecc71; border-radius: 10px; }
        ul#messages li { margin: 0.35em 0; padding: 0; }
        ul#messages li small { display: block; font-size: 0.59em; color: gray; }
        ul#messages li.pending { color: #aaa; }
        form { font-size: 1em; margin: 1em 0; padding: 0; }
        form p { position: relative; margin: 0.5em 0; padding: 0; }
        form p input { font-size: 1em; border: 1px solid #2ecc71; border-radius: 5px; padding: 0.5em; width: 90%; }
        form p input#name { width: 90%; }
        form p button { position: absolute; top: 0; right: 0; font-size: 1em; border: none; border-radius: 5px; padding: 0.5em 1em; background-color: #2ecc71; color: #fff; cursor: pointer; }
    </style>
    <meta name="author" content="Stephan Soller" />
</head>
<body>
<div class="container">
    <h1>Report Chat</h1>
    <p class="subtitle">Qui puoi fare domande e segnalazioni per il percorso della Greenway</p>

    <ul id="messages" class="border p-2">
        <li>loading…</li>
    </ul>

    <form action="#" method="post">
        <p>
            <input type="text" name="content" id="content" class="form-control" placeholder="Inserisci il tuo messaggio..." />
        </p>
        <p>
            <input type="text" name="name" id="name" value="Anonymous" class="form-control" placeholder="Il tuo nome..." />
            <button type="submit">Invia</button>
        </p>
    </form>
</div>
<div style="width: 100%; color:#66ff00"></img>
</body>

</html>