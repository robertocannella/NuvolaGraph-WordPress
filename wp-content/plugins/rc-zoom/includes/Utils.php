<?php

class Utils {

	public function logger($object):void {
		if (defined('WP_DEBUG') && WP_DEBUG) {
			error_log(print_r($object, true));
		}
	}
}