<?PHP

function getHtmlForIsoformMessages($field){
 return '
 	<p class="text-danger" ng-repeat="message in isoform.messages.'.$field.'" ng-cloak>
		{{message}}
	</p>
 ';
}