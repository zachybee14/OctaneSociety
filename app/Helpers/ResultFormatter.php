<?php namespace OctaneSociety\Helpers;

use \Illuminate\Database\Eloquent\Collection;
use \Closure;

/*
	ResultFormatter::format($sourceObject, [
		'some_field_from_source_object',
		'another_field_from_source_object',
		
		'a_subobject_on_source_object.field_on_subobject', // will output 'field_on_subobject'
		
		'field_with_a_shitty_name:the_name_to_output',
		'some_subobject.subobject_with_with_shitty_name:the_name_to_output',
		
		'subcollection' => [
			'some_field_from_an_item_in_subcollection',
			'another_field_from_an_item_in_subcollection',
			'etc'
		]
	]);
*/

class ResultFormatter {
	public static function format($object, $format) {
		if (is_null($object))
			return null;

		if (is_array($object)) {
			$result = [];
			foreach ($object as $subobject)
				$result[] = self::format($subobject, $format);
			return $result;
		}
		
		if ($object instanceof Collection) {
			$result = [];
			$object->each(function($subobject) use (&$result, $format) {
				$result[] = self::format($subobject, $format);
			});
			return $result;
		}

		if ($format instanceof Closure) {
			return $format($object);
		}

		$item = [];
		foreach ($format as $key => $value) {
			if (is_numeric($key)) {
				$dataKey = $value;
				$subformat = null;
			}
			else {
				$dataKey = $key;
				$subformat = $value;
			}

			if (str_contains($dataKey, ':')) {
				list($sourceKey, $targetKey) = explode(':', $dataKey);
			}
			else {
				$sourceKey = $dataKey;
				$targetKey = $dataKey;
			}

			if (str_contains($sourceKey, '.')) {
				$sourceKey = explode('.', $sourceKey);
				$source = $object;
				while (count($sourceKey)) {
					$piece = array_shift($sourceKey);
					if (!isset($source->$piece)) {
						$source = null;
						break;
					}
					$source = $source->$piece;
				}
			}
			else {
				$source = $object->$sourceKey;
			}

			if (str_contains($targetKey, '.'))
				$targetKey = preg_replace('/^.*\./', '', $targetKey);

			if (is_object($source) && $source instanceof \Carbon\Carbon)
				$item[$targetKey] = $source->toDateTimeString();

			elseif (is_null($subformat))
				$item[$targetKey] = $source;

			elseif (is_null($source))
				$item[$targetKey] = null;

			else
				$item[$targetKey] = self::format($source, $subformat);
		}

		return $item;
	}
}