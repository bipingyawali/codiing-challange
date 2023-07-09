<?php

class WallysWidgetsCalculator
{
    /**
     * Your solution should return an array with the pack sizes as the key
     * and the number of packs in that size as the value.
     *
     * Pack sizes that are not required should NOT be included.
     *
     * Example:
     *
     * getPacks(251, [250, 500, 1000])
     *
     * Should return:
     *
     * [500 => 1]
     */
    public function getPacks(int $widgetsRequired, array $packSizes): array
    {
        $packsToSend = array();

        // Find the largest pack size that can fulfill the order without exceeding it
        for ($i = count($packSizes) - 1; $i >= 0; $i--) {
            if ($widgetsRequired >= $packSizes[$i]) {
                $numPacks = intval($widgetsRequired / $packSizes[$i]);

                // Add the pack size and the number of packs to the array
                $packsToSend[] = array($packSizes[$i], $numPacks);

                // Update the remaining order size
                $widgetsRequired -= $numPacks * $packSizes[$i];
            }
        }

        if ($widgetsRequired > 0 && $widgetsRequired >= $packSizes[0]) {
            for ($i = 1; $i < count($packSizes); $i++) {
                if ($widgetsRequired >= $packSizes[$i] && $widgetsRequired < $packSizes[$i - 1]) {
                    $packsToSend[] = array($packSizes[$i], 1);
                    break;
                }
            }
        } else if ($widgetsRequired > 0 && $widgetsRequired < $packSizes[0]) {
            $packsToSend[] = [$packSizes[0], 1];
        }

        return $this->combineWidget($packsToSend, $packSizes);
    }


    function combineWidget($packs, $packSizes): array
    {
        $this->updateArray($packs);
        $combineArray = [];
        $updatedIndex = [];
        foreach ($packs as $pack) {
            if(key_exists($pack[0], $combineArray)) {
                $combineArray[$pack[0]] = $combineArray[$pack[0]]+1;
                $updatedIndex [] = $pack[0];
            } else {
                $combineArray[$pack[0]] = $pack[1];
            }
        }
        $newWidgetSizes = [];
        foreach ($combineArray as $key => $na) {
            $desiredValue = (int) $key * $na;
            if (in_array($desiredValue, $packSizes)) {
                $newWidgetSizes[$desiredValue] = 1;
            } else {
                if(in_array($key, $updatedIndex)) {
                    // Find the nearest greater value
                    foreach ($packSizes as $packSize) {
                        if ($packSize > $desiredValue) {
                            $newWidgetSizes[$packSize] = 1;
                            break;
                        }
                    }
                } else {
                    $newWidgetSizes[$key] = $na;
                }
            }
        }
        return $newWidgetSizes;
    }

    function updateArray(&$array): void
    {
        $modified = false;

        for ($i = 0; $i < count($array); $i++) {
            $value = $array[$i];
            $product = $value[0] * $value[1];

            $foundDuplicate = false;

            for ($j = 0; $j < count($array); $j++) {
                if ($j !== $i && $array[$j][0] === $product) {
                    $array[$j][1]++;
                    $foundDuplicate = true;
                    break;
                }
            }

            if ($foundDuplicate) {
                unset($array[$i]);
                $array = array_values($array); // Re-index the array
                $modified = true;
                break;
            }
        }

        $array = array_values($array); // Re-index the array

        if ($modified) {
            $this->updateArray($array);
        }
    }
}