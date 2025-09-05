<?php

namespace DL\EstimatedDelivery;

defined('ABSPATH') || exit;

final class Days
{

    /**
     * Añadimos un dia mas si el pedido es despues de las 12
     * @param int $days
     * @param int $product_id
     * @return int
     * @author Daniel Lucia
     */
    public function addIfIsToday(int $days, int $product_id)
    {

        //Si son mas de las 12:00 sumamos un dia a today
        if (current_time('H') >= 12) {
            $days = (int)$days + 1;
        }

        return $days;
    }

    /**
     * Añadimos dias de preparación del producto si tiene
     * @param string $date
     * @param int $product_id
     * @return string
     * @author Daniel Lucia
     */
    public function addPreparationDaysFromProduct(int $days, int $product_id = 0): string
    {

        $preparation_days = 0;
        if ($product_id > 0) {
            $preparation_days = (int)get_post_meta($product_id, '_dl_preparation_days', true);
        }

        return $days + $preparation_days;
    }

    /**
     * Sumamos un dia si la fecha de llegada es domingo
     * @param int $days
     * @param int $product_id
     * @return int
     * @author Daniel Lucia
     */
    public function addDaysIfIsSunday(int $days, int $product_id): int
    {
        $date = new \DateTime();
        $added = 0;
        $i = 0;

        while ($i < $days + $added) {
            $date_check = clone $date;
            $date_check->modify("+{$i} days");

            if ($date_check->format('N') === '7') {
                $added++;
            }

            $i++;
        }

        return $days + $added;
    }

    /**
     * Comprobamos los dias festivos
     * @param int $days
     * @param int $product_id
     * @return int
     * @author Daniel Lucia
     */
    public function addDaysIfIsHoliday(int $days, int $product_id): int
    {
        $holidays = get_option('dl_estimated_delivery_holidays', []);

        $date = new \DateTime();
        $added = 0;
        $i = 0;

        while ($i < $days + $added) {
            $date_check = clone $date;
            $date_check->modify("+{$i} days");

            if (in_array($date_check->format('Y-m-d'), $holidays)) {
                $added++;
            }

            $i++;
        }
        
        return $days + $added;
    }
}
