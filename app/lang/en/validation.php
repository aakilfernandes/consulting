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

	"accepted"             => "Your :attribute must be accepted.",
	"active_url"           => "Your :attribute is not a valid URL.",
	"after"                => "Your :attribute must be a date after :date.",
	"alpha"                => "Your :attribute may only contain letters.",
	"alpha_dash"           => "Your :attribute may only contain letters, numbers, and dashes.",
	"alpha_num"            => "Your :attribute may only contain letters and numbers.",
	"array"                => "Your :attribute must be an array.",
	"before"               => "Your :attribute must be a date before :date.",
	"between"              => array(
		"numeric" => "Your :attribute must be between :min and :max.",
		"file"    => "Your :attribute must be between :min and :max kilobytes.",
		"string"  => "Your :attribute must be between :min and :max characters.",
		"array"   => "Your :attribute must have between :min and :max items.",
	),
	"boolean"              => "Your :attribute must be true or false.",
	"confirmed"            => "Your :attribute confirmation does not match.",
	"date"                 => "Your :attribute is not a valid date.",
	"date_format"          => "Your :attribute does not match the format :format.",
	"different"            => "Your :attribute and :other must be different.",
	"digits"               => "Your :attribute must be :digits digits.",
	"digits_between"       => "Your :attribute must be between :min and :max digits.",
	"email"                => "Your :attribute must be a valid email address.",
	"exists"               => "Email has not been registered yet.",
	"image"                => "Your :attribute must be an image.",
	"in"                   => "Your selected :attribute is invalid.",
	"integer"              => "Your :attribute must be an integer.",
	"ip"                   => "Your :attribute must be a valid IP address.",
	"max"                  => array(
		"numeric" => "Your :attribute may not be greater than :max.",
		"file"    => "Your :attribute may not be greater than :max kilobytes.",
		"string"  => "Your :attribute may not be greater than :max characters.",
		"array"   => "Your :attribute may not have more than :max items.",
	),
	"mimes"                => "Your :attribute must be a file of type: :values.",
	"min"                  => array(
		"numeric" => "Your :attribute must be at least :min.",
		"file"    => "Your :attribute must be at least :min kilobytes.",
		"string"  => "Your :attribute must be at least :min characters.",
		"array"   => "Your :attribute must have at least :min items.",
	),
	"not_in"               => "Your selected :attribute is invalid.",
	"numeric"              => "Your :attribute must be a number.",
	"regex"                => "Your :attribute format is invalid.",
	"required"             => "Your :attribute is required.",
	"required_if"          => "Your :attribute is required when :other is :value.",
	"required_with"        => "Your :attribute is required when :values is present.",
	"required_with_all"    => "Your :attribute is required when :values is present.",
	"required_without"     => "Your :attribute is required when :values is not present.",
	"required_without_all" => "Your :attribute is required when none of :values are present.",
	"same"                 => "Your :other and :attribute must match.",
	"size"                 => array(
		"numeric" => "Your :attribute must be :size.",
		"file"    => "Your :attribute must be :size kilobytes.",
		"string"  => "Your :attribute must be :size characters.",
		"array"   => "Your :attribute must contain :size items.",
	),
	"unique"               => "That :attribute has already been taken.",
	"mine_or_unique"         => "That :attribute has already been taken.",
	"url"                  => "Your :attribute format is invalid.",
	"timezone"             => "Your :attribute must be a valid zone.",

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
