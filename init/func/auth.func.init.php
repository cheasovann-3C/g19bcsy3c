<?php
function UsernameExist($username)
{
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE username = ?');
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows)
        return true;
    return false;

}

function registerUser($name, $username, $password)
{
    global $db;
    $query = $db->prepare('INSERT INTO tbl_users (name, username, passwd) VALUES (?, ?, ?)');
    $query->bind_param('sss', $name, $username, $password);
    $query->execute();
    if ($db->affected_rows)
        return true;

    return false;
}

function logUserIn($username, $password){
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE username = ? AND passwd = ?');
    $query->bind_param('ss', $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows) {
        return $result->fetch_aobject();
    }
    return false;
}

?>