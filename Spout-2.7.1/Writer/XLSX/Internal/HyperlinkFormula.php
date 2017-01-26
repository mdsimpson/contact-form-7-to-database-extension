<?php
/**
 * CFDB addition
 */

namespace Box\Spout\Writer\XLSX\Internal;


class HyperlinkFormula {

    /**
     * @param $stringsEscaper \Box\Spout\Common\Escaper\XLSX
     * @param $cellValue mixed
     * @param $columnIndex int
     * @param $rowIndex int
     * @return null|string XML for Excel formula or null if $cellValue is not a Hyperlink formula
     */
    public static function getHyperlinkXml($stringsEscaper, $cellValue, $columnIndex, $rowIndex) {
        // CFDB EDIT BEGIN: Special case added to handle HYPERLINK functions
        // this IF wrapping exiting code in ELSE
        $matches = array();
        if (preg_match('/=HYPERLINK\("(.*)","(.*)"\)/', $cellValue, $matches)) {
            // Create a Formula
            $url = $stringsEscaper->escape($matches[1]);
            $text = $stringsEscaper->escape($matches[2]);
            $formula = sprintf('HYPERLINK("%s","%s")', $url, $text);
            return sprintf(
                    '<c r="%s%s" t="str"><f>%s</f><v>%s</v></c>',
                    $columnIndex, $rowIndex, $formula, $text);

        }
        return null;
    }
}