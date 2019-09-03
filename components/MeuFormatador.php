<?php

namespace app\components;

use DateTimeImmutable;
use DateTimeZone;
use Faker\Provider\zh_TW\DateTime;
use IntlDateFormatter;
use yii\base\InvalidConfigException;
use yii\helpers\FormatConverter;
use yii\i18n\Formatter;

class MeuFormatador extends Formatter {

    private $_intlLoaded = false;

    public function asDatetime($value, $format = null) {
        if ($format === null) {
            $format = $this->datetimeFormat;
        }

        return $this->formatDateTimeValue($value, $format, 'datetime');
    }

    private function formatDateTimeValue($value, $format, $type) {
        $timeZone = $this->timeZone;
        // avoid time zone conversion for date-only and time-only values
        if ($type === 'date' || $type === 'time') {
            list($timestamp, $hasTimeInfo, $hasDateInfo) = $this->normalizeDatetimeValue($value, true);
            if ($type === 'date' && !$hasTimeInfo || $type === 'time' && !$hasDateInfo) {
                $timeZone = $this->defaultTimeZone;
            }
        } else {
            $timestamp = $this->normalizeDatetimeValue($value);
        }
        if ($timestamp === null) {
            return $this->nullDisplay;
        }

        // intl does not work with dates >=2038 or <=1901 on 32bit machines, fall back to PHP
        $year = $timestamp->format('Y');
        if ($this->_intlLoaded && !(PHP_INT_SIZE === 4 && ($year <= 1901 || $year >= 2038))) {
            if (strncmp($format, 'php:', 4) === 0) {
                $format = FormatConverter::convertDatePhpToIcu(substr($format, 4));
            }
            if (isset($this->_dateFormats[$format])) {
                if ($type === 'date') {
                    $formatter = new IntlDateFormatter($this->locale, $this->_dateFormats[$format], IntlDateFormatter::NONE, $timeZone, $this->calendar);
                } elseif ($type === 'time') {
                    $formatter = new IntlDateFormatter($this->locale, IntlDateFormatter::NONE, $this->_dateFormats[$format], $timeZone, $this->calendar);
                } else {
                    $formatter = new IntlDateFormatter($this->locale, $this->_dateFormats[$format], $this->_dateFormats[$format], $timeZone, $this->calendar);
                }
            } else {
                $formatter = new IntlDateFormatter($this->locale, IntlDateFormatter::NONE, IntlDateFormatter::NONE, $timeZone, $this->calendar, $format);
            }
            if ($formatter === null) {
                throw new InvalidConfigException(intl_get_error_message());
            }
            // make IntlDateFormatter work with DateTimeImmutable
            if ($timestamp instanceof \DateTimeImmutable) {
                $timestamp = new DateTime($timestamp->format(DateTime::ISO8601), $timestamp->getTimezone());
            }

            return $formatter->format($timestamp);
        }

        if (strncmp($format, 'php:', 4) === 0) {
            $format = substr($format, 4);
        } else {
            $format = FormatConverter::convertDateIcuToPhp($format, $type, $this->locale);
        }
//        if ($timeZone != null) {
//            if ($timestamp instanceof \DateTimeImmutable) {
//                $timestamp = $timestamp->setTimezone(new DateTimeZone($timeZone));
//            } else {
//                $timestamp->setTimezone(new DateTimeZone($timeZone));
//            }
//        }


        return $timestamp->format($format) ;
    }

}
