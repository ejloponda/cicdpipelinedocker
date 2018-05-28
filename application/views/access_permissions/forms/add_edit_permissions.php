<section class="area">
	<hgroup id="area-header">
		<ul class="page-title">
			<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-role-type.png"></li>
			<li><h1>Add Role Types</h1></li>
		</ul>
		
		<ul id="controls">
			<li><img class="icon" onClick="$('#form-user-roles').submit();" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></li>
			<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
			<li class="firm_admin_roles"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></li>
		</ul>
		<div class="clear"></div>
	</hgroup>

	<h3>Update User Access (By User Type)</h3>

	<select>
		<?php foreach($user_types as $key=>$value): ?>
			<option value="<?php echo $value['id'] ?>"><?php echo $value['user_type'] ?></option>
		<?php endforeach; ?>
	</select>

	<table class="datatable">
		<thead>
			<th width="15%">Scope</th>
			<th align="middle" width="15%">Module</th>
			<th style="text-align:center !important;" width="15%">Can Add</th>
			<th style="text-align:center !important;" width="15%">Can Edit</th>
			<th style="text-align:center !important;" width="15%">Can Delete</th>
			<th style="text-align:center !important;" width="15%">Can View</th>
		</thead>
		<tbody>
			<?php foreach($user_module_list as $key=>$value): ?>
				<tr>
					<td width="15%"><?php echo $value['scope']; ?></td>
					<td width="15%"><?php echo $value['module']; ?></td>
					<td align="middle" width="15%"> <input type="checkbox"></td>
					<td align="middle" width="15%"> <input type="checkbox"></td>
					<td align="middle" width="15%"> <input type="checkbox"></td>
					<td align="middle" width="15%"> <input type="checkbox"></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<br/><br/>
<!-- 
	<h3>Update User Permission (By User)</h3>

	<script>
		$(function() {
			var opts=$("#user_source").html(), opts2="<option></option>"+opts;
		    $("#user_list").each(function() { var e=$(this); e.html(e.hasClass("placeholder")?opts2:opts); });
		    $("#user_list").select2({allowClear: true});
		});
	</script>


	<select id="user_list" name="user_list" class="populate" style="width:400px;"></select>


	<select id="user_source" style="display:none">
	  <?php foreach($users as $key=>$value): ?>
	    <option value="<?php echo $value['id']; ?>"><?php echo $value['firstname'] . " " . $value['lastname']; ?></option>
	  <?php endforeach; ?>
	</select>

	<br/>

	<table class="datatable">
		<thead>
			<th width="15%">Scope</th>
			<th align="middle" width="15%">Module</th>
			<th style="text-align:center !important;" width="15%">Can Add</th>
			<th style="text-align:center !important;" width="15%">Can Edit</th>
			<th style="text-align:center !important;" width="15%">Can Delete</th>
			<th style="text-align:center !important;" width="15%">Can View</th>
		</thead>
		<tbody>
			<?php foreach($user_module_list as $key=>$value): ?>
				<tr>
					<td width="15%"><?php echo $value['scope']; ?></td>
					<td width="15%"><?php echo $value['module']; ?></td>
					<td align="middle" width="15%"> <input type="checkbox"></td>
					<td align="middle" width="15%"> <input type="checkbox"></td>
					<td align="middle" width="15%"> <input type="checkbox"></td>
					<td align="middle" width="15%"> <input type="checkbox"></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<br/><br/> -->

	<section id="buttons">
			<button class="form_button" onClick="$('#form-user-roles').submit();">Save</button>
			<button class="form_button firm_admin_roles">Cancel</button>
		</section>			
</section>
	
<section class="clear"></section>