<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;



class Visit extends Model{

    const COLORS = [
		'fbd0d0',
		'ffbcbc',
		'ff8484',
		'ffe5ca',
		'ffd0a0',
		'ffbf7e',
		'f0e3ae',
		'ead270',
		'f7d343',
		'a4d999',
		'6ec85b',
		'badddd',
		'6ec4c4',
		'269999',
		'bebfec',
		'7577d0',
		'eabaee',
		'dd76e6',
		'c148cc',
	];

	public static function getRandomColor(){
		return Arr::random(self::COLORS);
	}

	public function save(array $options = []){
		$this->color = self::getRandomColor();
		return parent::save($options);
	}

}
