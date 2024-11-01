<?php

class CoronaFeedSource {
    /**
     * @return array
     */
    public function resolveFeedJSON()
    {
        $feedCachePath = get_temp_dir() . '/quarantine-feed.cache.json';

        if (!file_exists($feedCachePath) || filemtime($feedCachePath) < time() - 300) {
            $response = wp_remote_get(
                'https://api.quarantine.country/',
                array(
                    'timeout' => 15,
                    'compress' => true,
                    'sslverify' => true
                )
            );

            if ( is_wp_error( $response ) ){
                return [];
            }

            $feed = wp_remote_retrieve_body($response);
            file_put_contents($feedCachePath, $feed);
            touch($feedCachePath);
        } else {
            $feed = file_get_contents($feedCachePath);
        }

        return json_decode($feed, true);
    }

    /**
     * @param string $region
     * @return array
     */
    public function resolveRegionData($region)
    {
        $feed = $this->resolveFeedJSON();
        $key = strtolower(trim($region));

        if (array_key_exists('regions', $feed) && array_key_exists($key, $feed['regions'])) {
            return $feed['regions'][$key];
        }

        return [];
    }
}