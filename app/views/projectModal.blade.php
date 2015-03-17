<div class="modal-header">
    <h3 class="modal-title">Add a Project</h3>
</div>
<div class="modal-body">
    <form isoform="{{Isoform::getSeed('project')}}" ng-submit="submit($event)">
        Project Name
        <input class="form-control" name="name" ng-model="name" placeholder="Name of Your Project" autofocus>
        {{getHtmlForIsoformMessages('name')}}
        <hr>
        Your Role
        <input class="form-control" name="role" ng-model="role" placeholder="Lead Developer">
        {{getHtmlForIsoformMessages('role')}}
        <hr>
        URL <small class="text-muted">(optional)</small>
        <input class="form-control" name="url" ng-model="url" list="skillsList" placeholder="http://yourproject.com">
        {{getHtmlForIsoformMessages('url')}}
        <hr>
        Blurb <small class="text-muted">(optional)</small>
        <textarea class="form-control" name="blurb" ng-model="blurb" placeholder="About your role">
        </textarea>
        {{getHtmlForIsoformMessages('blurb')}}
        </table>
    </form>
</div>
<div class="modal-footer">
    <button class="btn btn-warning btn-xs" ng-click="cancel()">Cancel</button>
    <button class="btn btn-primary btn-xs" ng-click="submit()">Submit</button>
</div>