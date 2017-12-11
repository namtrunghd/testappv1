<!DOCTYPE html>
<html lang="en" ng-app="my-app">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Single Page Laravel - AngularJS</title>
  <link type="text/css" rel="stylesheet" href="<?php echo asset('frontend/css/bootstrap.css'); ?>" />
  <link type="text/css" rel="stylesheet" href="<?php echo asset('frontend/css/font-awesome.min.css'); ?>" />
  <link type="text/css" rel="stylesheet" href="<?php echo asset('frontend/css/bootstrap-theme.min.css'); ?>" />
  <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
</head>
<body>
  <center><h2>List of Members</h2></center>
  <div class="container" ng-controller="MembersController">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
        <tr>
          <th ng-click="sortData('id')">ID. <div ng-class="getSortClass('id')"></div></th>
          <th ng-click="sortData('name')">Name <div ng-class="getSortClass('name')"></div></th>
          <th ng-click="sortData('age')">Age <div ng-class="getSortClass('age')"></div></th>
          <th ng-click="sortData('address')">Address <div ng-class="getSortClass('address')"></div></th>
          <th>Photo</th>
          <th><a id="btn-add" class="btn btn-success btn-sm" ng-click="modal('add')">Add member</a></th>
        </tr>
        </thead>
        <tbody>
        <tr dir-paginate="mem in members | orderBy:sortColumn:reverse | itemsPerPage:5 ">
          <td >@{{mem.id}}</td>
          <td ><p class="name">@{{mem.name}}</p></td>
          <td >@{{mem.age}}</td>
          <td ><p class="address">@{{mem.address}}</p></td>
          <td width="20%"><img check-image class="img-responsive" ng-src="photo/@{{mem.photo || 'user-200.png'}}"></td>
          <td>
            <button class="btn btn-default btn-sm btn-info" id="btn-edit" ng-click="modal('edit',mem.id)"><span class="glyphicon glyphicon-pencil"></span></button>
            <button class="btn btn-danger btn-sm btn-delete" ng-click="confirmDelete(mem.id)"><span class="glyphicon glyphicon-trash"></span></button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <div class="col-xs-12 pull-right">
      <dir-pagination-controls
      max-size="5"
      direction-links="true"
      boundary-links="true" >
    </dir-pagination-controls></div>
    <!-- Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" ng-click="reset()" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@{{frmTitle}} </h4>
          </div>
          <div class="modal-body">
            <form name="frmMember">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" ng-model="member.name" ng-required="true" ng-maxlength="100" ng-pattern="/^[a-zA-Z\s]*$/"/>
                <div ng-show="frmMember.name.$dirty && frmMember.name.$invalid">
                  <p><span style="color: red" ng-show="frmMember.name.$error.required">Please enter Name</span></p>
                <p><span style="color: red" ng-show="frmMember.name.$error.pattern">The Name must be Alphabetic</span></p>
                <p><span style="color: red" ng-show="frmMember.name.$error.maxlength">The Name may not be greater than 100 characters.</span></p></div>
              </div>
              <div class="form-group">
               <label for="name">Age:</label>
               <input type="text" class="form-control" id="age" name="age" ng-model="member.age" ng-required="true" ng-maxlength="2" ng-pattern="/^-?[0-9][^\.]*$/"/>
               <div ng-show="frmMember.age.$dirty && frmMember.age.$invalid">
                <p><span style="color: red" ng-show="frmMember.age.$error.required">Please enter Age</span></p>
               <p><span style="color: red" ng-show="frmMember.age.$error.pattern">The Age must be number</span></p>
               <p><span style="color: red" ng-show="frmMember.age.$error.maxlength">Maximum 2 characters</span></p>
               </div>
               
             </div>
             <div class="form-group">
              <label for="name">Address:</label>
              <textarea rows="2" class="form-control" id="address" name="address" ng-model="member.address" ng-required="true" ng-maxlength="300"></textarea>
              <div ng-show="frmMember.address.$dirty && frmMember.address.$invalid">
                <p><span style="color: red" ng-show="frmMember.address.$error.required">Please enter Address</span></p>
              <p><span style="color: red" ng-show="frmMember.address.$error.maxlength">The Address may not be greater than 300 characters.</span></p>
              </div>
              
            </div>
            <div class="form-group">
              <label for="photo">Photo</label>
              <input type="file" ng-click="clear()" name="photo" file-model="myFile" ngf-model-invalid="errorFile" ngf-max-size="10MB" ngf-pattern="'.jpg,.png,.gif'"
              ngf-accept="'.jpg,.png,.gif'"  ngf-select ng-model="files" id="upload" ngf-change="upload($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"/>
              <p><span style="color: red" ng-show="frmMember.photo.$error.pattern">Photo only allow JPG, GIF, and PNG filetypes.</span></p>
              <p><span style="color: red" ng-show="frmMember.photo.$error.maxSize">The photo may not be greater than 10MB.</span></p>
              <p style="color:red"><span id="error"></span></p>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="submit" class="btn btn-success" ng-disabled="frmMember.$invalid" ng-click="save(state,id)">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<!-- <script>
  $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
      });
</script> -->
<script type="text/javascript" src="<?php echo asset('frontend/js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('frontend/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('app/lib/angular.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('app/app.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('app/controllers/MembersController.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('app/lib/ng-file-upload.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('app/lib/ng-file-upload-all.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('app/lib/ng-file-upload-shim.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('app/lib/dirPagination.js'); ?>"></script>

</body>
</html>

