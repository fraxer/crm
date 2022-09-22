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
                    $flatArray[$object->parent_id]->sectionStatus = 200;
                }

                $flatArray[$object->parent_id]->childs[] = &$flatArray[$object->id];
            }

            if (!$object->parent_id && !isset($groupedItems[$object->id])) {
                $groupedItems[$object->id] = &$flatArray[$object->id];
            }
        }

        return self::setStatusesSection($groupedItems);
    }

    static private function setStatusesSection(Array& $items)
    {
        foreach ($items as &$item) {
            $item->sectionStatus = self::getStatus($item->childs);

            foreach ($item->childs as &$childItem) {
                $status = self::getStatus($childItem->childs);

                if ($childItem->actualStatus->status != 200) {
                    $item->sectionStatus = $status = $childItem->actualStatus->status;
                }

                $childItem->sectionStatus = $status;
                $childItem->childs = self::setStatusesSection($childItem->childs);
            }
        }

        return $items;
    }

    static private function getStatus(Array& $items)
    {
        $status = 200;

        foreach ($items as &$item) {
            if ($item->actualStatus->status != 200) {
                return $item->actualStatus->status;
            }
        }

        return $status;
    }
}