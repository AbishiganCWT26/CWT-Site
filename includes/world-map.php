<?php

function worldMapCatalog(): array {
    static $countries = null;
    if ($countries === null) {
        $data = json_decode(file_get_contents(__DIR__ . '/world-map-countries.json'), true, 512, JSON_THROW_ON_ERROR);
        $countries = [];
        foreach ($data['countries'] as $country) $countries[$country['code']] = $country;
    }
    return $countries;
}

function worldMapSelected(PDO $pdo): array {
    $codes = $pdo->query('SELECT country_code FROM world_map_countries WHERE is_active = 1')->fetchAll(PDO::FETCH_COLUMN);
    $catalog = worldMapCatalog();
    return array_values(array_filter($catalog, static fn($country) => in_array($country['code'], $codes, true)));
}
