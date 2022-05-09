<?php
/**
 * Breakthrough discord notifier.
 *
 * @package mm-discord-notify
 *
 * @wordpress-plugin
 * Plugin Name:       Breakthrough Membermouse Discord Notifier
 * Description:       Using the member added hook, sends a notification to discord when a new member joins. More notifications to be added.
 * Plugin URI:        https://github.com/BreakthroughParty
 * Version:           0.1
 * Author:            Breakthrough Contributors
 * Author URI:        https://breakthroughparty.org.uk/
 */
require_once("../wp-load.php");
require_once("../wp-content/plugins/membermouse/includes/mm-constants.php");
require_once("../wp-content/plugins/membermouse/includes/init.php");

define("BOT_TOKEN","{replace with your bot token}");
function discord_send_message($options) {
	$msgobj=json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	$channelid = "{replace with channel ID to send to}"
	$dAPI_SendMessage = "https://discordapp.com/api/channels/{$channelid}/messages";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER,
		array( "Authorization: Bot " . BOT_TOKEN,
				'Content-Type: application/json',
				'Referer: https://discordapp.com/channels/@me'
		));

	curl_setopt_array( $ch, [
		CURLOPT_URL => $dAPI_SendMessage,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $msgobj]);


	$msgresponse = curl_exec( $ch );
	curl_close( $ch );
	return $msgresponse;
}
function memberAdded($data)  
{
	if ($data[membership_level] == 2) {
		$msgobj = [
			"content" => "A new member has joined the party! 🥳",
		];
		$m=discord_send_message($msgobj);
	}
}
add_action('mm_member_add', 'memberAdded');
?>
