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
	</style>
	<body>
		<?php
			$name = $em = "";
			$nameErr = $emailErr = "";
			
			if(isset($_POST['submit']))
				{					
					if(empty($_POST['fname']))
					{
						$nameErr = "Name is required";
					}
					else
					{
						$name = $_POST['fname'];
						if (!preg_match("/^[a-zA-Z ]*$/",$name)) 
						{
							$nameErr = "Only letters and white space allowed";					  
						}
					}
					if(empty($_POST['email']))
					{
						$emailErr = "Email is required";
					}
					else
					{
						$em = $_POST['email'];
						if (!filter_var($em, FILTER_VALIDATE_EMAIL)) 
						{
							$emailErr = "Invalid email format";					
						}
					}
					if(strlen($nameErr)==0 AND strlen($emailErr)==0)
					{
						$m_fname = $_POST['fname'];
						$email = $_POST['email'];
						$s_id = $_POST['schools_list'];
						if($s_id != 0)
						{
							$connection = new mysqli("localhost","root","","code_test_db") or die();
							$sqlcheck = "SELECT member_fname, s_id FROM Members WHERE s_id = \"{$s_id}\" AND member_fname =\"{$m_fname}\"";
							$result = mysqli_query($connection, $sqlcheck) or die();
							if ($result->num_rows>0) 
							{
								echo "<br/>"."*User is already associated with this school";
							} 
							else 
							{
								$sql = mysqli_query($connection, "INSERT INTO members VALUES ( NULL, \"{$m_fname}\", \"{$email}\" , \"{$s_id}\" )");
								echo "<br/>"."The insertion has been successfull";
							}
						}
						else
						{
							echo "<br/>". "Please select a school from the list!";
						}
					}
				}
		?>
		<div class="container">
		<form action = "index.php" method = "post">
			<h1>Associate a member with a school</h1>
			<p><span class="error">* required fields.</span></p>
			<table>
				<tr>
					<td><label>Full Name:</label></td>
					<td><input type="text" id="m_fname" name="fname"/></td>
					<td><span class="error">* <?php echo $nameErr;?></span></td>
				</tr>
				<tr>
					<td><label>Email:</label></td>
					<td><input type="text" id="m_email" name="email"/></td>
					<td><span class="error">* <?php echo $emailErr;?></span><td>
				</tr>
				<tr>
					<td><label>School:</label></td>
					<td><select name="schools_list">
							<option value="0">--Select School from the list--</option>
							<?php
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
	</body>
</html>