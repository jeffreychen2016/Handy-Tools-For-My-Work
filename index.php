<?php
include 'process.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
<body>
	<div id="main-container">
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#">Docs Storage</a>
				</div>
				<ul class="nav navbar-nav">
					<?php
						generatingDirectoriesInNav(rootDir);
					?>
				</ul>		
			</div>
		</nav>

		<div class="result">
			<!-- side bar secion -->
			<div class="sidebar">
				<ul>
					<li>
						<a href="#" class="sidebar-icon sidebar-icon-label" id="upload_btn"><span class="glyphicon glyphicon-upload sidebar-icon"></span>Upload</a>
					</li>
					<li>
						<a href="mailto:jchen@comdata.com" class="sidebar-icon sidebar-icon-label"><span class="glyphicon glyphicon-envelope sidebar-icon"></span>Email</a>
					</li>
					<li>
						<a href="http://localhost/Comdata_Prod/phpmyfaq/index.php" class="sidebar-icon sidebar-icon-label" target="_blank"><span class="glyphicon glyphicon-question-sign sidebar-icon"></span>F.A.Q</a>
					</li>
					<li>
						<a href="#" class="sidebar-icon sidebar-icon-label" id="SQL_convertor_btn"><span class="glyphicon glyphicon-wrench sidebar-icon"></span>SQL Convertor</a>
					</li>
					<!-- <li><a class="glyphicon glyphicon-envelope" href="mailto:jchen@comdata.com"></a></li> -->
				</ul>
			</div>
			<a class="btn-open-side-bar">
				<span class="glyphicon glyphicon-menu-hamburger"></span>
			</a>
			<!-- iframe section -->
 			<div id="iframe-container">

			</div>
			<!-- iframe section end -->
			<!-- SQL Convertor -->
			<div id="SQL_convertor_container">
				<textarea id="SQL_convertor_textarea"></textarea>
				<label>Batch Number: </label><input type="text" name="" id="input_batch_number">
				<label>Tenant ID: </label><input type="text" name="" id="input_tenant_id">
				<input type="submit" name="submit" value="Convert->SQLyog" id="convert-btn">
				<input type="submit" name="submit" value="Copy" id="copy-btn">
				<input type="submit" name="submit" value="Reverse->ET" id="reverse-btn">
			</div>
			<!-- SQL Convertor end -->
		</div>
		<div id="upload_window">
			<form action="process.php" method="post" enctype="multipart/form-data" id="file_upload_form">
				 <h1>Upload your file using this form:</h1>
				 <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
				 <p><input type="file" name="the_file" id="choose_file_btn"></p>
				 <label for="choose_file_btn" class="btn btn-info">Please choose a file to upload</label>
				 <span id="file_selected">No file selected</span>
				 <div id="categories">
					 <?php
						buildDomStringForDirectoryDropDown(rootDir);
					 ?>
				 </div>
				 <span id="directory_selected">No directory selected</span>
				 <p><input type="submit" name="submit_upload_file" value="Upload This File" id="upload_this_file"></p>
				 <?php 
						printUploadMessage();
				 ?>
			</form>
		</div>
		<div id="doc_manager_container">
			<h2 id="doc_manager_title">Doc Manager</h2>
			<div id="doc_manager_wrapper">
				<div id="directory_manager">
					<h3 class="sub_title">Directories</h3>
					<form action="process.php" method="post"  id="directory_manager_form">
						<?php
							listAllDirectories(rootDir);
						?>
						<input type="text" name="directory_manager_form" hidden>
					</form>
					<div class="doc_manager_btn_group">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_dir_modal">Add</button>
						<button type="button" class="btn btn-primary edit_dir_btn" data-toggle="modal" data-target="#edit_dir_modal" disabled>Edit</button>
						<button type="button" class="btn btn-primary delete_dir_btn" id="delete_directory_btn" disabled>Delete</button>
					</div>
				</div>
				
				<div id="arrow_sign_container">
						<img src="./imgs/right-arrow.png" alt="arrow-sign" id="arrow_sign">
				</div>

				<div id="file_manager">
					<h3 class="sub_title">Files</h3>
					<form action="process.php" method="post"  id="file_manager_form">
						<input type="text" name="file_manager_form" hidden>
					</form>
					<div class="doc_manager_btn_group">
						<a href="" id="download_file_link" download><button type="button" class="btn btn-primary download_file_btn" id="download_file_btn" disabled>Download</button></a>
						<button type="button" class="btn btn-primary edit_file_btn" data-toggle="modal" data-target="#edit_file_modal" disabled>Edit</button>
						<button type="button" class="btn btn-primary delete_file_btn" id="delete_file_btn" disabled>Delete</button>
					</div>
				</div>
			</div>
		</div>


		<div id="footer">
			
		</div>
		<!-- Create Directory Modal -->
		<div class="modal fade" id="add_dir_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<form action="process.php" method="post" id="create_directory_form">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Add Directory</h4>
						</div>
						<div class="modal-body">
							<label for="add_directory_name">Directory Name</label>
							<input type="text" id="add_directory_name" name="add_directory_name">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<input type="submit" class="btn btn-primary"  name="submit_create_directory" value="Add Directory">
							<!-- <button name="submit_create_directory" class="btn btn-primary" id="submit_file_name"></button> -->
						</div>
					</div>
				</div>
			</form>
		</div>
		<!-- Edit Directory Modal -->
		<div class="modal fade" id="edit_dir_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<form action="process.php" method="post" id="edit_directory_form">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Edit Directory</h4>
						</div>
						<div class="modal-body">
							<label>Original Name:</label>
							<label id='original_dir_name'></label>
							<label>=></label>
							<label for="edit_directory_name">New Name:</label>
							<input type="text" id="new_directory_name" name="edit_directory_name">
							<input type="text" id="selected_dir_for_renaming" name="selected_dir_for_renaming" hidden>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<input type="submit" class="btn btn-primary"  name="submit_edit_directory" value="Save Change">
						</div>
					</div>
				</div>
			</form>
		</div>
		<!-- Edit File Modal -->
		<div class="modal fade" id="edit_file_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<form action="process.php" method="post" id="edit_file_form">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Edit File</h4>
						</div>
						<div class="modal-body">
							<label>Original Name:</label>
							<label id='original_file_name'></label>
							<label>=></label>
							<label for="edit_file_name">New Name:</label>
							<input type="text" id="new_file_name" name="edit_file_name">
							<input type="text" id="selected_file_for_renaming" name="selected_file_for_renaming" hidden>
							<input type="text" id="selected_dir_name" name="selected_dir_name" hidden>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<input type="submit" class="btn btn-primary"  name="submit_edit_file" value="Save Change">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>


	<!--CSS,JQuery,JS -->
	<script
	  src="https://code.jquery.com/jquery-3.2.1.min.js"
	  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
	  crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- My CSS -->
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/js.js"></script>	  
</body>
</html>