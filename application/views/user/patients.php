<?php include('themes/templates/includes/user-header.php'); ?>
<section id="content">
			<?php include('themes/templates/includes/user-sidebar.php'); ?>
			<section class="area">
				<hgroup id="area-header">
					<ul class="page-title">
						<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
						<li><h1>Patients List</h1></li>
					</ul>
					
					<ul id="filter-search">
						<li>
							<select name="filter" class="select">
								<option value="option 1">10</option>
								<option value="option 2">20</option>
								<option value="option 3">30</option>
							</select>
						</li>
						<li>items per page</li>
					</ul>
					
					<ul id="text-search">
						<li><input type="text" name="Name" class="textbox" value="Filter Information"></li>
					</ul>
					<div class="clear"></div>
				</hgroup>
				
				<button class="button01" onClick="window.location.href='<?php echo url('rpcuser/add_patient'); ?>';">+ Add New Patient</button>
				
				<table class="table">
						<th>Patient ID</th>
						<th>Patient Name</th>
						<th>Initial Appointment Date</th>
						<th>Gender</th>
						<th>Date of Birth</th>
						<th>Place of Birth</th>
						<th>Status</th>
							
						<tr>
							<td>JB-034538-C8</td>
							<td><a href="view-patient.html">Victor Inigo A. Garcia</a></td>
							<td>October 25, 2013</td>
							<td>Male</td>
							<td>July 25, 1978</td>
							<td>Pasig</td>
							<td>Active</td>
						</tr>
						
						<tr>
							<td>JB-978645-C7</td>
							<td><a href="#">Ramon Vincent A. Garcia</a></td>
							<td>August 14, 2013</td>
							<td>Male</td>
							<td>September 11, 1976</td>
							<td>Pasig</td>
							<td>Active</td>
						</tr>
						
						<tr>
							<td>JB-111347-D1</td>
							<td><a href="#">Sarah Jane O. Lampa</a></td>
							<td>June 2, 2013</td>
							<td>Female</td>
							<td>January 2, 1983</td>
							<td>Paranaque</td>
							<td>Active</td>
						</tr>
						
						<tr>
							<td>JB-091888-A8</td>
							<td><a href="#">Pep David</a></td>
							<td>February 29, 2013</td>
							<td>Male</td>
							<td>October 6, 1966</td>
							<td>South Africa</td>
							<td>Discontinued</td>
						</tr>
						
						<tr>
							<td>AG-034538-P9</td>
							<td><a href="#">Rico Martin G. Bo-ot</a></td>
							<td>April 10, 2013</td>
							<td>Male</td>
							<td>May 14, 1985</td>
							<td>Guam</td>
							<td>Active</td>
						</tr>
						
						<tr>
							<td>JB-034538-C8</td>
							<td><a href="#">Victor Inigo A. Garcia</a></td>
							<td>October 25, 2013</td>
							<td>Male</td>
							<td>July 25, 1978</td>
							<td>Angeles</td>
							<td>Active</td>
						</tr>
						
						<tr>
							<td>JB-034538-C8</td>
							<td><a href="#">Emmanuel C. Canonigo</a></td>
							<td>October 25, 2013</td>
							<td>Male</td>
							<td>July 25, 1978</td>
							<td>General Santos</td>
							<td>Dicontinued</td>
						</tr>
						
						<tr>
							<td>XY-768402-Q10</td>
							<td><a href="#">Jose Alphonso N. Ramirez </a></td>
							<td>May 2, 2013</td>
							<td>Male</td>
							<td>November 01, 1980</td>
							<td>Pasig</td>
							<td>Active</td>
						</tr>
						
						<tr>
							<td>LG-245458-G4</td>
							<td><a href="#"> Claire M. Garcia</a></td>
							<td>September 21, 2013</td>
							<td>Female</td>
							<td>July 25, 1978</td>
							<td>Quezon</td>
							<td>Active</td>
						</tr>
						
						<tr>
							<td>RE-239871-OP</td>
							<td><a href="#">Maria Mia Domingo</a></td>
							<td>December 6, 2013</td>
							<td>Male</td>
							<td>October 30, 1988</td>
							<td>Hong kong</td>
							<td>Renewed</td>
						</tr>
						
					</table>
					
					<button class="button02" onClick="window.location.href='add-patient.html';">Add New Patient</button>
			</section>
		<section class="clear"></section>
		</section>
<?php include('themes/templates/footer/user-footer.php'); ?>