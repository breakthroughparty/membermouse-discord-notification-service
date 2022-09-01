<?php
/**
 * Breakthrough discord notifier.
 *
 * @package membermouse-discord-notification-service
 *
 * @wordpress-plugin
 * Plugin Name:       Breakthrough Membermouse Discord Notification Service
 * Description:       Using the member added hook, sends a notification to discord when a new member joins. More notifications to be added.
 * Plugin URI:        https://github.com/BreakthroughParty/membermouse-discord-notification-service
 * Version:           0.1
 * Author:            Breakthrough Contributors
 * Author URI:        https://breakthroughparty.org.uk/
 */

/**
 * These are due to be replaced with a page using the WordPress Options API in
 * due course.
 */
define( 'DISCORD_BOT_TOKEN',               '' );
define( 'DISCORD_CHANNEL_ID',              '' );
define( 'MEMBERMOUSE_MEMBERSHIP_LEVEL_ID', '' );

function discord_send_message ( $options ) {
	$msgobj = json_encode( $options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

	$dAPI_SendMessage = 'https://discordapp.com/api/channels/' . DISCORD_CHANNEL_ID . '/messages';

	$ch = curl_init();

	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Authorization: Bot ' . BOT_TOKEN,
		'Content-Type: application/json',
		'Referer: https://discordapp.com/channels/@me'
	) );

	curl_setopt_array( $ch, array(
		CURLOPT_URL => $dAPI_SendMessage,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $msgobj
	) );

	$msgresponse = curl_exec( $ch );

	curl_close( $ch );

	return $msgresponse;
}

function memberAdded ( $data ) {
	if ( MEMBERMOUSE_MEMBERSHIP_LEVEL_ID == $data['membership_level'] ) {
		$msgobj = [
			'content' => 'A new member has joined the party! 🥳',
		];

		$m = discord_send_message( $msgobj );
	}
}

add_action( 'mm_member_add', 'memberAdded' );
