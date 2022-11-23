<?php

namespace App\Http\Presenters;

class Missing {
    /**
     * @return bool
     */
    public function isMissing(): bool {
        return true;
    }
}
/**
 * @method array body()
 */
abstract class AnonymousPresenter {

    /**
     * @param array $items
     * @return array
     */
    public static function fromArray(array $items): array {
        $presenter = new static();
        return array_map(fn(object $item) => $presenter->toArray($item), $items);
    }

    /**
     * @param mixed $items
     * @return array
     */
    public static function present(...$items): array {
        return (new static())->toArray(...$items);
    }

    /**
     * @param ...$item
     * @return array
     */
    public function toArray(...$item): array {
        $presentingValues = [];

        foreach ($this->body(...$item) as $key => $prItem) {
            if (!$prItem instanceof Missing) {
                $presentingValues[$key] = $prItem;
            }
        }

        return $presentingValues;
    }

    /**
     * @param bool $condition
     * @param mixed $value
     * @return Missing|mixed
     */
    protected function when(bool $condition, mixed $value): mixed {
        return $condition ? $value : new Missing();
    }
}
