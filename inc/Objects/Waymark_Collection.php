<?php

class Waymark_Collection {
	public $collection_id = null;
	public $Maps = array();
	public $title = null;

	function __construct($collection_id = null) {
		$collection = get_term_by('term_taxonomy_id', $collection_id);	
		
		//Valid Collection
		if($collection) {
			$this->title = $collection->name;
			$this->collection_id = $collection_id;
		
			//Get Maps
			$posts = get_posts(array(
				'posts_per_page' => -1,
				'post_type' => 'waymark_map',				
				'tax_query' => array(
					array(
						'taxonomy' => 'waymark_collection',
						'field' => 'term_id',
						'terms' => $collection_id,
					)
				)
			));
			
			//Has Maps
			if(sizeof($posts)) {
				foreach($posts as $post) {
					$Map = new Waymark_Map($post->ID);
				
					$this->Maps[$post->ID] = $Map;
				}			
			}		
		}
	}
}