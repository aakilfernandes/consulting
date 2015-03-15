<div class="modal-header">
    <h3 class="modal-title" ng-if="!isEditing">Add a Tech/Skill</h3>
    <h3 class="modal-title" ng-if="isEditing">Edit @{{skill.name}}</h3>
</div>
<div class="modal-body">
    <form isoform="{{Isoform::getSeed('skill')}}" ng-submit="submit($event)">
        <input class="form-control" name="name" ng-model="name" list="skillsList" placeholder="javascript" autofocus autocomplete="off">
        <datalist id="skillsList">
        	@foreach($skills as $skill)
        		<option value="{{$skill->name}}">
        	@endforeach
        </datalist>
        {{getHtmlForIsoformMessages('name')}}
        <hr>
        <table><tr>
        	<td style="width:100px;">
        		Skill Level
        	</td>
        	<td>
        		<select class="form-control" name="level" ng-model="level" ng-options="level for level in _.range(frontloaded.constants.levels.min,frontloaded.constants.levels.max+1)"></select>
        	</td>
        </tr>
        <tr>
        	<td></td><td>{{getHtmlForIsoformMessages('level')}}</td>
        </tr>
        </table>
    </form>
</div>
<div class="modal-footer">
    <button class="btn btn-warning btn-xs" ng-click="cancel()">Cancel</button>
    <button class="btn btn-primary btn-xs" ng-click="submit()">Submit</button>
</div>