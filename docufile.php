<?php
  error_reporting(-1);
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
  if(!isset($_SESSION["HeliUser"])){
    header("Location: index.php");
  }

  $page = "docufile";
  include_once "header.php";

?>
    <div class="light-bg" id="personalSection">
      <div class="inner-sm">    
        <div class="col-md-12 no-float">
          <h2 class="page-header">Documents <?php echo (intval($_SESSION["admin"]) > 0 ? '<button class="btn btn-lg btn-success pull-right" id="uploadModalBtn">Upload Files</button>' : '');?></h2>
          <div class="col-md-2 no-padding no-float outer-bottom-xxs">
            <div class="lbl">Select Category</div>
            <select class="form-control" id="documentCategories"></select>
          </div>
          <div id="document-list" class="list"></div>
          <div id="document-panel">
            <div id="document-title">Select a Document</div>
            <div id="document-menu">
              Filename: <span id="document-filename"></span>
              Creation Date: <span id="document-creation"></span>
              <button class="btn btn-primary" id="downloadDocument">Download</button>
              <button class="btn btn-primary" data-toggle="modal" data-target="#viewStatsModal">Viewed Info</button>
              <?php echo (intval($_SESSION["admin"]) > 0 ? '<button class="btn btn-danger" id="deleteDocument">Delete</button><button class="btn btn-primary" data-toggle="modal" data-target="#changeCategoryModal">Change Category</button>' : ''); ?>
            </div>
            <div id="document-content"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h1 class="modal-title">Upload Documents And Files</h1>
          </div>
          <div class="modal-body">
            <div class="no-float" style="width: 100%">
              <div class="col-md-5 outer-bottom-xxs">
                <div class="lbl">Select Document Category</div>
                <select class="form-control outer-bottom-xxs" id="modalCategories"></select>
                <input type="text" class="form-control" id="addCategoryInput" placeholder="Input Category Name"/>
              </div>
              <form action="php/upload_document.php" method="post" class="dropzone no-float" id="uploadDocuments">
                <input type="hidden" class="form-control col-md-5" id="categoryValue" name="category" />
                <p class="dz-message">Drag and Drop, or click, to upload files.</p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="changeCategoryModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h1 class="modal-title">Change Document's Category</h1>
          </div>
          <div class="modal-body">
            <div class="no-float" style="width: 100%">
              <div class="col-md-5 outer-bottom-xxs">
                <div class="lbl">Select Document Category</div>
                <select class="form-control outer-bottom-xxs" id="changeCategory"></select>
                <input type="text" class="form-control" id="changeCategoryInput" placeholder="Input Category Name"/>
              </div>
                <input type="hidden" class="form-control col-md-5" id="changeCategoryValue" />
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" id="changeCategorySave">Save</button>
            <button class="btn" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="viewStatsModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h1 class="modal-title">List of Document Views</h1>
          </div>
          <div class="modal-body">
            <div class="col-md-12 no-float">
              <div class="col-md-6">
                <h3>Document viewed by:</h3>
                <ul id="viewedList"></ul>
              </div>
              <div class="col-md-6">
                <h3>Document not viewed by:</h3>
                <ul id="notviewedList"></ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> 
    <a href="" download id="downloadLink"></a>
  <?php include_once "footer.php";?>