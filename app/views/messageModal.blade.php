<div class="modal-header">
    <h3 class="modal-title">Send @{{user.firstName}} a Message</h3>
</div>
<div class="modal-body">
    <form isoform="{{Isoform::getSeed('message')}}" ng-submit="submit($event)">
        Your Name
        <input class="form-control" name="name" ng-model="name" autofocus>
        {{getHtmlForIsoformMessages('name')}}
        <hr>
        Your Company
        <input class="form-control" name="company" ng-model="company">
        {{getHtmlForIsoformMessages('company')}}
        <hr>
        Your Email
        <input class="form-control" name="email" ng-model="email" placeholder="me@gmail.com">
        {{getHtmlForIsoformMessages('email')}}
        <hr>
        Maximum Hourly Rate
        <br><small class="text-muted">(This won't be made public or shared with @{{user.firstName}})</small>
        <div class="input-group">
          <span class="input-group-addon">$</span>
          <input name="hourlyMax" class="form-control" ng-model="hourlyMax">
          <span class="input-group-addon">.00</span>
        </div>
        {{getHtmlForIsoformMessages('hourlyMax')}}
        <hr>
        Additional Info
        <textarea class="form-control" name="info" ng-model="info" ></textarea>
        {{getHtmlForIsoformMessages('info')}}
    </form>
</div>
<div class="modal-footer">
    <button class="btn btn-warning btn-xs" ng-click="cancel()">Cancel</button>
    <button class="btn btn-primary btn-xs" ng-click="submit()">Submit</button>
</div>