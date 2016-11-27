<html>
	<header>
		<title>Code Test</title>
	</header>
	<style type="text/css">
		body {
			background-color: #f2f2f2 !important;
		}
		.error {
			color : #FF0000;
			font-size: 12px;
		}
		.container{
			padding-bottom:50px;
			padding-left:350px;
			padding-right:350px;
		}
		.header {
			width:180px;
		}
	</style>
	<body>
		<?php
			$name = $email = "";
			$nameErr = $emailErr = "";
			
			if(isset($_POST['submit']))
				{	
					//Check if the name textfield is empty and if so, notify the user with appropriate message.
					if(empty($_POST['m_fname']))		
					{
						$nameErr = "Name is required";
					}
					else
					{
						//Check if the name is consisted by letters and white spaces only. Notify the user if the name does not follow the requirements.
						$name = $_POST['m_fname'];		
						if (!preg_match("/^[a-zA-Z ]*$/",$name)) 
						{
							$nameErr = "Only letters and white space allowed";					  
						}
					}
					//Check if the email textfield is empty and if so, notify the user with appropriate message.
					if(empty($_POST['m_email']))		
					{
						$emailErr = "Email is required";
					}
					else
					{
						//Validate email address format.
						$email = $_POST['m_email'];		
						if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
						{
							$emailErr = "Invalid email format";					
						}
					}
					//If there is not any of the above presented error cases, we proceed to the next step by getting the values for name, email and school.
					if(strlen($nameErr)==0 AND strlen($emailErr)==0)	
					{
						$m_fname = $_POST['m_fname'];
						$email = $_POST['m_email'];
						$s_id = $_POST['schools_list'];
						//Value 'zero' from dropdown list is the "--Select school from the list--, so this choice should be excluded"
						if($s_id != 0)					
						{
							//Establish a new connection with the database
							$connection = new mysqli("localhost","root","","code_test_db") or die();	
							$sqlcheck = "SELECT member_fname, s_id FROM Members WHERE s_id = \"{$s_id}\" AND member_fname =\"{$m_fname}\"";	//Checking whether there is already an association between Member and School.
							$result = mysqli_query($connection, $sqlcheck) or die();
							//If the association already exists, raise an appropriate error message.
							if ($result->num_rows>0) 
							{
								echo "<br/>"."*User is already associated with this school";
							} 
							//If there is not any association then insert a new entry to the database to create the association.
							else 					
							{
								$sql = mysqli_query($connection, "INSERT INTO members VALUES ( NULL, \"{$m_fname}\", \"{$email}\" , \"{$s_id}\" )");
								echo "<br/>"."The insertion has been successful!";
							}
						}
						//Raise an appropriate error if the user does not choose any school.
						else						
						{
							echo "<br/>". "Please select a school from the list!";
						}
					}
				}
		?>
		<div class="container">
			<div class="header">
				<img src="https://pbs.twimg.com/profile_images/781409516919353344/xzJO7MDu.jpg" alt="Toucan Tech Logo" style="width:100px;height:100px;">
				<h3>Happy Communities</h3>
			</div>
			<div class="content">
				<form action = "index.php" method = "post">
					<h1>Associate a member with a school</h1>
					<p><span class="error">* required fields.</span></p>
					<table>
						<tr>
							<td><label>Full Name:</label></td>
							<td><input type="text" name="m_fname"/></td>
							<td><span class="error">* <?php echo $nameErr;?></span></td>
						</tr>
						<tr>
							<td><label>Email:</label></td>
							<td><input type="text" name="m_email"/></td>
							<td><span class="error">* <?php echo $emailErr;?></span><td>
						</tr>
						<tr>
							<td><label>School:</label></td>
							<td><select name="schools_list">
									<option value="0">--Select School from the list--</option>
									<?php 
									//Populate the dropdown list from table schools from the database.
										$connection = new mysqli("localhost","root","","code_test_db") or die();
										$sql = mysqli_query($connection, "SELECT s_id, school_name FROM schools");
										while ($row = $sql->fetch_assoc())
										{
											echo "<option value=" .$row['s_id']. ">" . $row['school_name'] . "</option>";
										}
									?>
								</select></td>
						</tr>
						<tr>
							<td><input type="submit" name="submit"/></td>
						</tr>
					</table>
					
					<br/>
					<br/>
					<br/>
					<h2>Display Members from a school</h2>
					<label>Choose a school from the list:</label> 
						<select name="schools_list2">
							<option value="0">--Select School from the list--</option>
						<?php
						//Populating the dropdown list from table schools from the database.
							$connection = new mysqli("localhost","root","","code_test_db") or die();
							$sql = mysqli_query($connection, "SELECT s_id, school_name FROM schools");
							while ($row = $sql->fetch_assoc())
							{
								echo "<option value=" .$row['s_id']. ">" . $row['school_name'] . "</option>";
							}
						?>
					</select><br/><br/>
					<input type="submit" name="submit2"/><br/><br/>
								
					<?php
						if(isset($_POST['submit2']))
						{
							unset($s_id);
							$s_id = $_POST['schools_list2'];
							if($s_id != 0)
							{
								$connection = new mysqli("localhost","root","","code_test_db") or die();
								$sql = mysqli_query($connection, "SELECT member_fname FROM Members WHERE s_id = \"{$s_id}\" ORDER BY  member_fname ASC");
								while ($row = $sql->fetch_assoc())
								{
									echo "<label>".$row['member_fname']."</label><br/>";
								}
							}
							else
							{
								echo "<br/>". "Please select a school from the list!";
							}
						}	
					?>
				</form>
			</div>
		</div>
	</body>
</html>