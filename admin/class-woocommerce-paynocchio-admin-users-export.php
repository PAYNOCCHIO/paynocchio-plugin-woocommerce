<?php
/**
 * Creates the Users Export Page for the plugin.
 *
 * @package Wocommerce_Paynocchio
 */
/**
 * Creates the Users Export Page for the plugin.
 *
 * Provides the functionality necessary for rendering the page corresponding
 * to the submenu with which this page is associated.
 *
 * @package Wocommerce_Paynocchio
 */
class Paynocchio_Users_Export_Page {
    /**
     * This function renders the contents of the page associated with the
     * Paynocchio_Users_Export_Menu that invokes the render method. In
     * the context of this plugin, this is the Paynocchio_Users_Export_Page class.
     */
    public function render()
    {

        if (isset($_GET['date_from'])) {
            $date_from = htmlspecialchars($_GET['date_from']);
        } else {
            $date_from = '';
        }

        if (isset($_GET['date_to'])) {
            $date_to = htmlspecialchars($_GET['date_to']);
        } else {
            $date_to = '';
        }

        echo '
        <div class="wrap">
            <h1 class="wp-heading-inline">Paynocchio Users Export</h1>
            <form action="" method="get">
                <input type="hidden" name="page" value="paynocchio-export-users" />
                <div class="paynocchio_filters">
                    <div class="paynocchio_filter">
                        <label for="date_from">
                        <input type="date" name="date_from" id="date_from" value="' . $date_from . '" />
                        </label>
                    </div>
                    <div class="paynocchio_filter">
                        <label for="date_to">
                        <input type="date" name="date_to" id="date_to" value="' . $date_to . '" />
                        </label>
                    </div>
                    <input type="submit" class="paynocchio_submit" id="filters" value="Filter" />
                </div>
                
            </form>';

        function dateFromString ($dateparam, $date) {
            $dateUnix = strtotime($date);
            return date($dateparam, $dateUnix);
        }

        $date_query = [];
        $date_from_array = [];
        $date_to_array = [];

        if ($date_from != '') {
            $date_from_array = [
                'year' => dateFromString('Y', $date_from),
                'month' => dateFromString('m', $date_from),
                'day' => dateFromString('d', $date_from),
            ];
        }
        if ($date_to != '') {
            $date_to_array = [
                'year' => dateFromString('Y', $date_to),
                'month' => dateFromString('m', $date_to),
                'day' => dateFromString('d', $date_to),
            ];
        }

        if ($date_from_array != '' || $date_to_array != '') {
            $date_query = [
                'relation' => 'AND',
                [
                    'after' => $date_from_array,
                    'before' => $date_to_array,
                    'inclusive' => true
                ],
            ];
        }

        $args = array(
            'meta_query' => array(
                array(
                    'key' => PAYNOCCHIO_WALLET_KEY,
                    'compare' => 'NOT EXISTS',
                )
            ),
            'date_query' => $date_query,
            'orderby' => 'registered',
            'order' => 'ASC',
        );

        $users = get_users($args);
        $result = array();

        foreach ($users as $user) {
            $user_array = array(
                $user->ID,
                get_user_meta($user->ID, PAYNOCCHIO_USER_UUID_KEY, true), // USER_UUID
                get_option('woocommerce_paynocchio_settings')[PAYNOCCHIO_CURRENCY_KEY], // CURRENCY_UUID
                'type',
                'status'
            );
            array_push($result, $user_array);
        }

        if (count($result)) {
            echo '<table class="paynocchio_table"><thead><tr><td>USER ID</td><td>USER UUID</td><td>CURRENCY UUID</td><td>TYPE</td><td>STATUS</td></tr></thead><tbody>';
            foreach ($result as $row) {
                echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td><td>' . $row[3] . '</td><td>' . $row[4] . '</td></tr>';
            }
            echo '</tbody></table>';
        }

        function curPageURL() {
            $pageURL = 'http';
            if(isset($_SERVER["HTTPS"]))
                if ($_SERVER["HTTPS"] == "on") {
                    $pageURL .= "s";
                }
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            }
            return $pageURL;
        }
        $url = curPageURL();
        $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'export=1';

        if (count($result) == 0) {
            return null;
        } else {
            echo '<a href="'.$url.'" class="paynocchio_export">Export Users</a>';
        }
        echo '</div>';

        if(isset($_GET['export'])) {
            if ($_GET['export'] == 1) {
                $this->csv_export($result);
            }
        }
    }

    public function csv_export ($result) {
        function array2csv($array) {
            ob_clean();
            ob_start();
            $df = fopen("php://output", 'w');
            foreach ($array as $row) {
                fputcsv($df, $row, ';');
            }
            fclose($df);
            return ob_get_clean();
        }

        function download_send_headers($filename) {
            // disable caching
            $now = gmdate("D, d M Y H:i:s");
            header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
            header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
            header("Last-Modified: {$now} GMT");

            // force download
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");

            // disposition / encoding on response body
            header("Content-Disposition: attachment;filename={$filename}");
            header("Content-Transfer-Encoding: binary");
        }

        download_send_headers("user_export_" . date("Y-m-d") . ".csv");
        echo array2csv($result);
        die();
    }
}