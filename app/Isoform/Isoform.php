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
		$this->messages = [];
		$this->values = [];
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

		$validator = $isoform->getValidator($values,false);
		
		if($validator->fails())
			return Response::json($validator->isoformMessages,400);
		
		return Response::json($isoform->getBlankMessages(),200);
	}

	public function getBlankMessages($values = null){
		if(!$values) 
			$values = $this->values;

		return array_map(function(){
			return [];
		}, $this->values);
	}

	public function getValidator($values,$doForceRequired = true){
		$this->values = $values;

		$rulesStrings = $this->rulesStrings;

		if(!$doForceRequired)		
			foreach($rulesStrings as $field=>$ruleStrings)
				if(!isset($values[$field])) unset($rulesStrings[$field]);

		$validator = Validator::make($values, $rulesStrings);
		$validator->isoformMessages = [];

		if($validator->fails()){
			$messages = $validator->messages()->toArray();
	
			foreach($messages as $field=>$message)
				$messages[$field]=$message;


			$this->messages 
				= $validator->isoformMessages
				= array_merge($this->getBlankMessages(),$messages);
		}

		return $validator;
	}

	public function getAjaxErrorResponse(){
		return response($this->messages,400);
	}

	public function getRedirect($url,$messages = []){

		$messages = array_merge($this->messages,$messages);

		return Redirect::to($url)
			->with("isoform.{$this->namespace}.messages",$messages)
			->with("isoform.{$this->namespace}.values",$this->values);
	}

	public function __toString(){
		return json_encode([
			'namespace'=>$this->namespace
			,'fields'=>$this->fields
			,'messages'=>$this->messages
			,'values'=>$this->values
			,'rulesStrings'=>$this->rulesStrings
		],JSON_FORCE_OBJECT);
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
			return $rule.':'.implode(',',$parameters);
	}
}