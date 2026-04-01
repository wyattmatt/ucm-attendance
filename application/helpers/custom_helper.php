<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Generate a v4 UUID (random)
 * 
 * @return string
 */
function generate_uuid()
{
	// PHP 7+ approach using random_bytes
	if (function_exists('random_bytes')) {
		return sprintf(
			'%s-%s-%s-%s-%s',
			bin2hex(random_bytes(4)),
			bin2hex(random_bytes(2)),
			bin2hex(chr((ord(random_bytes(1)) & 0x0f) | 0x40)) . bin2hex(random_bytes(1)),
			bin2hex(chr((ord(random_bytes(1)) & 0x3f) | 0x80)) . bin2hex(random_bytes(1)),
			bin2hex(random_bytes(6))
		);
	}

	// Fallback for older PHP versions
	return sprintf(
		'%s-%s-%s-%s-%s',
		md5(uniqid(rand(), true)),
		substr(md5(uniqid(rand(), true)), 0, 4),
		substr(md5(uniqid(rand(), true)), 0, 4),
		substr(md5(uniqid(rand(), true)), 0, 4),
		substr(md5(uniqid(rand(), true)), 0, 12)
	);
}

/**
 * Generate a simple unique ID (shorter version)
 * 
 * @return string
 */
function generate_unique_id()
{
	return md5(uniqid(rand(), true));
}
