<?php

include('output.php');

function getName($result_array, $facebook, $fid, $key) {
    $fql = 'SELECT name from user where uid = ' . $fid;
    $return_object = $facebook->api(array(
        'method' => 'fql.query',
        'query' => $fql,
            ));

    $result_array[$key]['name'] = $return_object[0]['name'];

    return $result_array;
}

function getUserWall($result_array, $facebook, $fid, $key, $nrOfPosts) {
    $time = $_SERVER['REQUEST_TIME'];
    $fql = "SELECT post_id, actor_id, target_id, message FROM stream WHERE source_id = " . $fid . " AND message != '' AND target_id == ''AND created_time < " . $time . " LIMIT " . $nrOfPosts;
    $return_object = $facebook->api(array(
        'method' => 'fql.query',
        'query' => $fql,
            ));
    
    echo '<pre>';
    print_r($return_object);
    echo '</pre>';
    
    foreach ($return_object as $index => $arrayindex) {

        if ($return_object[$index]['message'] != "") {
            $result_array[$key]['wallposts'][$index] = $return_object[$index]['message'];
        } else {
            $result_array[$key]['wallposts'][$index] = "Wallpost not containing an text-entry";
        }
    }

    if (!isset($result_array[$key]['wallposts'])) {
        $result_array[$key]['wallposts'][0] = "No wallposts were independent (not posted on another persons wall) or the wall could not be accessed";
    }

    return $result_array;
}

function getUserWallSpec($result_array, $facebook, $fid, $key, $nrOfPosts, $searchString) {
    $time = $_SERVER['REQUEST_TIME'];
    $fql = "SELECT post_id, actor_id, target_id, message FROM stream WHERE source_id = " . $fid . " AND message != '' AND target_id == ''AND created_time < " . $time . " LIMIT " . $nrOfPosts;
    $return_object = $facebook->api(array(
        'method' => 'fql.query',
        'query' => $fql,
            ));


    foreach ($return_object as $index => $arrayindex) {

        if ($return_object[$index]['message'] != "") {
            if (stristr($return_object[$index]['message'], $searchString) == TRUE) {
                $result_array[$key]['wallpostsCont'][$index] = $return_object[$index]['message'];
            }
        } else {
            $result_array[$key]['wallpostsCont'][$index] = "Wallpost not containing an text-entry";
        }
    }

    if (!isset($result_array[$key]['wallpostsCont'])) {
        $result_array[$key]['wallpostsCont'][0] = "No wallposts containting any text, no wallpost containing the specified text, or no access to the users Wall";
    }

    return $result_array;
}

function getUserLikes($result_array, $facebook, $fid, $key) {
    //get the nuber of page-likes from a specific user
    $fql = 'SELECT page_id FROM page_fan where uid = ' . $fid;
    $return_object = $facebook->api(array(
        'method' => 'fql.query',
        'query' => $fql,
            ));

    echo '<pre>';
    print_r($return_object);
    echo '</pre>';
    foreach ($return_object as $index => $arrayindex) {
        $result_array[$key]['likes'][$index] = $return_object[$index]['page_id'];
    }

    return $result_array;
}

function getHomeTown($result_array, $facebook, $fid, $key) {
    $fql = 'SELECT hometown_location, current_location from user where uid = ' . $fid;
    $return_object = $facebook->api(array(
        'method' => 'fql.query',
        'query' => $fql,
            ));
    $hometown = $return_object[0]['hometown_location']['name'];
    $current = $return_object[0]['current_location']['name'];
    $result_array[$key]['towns']['hometown'] = utf8_decode($hometown);
    $result_array[$key]['towns']['current_town'] = utf8_decode($current);

    return $result_array;
}

?>