<?php include('themes/templates/includes/user-add-patient-header.php'); ?>
<section id="content">
			<?php include('themes/templates/includes/user-sidebar.php'); ?>
			<section class="area">
			<hgroup id="area-header">
				<ul class="page-title">
					<li><img src="<?php echo BASE_FOLDER; ?>themes/images/header-user.png"></li>
					<li><h1>Add Patient Information</h1></li>
				</ul>
				
				<ul id="controls">
					<li><a href="#"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_save.png"></a></li>
					<li style="margin: 0; padding: 0; text-align: center;"><img src="<?php echo BASE_FOLDER; ?>themes/images/dot.png" ></li>
					<li><a href="<?php echo url('rpcuser/patient'); ?>"><img class="icon" src="<?php echo BASE_FOLDER; ?>themes/images/icon_cancel.png"></a></li>
				</ul>
				<div class="clear"></div>
			</hgroup>
				<section id="left">
						<form>
							<ul id="form">
								<li>Initial Appointment Date</li>
								<li>
									<select name="month" class="select" style="width: 132px;">
										<option value="Option 2">Jan</option>
										<option value="Option 3">Feb</option>
										<option value="Option 4">Mar</option>
										<option value="Option 5">Apr</option>
										<option value="Option 6">May</option>
										<option value="Option 7">June</option>
										<option value="Option 8">July</option>
										<option value="Option 9">Aug</option>
										<option value="Option 10">Sept</option>
										<option value="Option 11">Oct</option>
										<option value="Option 12">Nov</option>
										<option value="Option 13">Dec</option>
									</select>
								</li>
								<li>
									<select name="Day" class="select" style="width: 64px;">
										<option value="Option 1">01</option>
										<option value="Option 2">02</option>
										<option value="Option 3">03</option>
										<option value="Option 4">04</option>
										<option value="Option 5">05</option>
										<option value="Option 6">06</option>
										<option value="Option 7">07</option>
										<option value="Option 8">08</option>
										<option value="Option 9">09</option>
										<option value="Option 10">10</option>
										<option value="Option 11">11</option>
										<option value="Option 12">12</option>
										<option value="Option 13">13</option>
										<option value="Option 14">14</option>
										<option value="Option 15">15</option>
										<option value="Option 16">16</option>
										<option value="Option 17">17</option>
										<option value="Option 18">18</option>
										<option value="Option 19">19</option>
										<option value="Option 20">20</option>
										<option value="Option 21">21</option>
										<option value="Option 22">22</option>
										<option value="Option 23">23</option>
										<option value="Option 24">24</option>
										<option value="Option 25">25</option>
										<option value="Option 26">26</option>
										<option value="Option 27">27</option>
										<option value="Option 28">28</option>
										<option value="Option 29">29</option>
										<option value="Option 30">30</option>
										<option value="Option 31">31</option>
									</select>
								</li>
								<li>
									<select name="Year" class="select" style="width: 74px;">
										<option value="Option 1">2013</option>
										<option value="Option 1">2012</option>
										<option value="Option 1">2011</option>
										<option value="Option 1">2010</option>
										<option value="Option 1">2009</option>
										<option value="Option 1">2008</option>
										<option value="Option 1">2007</option>
										<option value="Option 1">2006</option>
										<option value="Option 1">2005</option>
										<option value="Option 1">2004</option>
										<option value="Option 1">2003</option>
										<option value="Option 1">2001</option>
										<option value="Option 1">2000</option>
										<option value="Option 1">1999</option>
										<option value="Option 1">1998</option>
										<option value="Option 1">1997</option>
										<option value="Option 1">1996</option>
										<option value="Option 1">1995</option>
										<option value="Option 1">1994</option>
										<option value="Option 1">1993</option>
										<option value="Option 1">1992</option>
									</select>
								</li>
							</ul>
						</form>
						<section class="clear"></section>
						<form>
								<ul id="form02">
									<li>Patient Code</li>
									<li><input type="text" name="Name" class="textbox"  value="JB-034538-C8"></li>
								</ul>
						</form>	
						<section class="clear"></section>
						<form>
								<ul id="form02">
									<li>Patient Name<span>*</span></li>
									<li><input type="text" name="Name" class="textbox"></li>
								</ul>
						</form>	
						<section class="clear"></section>
						<form>
								<ul id="form">
									<li>Gender<span>*</span></li>
									<li>
										<input type="radio" name="gender" value="Male"><label for="r1"><span></span>Male</label>
										<input type="radio" name="gender" value="Female"> <label for="r2"><span></span>Female</label>
									</li>
								</ul>
						</form>
						<section class="clear">	</section>
						<form>
							<ul id="form">
								<li>Date of Birth<span>*</span></li>
								<li>
									<select name="month" class="select" style="width: 132px;">
										<option value="Option 2">Jan</option>
										<option value="Option 3">Feb</option>
										<option value="Option 4">Mar</option>
										<option value="Option 5">Apr</option>
										<option value="Option 6">May</option>
										<option value="Option 7">June</option>
										<option value="Option 8">July</option>
										<option value="Option 9">Aug</option>
										<option value="Option 10">Sept</option>
										<option value="Option 11">Oct</option>
										<option value="Option 12">Nov</option>
										<option value="Option 13">Dec</option>
									</select>
								</li>
								<li>
									<select name="Day" class="select" style="width: 64px;">
										<option value="Option 1">01</option>
										<option value="Option 2">02</option>
										<option value="Option 3">03</option>
										<option value="Option 4">04</option>
										<option value="Option 5">05</option>
										<option value="Option 6">06</option>
										<option value="Option 7">07</option>
										<option value="Option 8">08</option>
										<option value="Option 9">09</option>
										<option value="Option 10">10</option>
										<option value="Option 11">11</option>
										<option value="Option 12">12</option>
										<option value="Option 13">13</option>
										<option value="Option 14">14</option>
										<option value="Option 15">15</option>
										<option value="Option 16">16</option>
										<option value="Option 17">17</option>
										<option value="Option 18">18</option>
										<option value="Option 19">19</option>
										<option value="Option 20">20</option>
										<option value="Option 21">21</option>
										<option value="Option 22">22</option>
										<option value="Option 23">23</option>
										<option value="Option 24">24</option>
										<option value="Option 25">25</option>
										<option value="Option 26">26</option>
										<option value="Option 27">27</option>
										<option value="Option 28">28</option>
										<option value="Option 29">29</option>
										<option value="Option 30">30</option>
										<option value="Option 31">31</option>
									</select>
								</li>
								<li>
									<select name="Year" class="select" style="width: 74px;">
										<option value="Option 1">2013</option>
										<option value="Option 1">2012</option>
										<option value="Option 1">2011</option>
										<option value="Option 1">2010</option>
										<option value="Option 1">2009</option>
										<option value="Option 1">2008</option>
										<option value="Option 1">2007</option>
										<option value="Option 1">2006</option>
										<option value="Option 1">2005</option>
										<option value="Option 1">2004</option>
										<option value="Option 1">2003</option>
										<option value="Option 1">2001</option>
										<option value="Option 1">2000</option>
										<option value="Option 1">1999</option>
										<option value="Option 1">1998</option>
										<option value="Option 1">1997</option>
										<option value="Option 1">1996</option>
										<option value="Option 1">1995</option>
										<option value="Option 1">1994</option>
										<option value="Option 1">1993</option>
										<option value="Option 1">1992</option>
									</select>
								</li>
							</ul>
						</form>
						<section class="clear"></section>
						<form>
							<ul id="form">
								<li>Age<span>*</span></li>
								<li><input type="text" name="Age" class="textbox" style="width: 127px;"></li>
							</ul>
						</form>	
						<section class="clear"></section>
						<form>
							<ul id="form02">
								<li>Place of Birth</li>
								<li>
									<input type="text" name="Address" class="textbox">
									<input type="text" name="Address" class="textbox">
								</li>
							</ul>
							
							<ul id="form-address">
								<li><li>
								<li><input type="text" name="Address" class="textbox" value="City"></li>
								<li>
									<select name="state" class="select">
										<option value="Option 1">State</option>
										<option value="Option 2">Option 1</option>
										<option value="Option 3">Option 2</option>
										<option value="Option 4">Option 3</option>
									</select>
								</li>
								<li><input type="text" name="Address" class="textbox" value="Zip"></li>
							</ul>
						</form>
						<section class="clear"></section>
						<form>
							<ul id="form02">
								<li>Contact Information<span>*</span></li>
							</ul>
							<section class="clear"></section>
							<ul id="contact">
								<li>
									<select name="contact" class="select02">
										<option value="Option 1">Mobile</option>
										<option value="Option 2">Work</option>
										<option value="Option 3">Home</option>
										<option value="Option 4">Fax</option>
									</select>
								</li>
								<li><input type="text" name="contact" class="textbox"></li>
								<li><button>+Add Number</button></li>
							</ul>
							<section class="clear"></section>
							<ul id="form">
								<li>Email Address<span>*</span></li>
								<li><input type="text" name="email" class="textbox" ></li>
							</ul>
						</form>				
				</section>	
				
				<section id="right">
						<form>
							<ul id="form">
								<li>Upload Patient Photo</li>
								<li><img src="<?php echo BASE_FOLDER; ?>themes/images/photo.png"></li>
								<li>
								<input type="file" name="" class="textbox02"><br>
								<div>*File should not be larger than 25 MB</div>
								</li>
							</ul>
						</form>
						<section class="clear"></section>
						<form>
							<ul id="form">
								<li>Civil Status</li>
								<li>
									<select name="status" class="select" style="width: 132px;">
										<option value="Option 1">Single</option>
										<option value="Option 2">Married</option>
										<option value="Option 3">Widowed</option>
										<option value="Option 4">Seperated</option>
									</select>
								</li>
							</ul>
						</form>
						<section class="clear"></section>
						<form>
							<ul id="form">
								<li>Dominant Hand Used</li>
								<li>
									<select name="hand" class="select" style="width: 132px;">
										<option value="Option 1">Left</option>
										<option value="Option 2">Right</option>
									</select>
								</li>
							</ul>
						</form>
						<section class="clear"></section>
						<form>
							<ul id="form">
								<li>Work Status</li>
								<li>
									<select name="work" class="select" style="width: 132px;">
										<option value="Option 1">Full Time</option>
								<option value="Option 2">Part Time</option>
								<option value="Option 3">Student</option>
								<option value="Option 4">Self Employed</option>
									</select>
								</li>
							</ul>
						</form>
						<section class="clear"></section>
				</section>	
					
				<div class="line02"></div>
				
				<section id="left">	
					
						<p class="emergency"><img src="<?php echo BASE_FOLDER; ?>themes/images/emergency.png"><span>In Case of Emergency, who do we contact?</span></p>
						<section class="clear"></section>
						<form>
							<ul id="form02">
								<li>Contact Name</li>
								<li><input type="text" name="Name" class="textbox"></li>
							</ul>
						</form>	
						<section class="clear"></section>
						<form>
							<ul id="form02">
								<li>Contact Information<span>*</span></li>
							</ul>
							<section class="clear"></section>
							<ul id="contact">
								<li>
									<select name="contact" class="select02">
										<option value="Option 1">Mobile</option>
										<option value="Option 2">Work</option>
										<option value="Option 3">Home</option>
										<option value="Option 4">Fax</option>
									</select>
								</li>
								<li><input type="text" name="contact" class="textbox"></li>
								<li><button>+Add Number</button></li>
							</ul>
							<section class="clear"></section>
							<ul id="form">
								<li>Email Address<span>*</span></li>
								<li><input type="text" name="email" class="textbox" ></li>
							</ul>
						</form>	
				</section>
				
		<section class="clear"></section>
				<section id="buttons">
					<button class="form_button" onClick="window.location.href='#';">Save & Continue</button>
					<button class="form_button" onClick="window.location.href='#';">Cancel</button>
				</section>			
		</section>
	<section class="clear"></section>
	</section>
<?php include('themes/templates/footer/user-footer.php'); ?>