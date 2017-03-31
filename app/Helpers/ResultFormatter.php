<?php namespace OctaneSociety\Helpers;

use Closure;
use DateTime;
use Exception;
use Log;

use Illuminate\Database\Eloquent\Collection;

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
            if (!count($object)) {
                return [];
            }
            elseif (array_key_exists(0, $object)) {
                $result = [];
                foreach ($object as $subobject)
                    $result[] = self::format($subobject, $format);
                return $result;
            }
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

            $skipIfFalse = substr($dataKey, -1) == '?';

            if ($skipIfFalse)
                $dataKey = substr($dataKey, 0, -1);

            if (str_contains($dataKey, ':')) {
                list($sourceKey, $targetKey) = explode(':', $dataKey);
            }
            else {
                $sourceKey = $dataKey;
                $targetKey = $dataKey;
            }

            if (str_contains($sourceKey, '.')) {
                $pieces = explode('.', $sourceKey);
                $sourceKey = array_pop($pieces);
                $source = $object;
                while (count($pieces)) {
                    $piece = array_shift($pieces);
                    $source = $source->$piece;
                    if (is_null($source)) break;
                }
            }

            else {
                $source = $object;
            }

            if (substr($sourceKey, 0, 1) == '@') {
                $sourceKey = substr($sourceKey, 1);
                $source = $source->getAttributes()[$sourceKey];
                $targetKey = preg_replace('/^@/', '', $targetKey);
            }
            elseif (is_array($source)) {
                $source = $source[$sourceKey];
            }
            elseif (!is_null($source)) {
                $source = $source->$sourceKey;
            }

            if ($skipIfFalse && $source == false)
                continue;

            if (str_contains($targetKey, '.'))
                $targetKey = preg_replace('/^.*\./', '', $targetKey);

            if (is_object($source) && $source instanceof DateTime)
                $item[$targetKey] = $source->format('c');

            elseif (is_null($subformat))
                $item[$targetKey] = $source;

            elseif (is_null($source))
                $item[$targetKey] = null;

            else {
                try {
                    $item[$targetKey] = self::format($source, $subformat);
                } catch (Exception $e) {
                    Log::debug('Exception was encountered while formatting target key: ' . $targetKey);
                    throw $e;
                }
            }
        }

        return $item;
    }
}