<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => "A :attribute must be accepted.",
	"active_url"           => "A :attribute is not a valid URL.",
	"after"                => "A :attribute must be a date after :date.",
	"alpha"                => "A :attribute may only contain letters.",
	"alpha_dash"           => "A :attribute may only contain letters, numbers, and dashes.",
	"alpha_num"            => "A :attribute may only contain letters and numbers.",
	"array"                => "A :attribute must be an array.",
	"before"               => "A :attribute must be a date before :date.",
	"between"              => array(
		"numeric" => "A :attribute must be between :min and :max.",
		"file"    => "A :attribute must be between :min and :max kilobytes.",
		"string"  => "A :attribute must be between :min and :max characters.",
		"array"   => "A :attribute must have between :min and :max items.",
	),
	"boolean"              => "A :attribute must be true or false.",
	"confirmed"            => "A :attribute confirmation does not match.",
	"date"                 => "A :attribute is not a valid date.",
	"date_format"          => "A :attribute does not match the format :format.",
	"different"            => "A :attribute and :other must be different.",
	"digits"               => "A :attribute must be :digits digits.",
	"digits_between"       => "A :attribute must be between :min and :max digits.",
	"email"                => "A :attribute must be a valid email address.",
	"exists_email"         => "Email has not been registered yet.",
	"exists"         	   => "Somethings not right here...",
	"image"                => "A :attribute must be an image.",
	"in"                   => "A selected :attribute is invalid.",
	"integer"              => "A :attribute must be an integer.",
	"ip"                   => "A :attribute must be a valid IP address.",
	"max"                  => array(
		"numeric" => "A :attribute may not be greater than :max.",
		"file"    => "A :attribute may not be greater than :max kilobytes.",
		"string"  => "A :attribute may not be greater than :max characters.",
		"array"   => "A :attribute may not have more than :max items.",
	),
	"mimes"                => "A :attribute must be a file of type: :values.",
	"min"                  => array(
		"numeric" => "A :attribute must be at least :min.",
		"file"    => "A :attribute must be at least :min kilobytes.",
		"string"  => "A :attribute must be at least :min characters.",
		"array"   => "A :attribute must have at least :min items.",
	),
	"not_in"               => "A selected :attribute is invalid.",
	"numeric"              => "A :attribute must be a number.",
	"regex"                => "A :attribute format is invalid.",
	"required"             => "A :attribute is required.",
	"required_if"          => "A :attribute is required when :other is :value.",
	"required_with"        => "A :attribute is required when :values is present.",
	"required_with_all"    => "A :attribute is required when :values is present.",
	"required_without"     => "A :attribute is required when :values is not present.",
	"required_without_all" => "A :attribute is required when none of :values are present.",
	"same"                 => "A :other and :attribute must match.",
	"size"                 => array(
		"numeric" => "A :attribute must be :size.",
		"file"    => "A :attribute must be :size kilobytes.",
		"string"  => "A :attribute must be :size characters.",
		"array"   => "A :attribute must contain :size items.",
	),
	"unique"               => "That :attribute has already been taken.",
	"mine_or_unique"         => "That :attribute has already been taken.",
	"url"                  => "That url is invalid.",
	"timezone"             => "A :attribute must be a valid zone.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => array(
		'attribute-name' => array(
			'rule-name' => 'custom-message',
		),
	),

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => array(),

);
