<?php

function isTorUser($ip)
{
    $list = getTorExitList();

    if (arrayBinarySearch($ip, $list) !== false) {
        return true;
    } else {
        return false;
    }
}

function getTorExitList()
{
    $path = __DIR__ . '/tor-list.cache';

    if ( file_exists($path) && time() - filemtime($path) < 600 ) {
        $list = include $path;
        if ($list && is_array($list)) {
            return $list;
        }
    }

    $data = file('https://openinternet.io/tor/tor-node-list.txt');
    if (!$data) {
        return array();
    }

    $list = array();

    foreach($data as $line) {
        $line = trim($line);
        if ($line == '' || $line[0] == '#') continue;

        list($nick, $ip) = explode("\t", $line);
        $list[] = $ip;
    }

    sort($list);

    file_put_contents($path, sprintf("<?php return %s;", var_export($list, true)));

    return $list;
}

/**
 * Perform binary search of a sorted array.
 * Credit: http://php.net/manual/en/function.array-search.php#39115
 *
 * Tested by VigilanTor for accuracy and efficiency
 *
 * @param string $needle String to search for
 * @param array $haystack Array to search within
 * @return boolean|number false if not found, or index if found
 */
function arrayBinarySearch($needle, $haystack)
{
    $high = count($haystack);
    $low = 0;

    while ($high - $low > 1){
        $probe = ($high + $low) / 2;
        if ($haystack[$probe] < $needle){
            $low = $probe;
        } else{
            $high = $probe;
        }
    }

    if ($high == count($haystack) || $haystack[$high] != $needle) {
        return false;
    } else {
        return $high;
    }
}

?>