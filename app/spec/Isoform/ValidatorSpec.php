<?php

namespace spec\Isoform;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Isoform\Validator');
    }

    function it_should_create_rule_string(){
    	$this->ruleString('required',[])->shouldReturn('required');
    	$this->ruleString('min',[3])->shouldReturn('min:3');
    	$this->ruleString('between',[1,10])->shouldReturn('between:1:10');
    	$this
    		->ruleString('exists',['staff,email,account_id,1'])
    		->shouldReturn('exists:staff,email,account_id,1');
    }

    function it_should_create_rules_string(){
    	$rules = [
    		'required'=>[]
    		,'min'=>[3]
    		,'between'=>[1,10]
    		,'exists'=>['staff,email,account_id,1']
    	];

    	$this->rulesString($rules)->shouldReturn(
    		'required|min:3|between:1:10|exists:staff,email,account_id,1'
    	);
    }

    function it_should_create_rules_strings(){
    	$fields = [
    		'username'=>[
	    		'required'=>[]
	    		,'alphanumeric'=>[]
	    		,'min'=>[3]
    	    ],'password'=>[
	    		'required'=>[]
	    		,'min'=>[6]
    	    ],'age'=>[
	    		'between'=>[18,120]
    	    ],'account_number'=>[
    	    	'exists'=>['staff,email,account_id,1']
    	    ]
    	];

    	$this->rulesStrings($fields)->shouldReturn([
    		'username'=>'required|alphanumeric|min:3'
    		,'password'=>'required|min:6'
    		,'age'=>'between:18:120'
    		,'account_number'=>'exists:staff,email,account_id,1'
    	]);
    }

}
