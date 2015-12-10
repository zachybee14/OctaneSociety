<?php namespace OctaneSociety\Helpers;

class InputValidator {
	public static function required($input, $validation, $template = ':error') {
		foreach ($validation as $key => $error) {
			if (is_numeric($key)) {
				$key = $error;
				$error = "'" . $key . "' is a required field.";
			}
			if (!isset($input[$key]) || (is_string($input[$key]) && !strlen(trim($input[$key])))) {
				if (substr($error, -1) != '.')
					$error = str_replace(':error', $error, $template);
				throw new \ErrorMessageException($error);
			}
		}
	}

	public static function requiredIfPresent($input, $validation, $template = ':error') {
		foreach ($validation as $key => $error) {
			if (is_numeric($key)) {
				$key = $error;
				$error = "'" . $key . "' cannot be blank.";
			}
			if (isset($input[$key]) && is_string($input[$key]) && !strlen(trim($input[$key]))) {
				if (substr($error, -1) != '.')
					$error = str_replace(':error', $error, $template);
				throw new \ErrorMessageException($error);
			}
		}
	}

	public static function fixInputCase(&$a, &$b = null, &$c = null, &$d = null) {
		foreach ([ &$a, &$b, &$c, &$d ] as &$arg) {
			if (is_null($arg))
				continue;
			$arg = ucfirst($arg);
			if ($arg == strtoupper($arg))
				$arg = ucfirst(strtolower($arg));
		}
	}

	public static function validateEmail(&$email) {
		$email = strtolower($email);

		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			throw new \ErrorMessageException('E-mail address is not valid.');

		// add domain check, MX check, etc
	}
}