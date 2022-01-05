<?php

class Overpass2Geojson {
	
	public static $polygon;
	
    /**
     * Converts a JSON string or decoded array into a GeoJSON string or array.
     * This creates a LineString feature for each supplied way, using the nodes
     * only as points for the LineString.
     * @param  mixed   $input  JSON string or array
     * @param  boolean $encode whether to encode output as string
     * @return mixed           false if failed, otherwise GeoJSON string or array
     */
    public static function convertWays($input, $encode = true, $polygon = false) {
	    
	    self::$polygon = $polygon;
	    
        $inputArray = self::validateInput($input);
        if (!$inputArray) {
            return false;
        }
        $nodes = self::collectNodes($inputArray['elements']);
        $output = array(
            'type' => 'FeatureCollection',
            'features' => array(),
        );
        foreach ($inputArray['elements'] as $osmItem) {
            if (isset($osmItem['type']) && $osmItem['type'] === 'way') {
                $feature = self::createWayFeature($osmItem, $nodes);
                if ($feature) {
                    $output['features'][] = $feature;
                }
            }
        }
        return $encode ? json_encode($output) : $output;
    }

    /**
     * Converts a JSON string or decoded array into a GeoJSON string or array.
     * This creates a Point feature for each supplied node, ignoring ways.
     * @param  mixed   $input  JSON string or array
     * @param  boolean $encode whether to encode output as string
     * @return mixed           false if failed, otherwise GeoJSON string or array
     */
    public static function convertNodes($input, $encode = true) {
        $inputArray = self::validateInput($input);
        $nodes = self::collectNodes($inputArray['elements']);
        $output = array(
            'type' => 'FeatureCollection',
            'features' => array(),
        );
        foreach ($nodes as $node) {
            $output['features'][] = array(
                'type' => 'Feature',
                'properties' => isset($node['tags']) ? $node['tags'] : array(),
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => array($node['lon'], $node['lat']),
                ),
            );
        }
        return $encode ? json_encode($output) : $output;
    }

    private static function validateInput($input) {
        if (is_array($input)) {
            $inputArray = $input;
        } else if (is_string($input)) {
            $inputArray = json_decode($input, true);
        } else {
            return false;
        }
        if (!is_array($inputArray) ||
            !isset($inputArray['elements']) ||
            !is_array($inputArray['elements'])) {

            return false;
        }
        return $inputArray;
    }

    /**
     * Creates an array of nodes indexed by node id
     * @param  array $elements  OSM items
     * @return array            nodes e.g. [id => {lon, lat, tags}, ...]
     */
    public static function collectNodes($elements) {
        $nodes = array();
        if (!is_array($elements)) {
            return $nodes;
        }
        foreach ($elements as $osmItem) {
            if (isset($osmItem['type']) && $osmItem['type'] === 'node') {
                if (isset($osmItem['id']) && isset($osmItem['lat']) && isset($osmItem['lon'])) {
                    $nodes[$osmItem['id']] = $osmItem;
                }
            }
        }
        return $nodes;
    }

    /**
     * Creates a Feature array with geometry from matching nodes
     * @param  array $way  OSM way
     * @param  array $nodes    OSM node coordinates indexed by id
     * @return mixed           false if invalid feature otherwise
     *                         array GeoJSON Feature with LineString or Polygon geometry
     */
    public static function createWayFeature($way, $nodes) {
        $coords = array();
        if (isset($way['nodes'])) {
            foreach ($way['nodes'] as $nodeId) {
                if (isset($nodes[$nodeId])) {
                    $coords[] = array($nodes[$nodeId]['lon'], $nodes[$nodeId]['lat']);
                }
            }
        }
        if (count($coords) >= 2) {
            return array(
                'type' => 'Feature',
                'geometry' => array(
                    'type' => self::$polygon ? 'Polygon' : 'LineString',
                    'coordinates' => self::$polygon ? [$coords] : $coords,
                ),
                'properties' => isset($way['tags']) ? array_merge($way['tags'], ["id"=>$way['id']]) : ["id"=>$way['id']],
            );
        }
        return false;
    }
}
