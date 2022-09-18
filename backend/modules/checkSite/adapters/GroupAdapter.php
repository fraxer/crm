<?php

namespace backend\modules\checkSite\adapters;

use Yii;

class GroupAdapter
{
    static public function groupSites(Array $items)
    {
        $groupedItems = [];
        $flatArray = [];


        foreach ($items as $item) {
            $object = clone $item;

            if (isset($flatArray[$object->id])) {
                $object->childs = &$flatArray[$object->id]->childs;
            }

            $flatArray[$object->id] = $object;

            if ($object->parent_id) {
                if (!isset($flatArray[$object->parent_id])) {
                    $flatArray[$object->parent_id] = (object)null;
                    $flatArray[$object->parent_id]->childs = [];
                }

                $flatArray[$object->parent_id]->childs[] = &$flatArray[$object->id];
            }

            if (!$object->parent_id && !isset($groupedItems[$object->id])) {
                $groupedItems[$object->id] = &$flatArray[$object->id];
            }
        }


        return $groupedItems;
    }
}