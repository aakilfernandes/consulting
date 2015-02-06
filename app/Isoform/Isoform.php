<?PHP 

namespace Isoform;
use \Illuminate\Support\Facades\Config;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Response;
use \Illuminate\Support\Facades\Validator;
use \Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Session;
use \stdClass;

class Isoform {

	public function __construct($namespace){
		$this->namespace = $namespace;
		$this->fields = Config::get('isoform.'.$namespace);
		$this->rulesStrings = $this->getRulesStrings();
		$this->messages = new stdClass;
		$this->values = new stdClass;
	}

	public static function getSeed($namespace){
		$isoform = new Isoform($namespace);

		if(Session::has("isoform.$namespace.messages"))
			$isoform->messages = Session::get("isoform.$namespace.messages");

		if(Session::has("isoform.$namespace.values"))
			$isoform->values = Session::get("isoform.$namespace.values");

		return htmlspecialchars((string) $isoform);
	}

	public static function ajaxValidationResponse(){

		$isoform = new Isoform(Input::get('namespace'));	
		$values = json_decode(Input::get('values'),true);

		$validator = $isoform->getValidator($values);
		
		if($validator->fails())
			return Response::json($validator->isoformMessages,400);

		
		$messages = array_map(function(){
			return [];
		}, $values);

		return Response::json($messages,200);
	}

	public function getValidator($values){
		$this->values = $values;

		$validator = Validator::make($values,$this->rulesStrings);
		$validator->isoformMessages = [];
	
		if($validator->fails()){
			$messages = $validator->messages()->toArray();
	
			foreach($messages as $field=>$message)
				$validator->isoformMessages[$field]=$message;
	
			$this->messages = $validator->isoformMessages;
		}

		
		

		return $validator;
	}

	public function getRedirect($url){
		return Redirect::to($url)
			->with("isoform.{$this->namespace}.messages",$this->messages)
			->with("isoform.{$this->namespace}.values",$this->values);
	}

	public function __toString(){
		return json_encode([
			'namespace'=>$this->namespace
			,'fields'=>$this->fields
			,'messages'=>$this->messages
			,'values'=>$this->values
			,'rulesStrings'=>$this->rulesStrings
		]);
	}

	public function getRulesStrings(){
		$rulesStrings = [];
		foreach($this->fields as $field=>$rules)
			$rulesStrings[$field]=$this->getRulesString($rules);
		return $rulesStrings;
	}

	public function getRulesString(array $rules){
		$ruleStrings = [];
		foreach($rules as $rule=>$parameters)
			$ruleStrings[] = $this->getRuleString($rule,$parameters);
		return implode('|',$ruleStrings);
	}

	public function getRuleString($rule,array $parameters){
		if(count($parameters)==0)
			return $rule;
		else
			return $rule.':'.implode(':',$parameters);
	}
}