<?PHP 

namespace Isoform;
use \Illuminate\Support\Facades as Facades;

class Validator{

	public static function rulesStrings($fields){
		$rulesStrings = [];
		foreach($fields as $field=>$rules)
			$rulesStrings[$field]=Validator::rulesString($rules);
		return $rulesStrings;
	}

	public static function rulesString(array $rules){
		$ruleStrings = [];
		foreach($rules as $rule=>$parameters)
			$ruleStrings[] = Validator::ruleString($rule,$parameters);
		return implode('|',$ruleStrings);
	}

	public static function ruleString($rule,array $parameters){
		if(count($parameters)==0)
			return $rule;
		else
			return $rule.':'.implode(':',$parameters);
	}

	public static function validate(array $values,array $fields){
		$rulesStrings = Validator::rulesStrings($fields);
		return Facades\Validator::make($values,$rulesStrings);
	}
}