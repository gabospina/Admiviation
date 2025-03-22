<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
  if(!isset($_SESSION["HeliUser"])){
    header("Location: index.php");
  }

  if($_SESSION["HeliUser"] != 12){
    header("Location: home.php");
  }
  $page = "admin";
  include_once "header.php";
  include_once "assets/php/db_connect.php";
?>
  <div class='inner-md'>
    <h2 class='page-header text-center'>Add to Blog</h2>
    <div class="col-md-4 center-block">
      <div class="lbl">Post Title</div>
      <input type="text" class="form-control" id="post-title" placeholder="Title of the post">
      <div class="lbl">Post Text</div>
      <textarea id="post-text" class="form-control" rowspan="35" style="height: 300px;" placeholder="Post Text"></textarea>
      <div class="lbl">Upload Image</div>
      <button class="btn btn-primary" data-toggle="modal" data-target="#uploadImageModal">Upload</button>
      <div class="lbl display-inline" id="uploadedImage"></div>
      <button class="btn btn-danger" id="removeImage">Remove Image</button>
      <div class="lbl">Link URL</div>
      <input type="text" class="form-control" id="link-url" placeholder="Article or website url"/>
      <button class="btn btn-success outer-top-xs form-control" id="submitPost">Submit</button>
    </div>
  </div>

  <div class="modal" id="uploadImageModal" tabindex="-1" role="dialog" aria-labelledby="personinfo" aria-hidden="true" >
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">Change Your Profile Picture</h3>
      </div>
      <div class="modal-body">
        <div class="col-md-12 no-float">
          <form class="dropzone no-float" id="uploadDocuments">
            <input type="hidden" name="pilot_id" id="picture-pilot-id"/>
            <p class="dz-message">Drag and Drop, or click, to upload files.</p>
          </form>
        </div>
      </div>
    </div>
    </div>
  </div>

<?php
  include_once "footer.php";
?>