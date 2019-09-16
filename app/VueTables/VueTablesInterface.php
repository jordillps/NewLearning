<?php
namespace App\VueTables;

interface VueTablesInterface {
    //model son les taules, fields son les columnes, relations son les relacions
	public function get($model, Array $fields, Array $relations = []);
}
