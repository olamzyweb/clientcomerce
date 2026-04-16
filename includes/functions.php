<?php
// formatting functions and db queries helper

function format_naira($amount) {
    return '₦' . number_format($amount, 2);
}

function get_store_info($pdo) {
    $stmt = $pdo->query("SELECT * FROM store_info LIMIT 1");
    $result = $stmt->fetch();
    if (!$result) {
        return [
            'name' => 'Naija Luxe Store',
            'logo' => '',
            'phone' => '',
            'whatsapp' => '',
            'email' => '',
            'address' => '',
            'delivery_areas' => '',
            'bank_name' => '',
            'account_number' => '',
            'account_name' => '',
            'primary_color' => '#832232'
        ];
    }
    return $result;
}

function get_settings($pdo) {
    try {
        $stmt = $pdo->query("SELECT key_name, key_value FROM settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['key_name']] = $row['key_value'];
        }
        return $settings;
    } catch(Exception $e) {
        return [];
    }
}

function generate_slug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return trim($slug, '-');
}
?>
