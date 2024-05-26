<?php
    $timestamp = gmdate("D, d M Y H:i:s") . " GMT";
    header("Expires: $timestamp");
    header("Last-Modified: $timestamp");
    header("Pragma: no-cache");
    header("Cache-Control: no-cache, must-revalidate");
    header("Content-type: image/svg+xml");

    function incrementFile($filename): int {
        if (file_exists($filename)) {
            $fp = fopen($filename, "r+") or die("Failed to open the file.");
            flock($fp, LOCK_EX);
            $count = fread($fp, filesize($filename)) + 1;
            ftruncate($fp, 0);
            fseek($fp, 0);
            fwrite($fp, $count);
            flock($fp, LOCK_UN);
            fclose($fp);
        } else {
            $count = 1;
            file_put_contents($filename, $count);
        }
        return $count;
    }

    function shortNumber($num) {
        $units = ['', 'K', 'M', 'B', 'T'];
        $index = $num > 0 ? floor(log($num, 1000)) : 0;
        $num = $num / pow(1000, $index);

        return round($num, 1) . $units[$index];
    }

    $message = incrementFile("views.txt");

    $params = [
        "style" => "for-the-badge",
        "label" => "views",
        "logo" => "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0id2hpdGUiPjxwYXRoIGQ9Ik0xMiw5QTMsMyAwIDAsMSAxNSwxMkEzLDMgMCAwLDEgMTIsMTVBMywzIDAgMCwxIDksMTJBMywzIDAgMCwxIDEyLDlNMTIsNC41QzE3LDQuNSAyMS4yNyw3LjYxIDIzLDEyQzIxLjI3LDE2LjM5IDE3LDE5LjUgMTIsMTkuNUM3LDE5LjUgMi43MywxNi4zOSAxLDEyQzIuNzMsNy42MSA3LDQuNSAxMiw0LjVNMy4xOCwxMkM0LjgzLDE1LjM2IDguMjQsMTcuNSAxMiwxNy41QzE1Ljc2LDE3LjUgMTkuMTcsMTUuMzYgMjAuODIsMTJDMTkuMTcsOC42NCAxNS43Niw2LjUgMTIsNi41QzguMjQsNi41IDQuODMsOC42NCAzLjE4LDEyWiIgLz48L3N2Zz4=",
        "message" => $message,
        "labelColor" => "1850a0",
        "color" => "1d62c4",
    ];

    $url = "https://img.shields.io/static/v1?" . http_build_query($params);

    function curl_get_contents($url): string {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    echo curl_get_contents($url);

