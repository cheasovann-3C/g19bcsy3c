<?php

function usernameExists($username)
{
    global $db;

    $query = $db->prepare('SELECT * FROM tbl_users WHERE username = ?');
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows) {
        return true;
    }

    return false;
}


function registerUser($name, $username, $passwd)
{
    global $db;

    if (usernameExists($username)) {
        return false;
    }

    $query = $db->prepare(
        'INSERT INTO tbl_users (name, username, passwd) VALUES (?, ?, ?)'
    );
    $query->bind_param('sss', $name, $username, $passwd);
    $query->execute();

    if ($db->affected_rows) {
        return true;
    }

    return false;
}


/* =========================
   LOGIN USER
========================= */
function logUserIn($username, $password)
{
    global $db;

    $query = $db->prepare(
        'SELECT * FROM tbl_users WHERE username = ? AND passwd = ?'
    );
    $query->bind_param('ss', $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_object();
    }

    return false;
}


/* =========================
   GET LOGGED IN USER
========================= */
function loggedIn()
{
    global $db;

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    $user_id = $_SESSION['user_id'];

    $query = $db->prepare('SELECT * FROM tbl_users WHERE id = ?');
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_object();
    }

    return false;
}
