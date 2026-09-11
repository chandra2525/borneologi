<?php

require_once __DIR__ . '/../models/ActivityLog.php';


/**
 * Membersihkan data sebelum disimpan ke log.
 *
 * Password, password_hash, token, session ID,
 * dan data sensitif lainnya tidak boleh disimpan.
 */
function sanitizeActivityData($data)
{
    if (!is_array($data)) {
        return $data;
    }

    $sensitiveFields = [
        'password',
        'password_hash',
        'current_password',
        'new_password',
        'new_password_confirmation',
        'password_confirmation',
        'csrf_token',
        'session_id',
        'remember_token',
        'access_token',
        'refresh_token'
    ];

    foreach ($data as $key => $value) {

        $keyLower = strtolower((string) $key);

        if (in_array($keyLower, $sensitiveFields, true)) {
            unset($data[$key]);
            continue;
        }

        if (is_array($value)) {
            $data[$key] = sanitizeActivityData($value);
        }
    }

    return $data;
}


/**
 * Mengubah data menjadi JSON aman untuk log.
 */
function activityDataToJson($data)
{
    if ($data === null) {
        return null;
    }

    $data = sanitizeActivityData($data);

    $json = json_encode(
        $data,
        JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES |
            JSON_PARTIAL_OUTPUT_ON_ERROR
    );

    return $json !== false ? $json : null;
}


/**
 * Membuat request ID.
 */
function getActivityRequestId()
{
    if (!isset($_SERVER['ACTIVITY_REQUEST_ID'])) {
        $_SERVER['ACTIVITY_REQUEST_ID'] = bin2hex(random_bytes(16));
    }

    return $_SERVER['ACTIVITY_REQUEST_ID'];
}


/**
 * Mendapatkan IP address client.
 */
function getActivityIp()
{
    return $_SERVER['REMOTE_ADDR'] ?? null;
}


/**
 * Mencatat aktivitas user.
 */
function logActivity(
    $pdo,
    $activity,
    $menuName = null,
    $targetId = null,
    $resource = null,
    $oldData = null,
    $newData = null,
    $description = null,
    $status = 'SUCCESS'
) {

    try {

        $activityLog = new ActivityLog($pdo);

        $userId = $_SESSION['user_id'] ?? null;
        $username = $_SESSION['username'] ?? null;

        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        if ($userAgent !== null) {
            $userAgent = substr($userAgent, 0, 1000);
        }

        return $activityLog->create([

            'user_id' => $userId,

            'username' => $username,

            'activity' => strtoupper($activity),

            'menu_name' => $menuName,

            'target_id' => $targetId,

            'resource' => $resource,

            'old_data' => activityDataToJson($oldData),

            'new_data' => activityDataToJson($newData),

            'description' => $description,

            'status' => strtoupper($status),

            'ip_address' => getActivityIp(),

            'user_agent' => $userAgent,

            'request_id' => getActivityRequestId()
        ]);
    } catch (Throwable $e) {

        /*
         * Jangan membuat proses utama gagal hanya karena
         * pencatatan log mengalami masalah.
         */
        error_log(
            'Activity Log Error: ' . $e->getMessage()
        );

        return false;
    }
}
