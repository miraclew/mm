<?php
class Model extends ActiveRecord\Model {
	public function attributes() {
		$attributes = parent::attributes();
		foreach ($attributes as $k => $v) {
			if ($v instanceof ActiveRecord\DateTime) {
				$attributes[$k] = $v->format('Y-m-d H:i:s');
			}
		}
		
		return $attributes;
	}
}