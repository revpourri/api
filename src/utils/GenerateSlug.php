<?php

namespace Rev\Utils;

/**
 * Class GenerateSlug
 * @package Rev\Utils
 */
final class GenerateSlug
{
    /**
     * Create a slug for models requiring a unique slug string.  Removes all special characters, adds dashes for spaces
     *
     * @param string $value
     * @param object $model Must be a model object
     * @return string
     */
    public static function getSlug(string $value, object $model): string
    {
        $slug = trim(strtolower($value));
        $slug = preg_replace("/[^a-z0-9_\s-]/", "", $slug);
        $slug = preg_replace("/[\s-]+/", " ", $slug);
        $slug = preg_replace("/[\s_]/", "-", $slug);

        for ($count = 0; $count < 10; $count++) {
            if (!$model::findFirst([
                'conditions' => 'slug = :slug:',
                'bind' => [
                    'slug' => $slug . (($count > 0) ? '-' . $count : '')
                ]
            ])) {
                $slug = $slug . (($count > 0) ? '-' . $count : '');
                break;
            }
        }

        return $slug;
    }
}
