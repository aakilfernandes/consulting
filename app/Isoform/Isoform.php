<?PHP 

namespace Isoform;
use \Illuminate\Support\Facades as Facades;

class Isoform extends \Eloquent {

	public static function ajaxValidationResponse(){
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

	public static function validateInputs($fieldNames){
		$fields = Isoform::fields($fieldNames);
		$values = Isoform::namespacedInputs($fieldNames);
		return Validator::validate($values,$fields);
	}

	public static function namespacedInputs($fieldNames){
		$values = Facades\Input::all();
		foreach($fieldNames as $fieldName){
			if(isset($values[$fieldName])) continue;

			$fieldNameParts = explode('.',$fieldName);
			$fieldNameLast = end($fieldNameParts);


			if(!isset($values[$fieldNameLast])) continue;

			$values[$fieldName] = $values[$fieldNameLast];
			unset($values[$fieldNameLast]);
		}
		return $values;
	}

	public static function redirect($url,$fieldNames,$messages){
		return Facades\Redirect::to($url)
			->with('isoformValues',Isoform::namespacedInputs($fieldNames))
			->with('isoformMessages',$messages);
	}

	public static function fields($fieldNames){
		$fields = [];
		foreach($fieldNames as $fieldName){
			$rules = Facades\Config::get("isoform.$fieldName");
			if($rules)
				$fields[$fieldName]=$rules;
		}
		return $fields;
	}

	public static function directive(){
		$ids = func_get_args();
		
		if(Facades\Session::has('isoformMessages'))
			$messages = Facades\Session::get('isoformMessages');
		else 
			$messages = new \stdClass;

		if(Facades\Session::has('isoformValues'))
			$values = Facades\Session::get('isoformValues');
		else 
			$values = new \stdClass;

		$isoformSeed = [
			'fields'=>Isoform::fields($ids)
			,'messages'=>$messages
			,'values'=>$values
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