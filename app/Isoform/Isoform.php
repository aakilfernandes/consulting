<?PHP 

namespace Isoform;
use \Illuminate\Support\Facades as Facades;

class Isoform {

	public static function ajaxValidationResponse(){
		$namespace = Facades\Input::get('namespace');
		$values = json_decode(Facades\Input::get('values'),true);
		$fields = json_decode(Facades\Input::get('fields'),true);

		$validator = Validator::validate($values,$fields);
		
		$results = array_map(function(){
			return [];
		}, $values);

		if($validator->fails()){
			$messages = $validator->messages()->toArray();
			foreach($messages as $field=>$message)
				$results[$field]=$message;
		}

		if($validator->passes())
			return Facades\Response::json($results,200);
		else
			return Facades\Response::json($results,400);
	}

	public static function getLastStringPart($string,$delimeter = '.'){
		$stringParts = explode($delimeter,$string);
		return array_pop($stringParts);
	}

	public static function getFieldNamesInNamespace($namespace){
		$fields = Facades\Config::get("isoform.$namespace");
		return array_keys($fields);
	}

	public static function validateInputs($fieldNames){
		$namespace = Facades\Input::get('_isoformNamespace');
		$fields = Isoform::fields($namespace,$fieldNames);
		$values = Facades\Input::all();

		return Validator::validate($values,$fields);
	}

	public static function redirect($url,$fieldNames,$messages){
		return Facades\Redirect::to($url)
			->with('isoformValues',Facades\Input::all())
			->with('isoformMessages',$messages);
	}

	public static function fields($namespace,$fieldNames){
		$fields = [];
		foreach($fieldNames as $fieldName){
			$rules = Facades\Config::get("isoform.$namespace.$fieldName");
			if($rules)
				$fields[$fieldName]=$rules;
		}
		return $fields;
	}

	public static function directive($namespace,$ids){	
		if(Facades\Session::has('isoformMessages'))
			$messages = Facades\Session::get('isoformMessages');
		else 
			$messages = new \stdClass;

		if(Facades\Session::has('isoformValues'))
			$values = Facades\Session::get('isoformValues');
		else 
			$values = new \stdClass;

		$isoformSeed = [
			'fields'=>Isoform::fields($namespace,$ids)
			,'messages'=>$messages
			,'values'=>$values
			,'namespace'=>$ids
		];

		return 'isoform="'.htmlspecialchars(json_encode($isoformSeed)).'"';
	}

	public static function messages(){
		if(!Facades\Session::get('isoformMessages'))
			return new \stdClass;
		else
			return Facades\Session::get('isoformMessages');
	}

}