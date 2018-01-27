<?php
include('config.php')
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
         <link rel="stylesheet" type="text/css" href="main.css">
        <title>Members Area</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Members Area" /></a>
	    </div>
        <div class="content">
<?php
//We display a welcome message, if the user is logged, we display it username
?>
Hello<?php if(isset($_SESSION['username'])){echo ' '.htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8');} ?>,<br />
Welcome Sign Up to List your Task!<br />

<?php
//If the user is logged, we display links to edit his infos, to see his pms and to log out
if(isset($_SESSION['username']))
{
//We count the number of new messages the user has
$nb_new_pm = mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"'));
//The number of new messages is in the variable $nb_new_pm
$nb_new_pm = $nb_new_pm['nb_new_pm'];
//We display the links
?>
            
<a href="edit_infos.php">Edit my personnal informations</a><br />
<a href="list_pm.php">My personnal messages(<?php echo $nb_new_pm; ?> unread)</a><br />
You can <a href="users.php">see the list of users</a>.<br /><br />
<a href="connexion.php">Logout</a>
<!--            input form for todo list-->
            <form method="post" action="index.php" class="input_form">
        <?php if (isset($errors)){?>
        <p><?php echo $errors; ?></p>
        
        <?php } ?>
		<input type="text" name="task" class="task_input">
		<button type="submit" name="submit" id="add_btn" class="add_btn">Add Task</button>
	</form>
<?php
}
else
{
//Otherwise, we display a link to log in and to Sign up
?>
<div class="sign">
<a href="connexion.php">Log in</a>
            </div>

<?php
}
?>
<!--todo list js starts here-->
            
            <?php 
    // initialize errors variable
	$errors = "";

	// connect to database
	$db = mysqli_connect("localhost", "root", "", "todo");

	// insert a quote if submit button is clicked
	if (isset($_POST['submit'])) {
		if (empty($_POST['task'])) {
			$errors = "You must fill in the task";
		}else{
			$task = $_POST['task'];
			$sql = "INSERT INTO tasks (task) VALUES ('$task')";
			mysqli_query($db, $sql);
			header('location: index.php');
		}
	}

// delete task
if (isset($_GET['del_task'])) {
	$id = $_GET['del_task'];

	mysqli_query($db, "DELETE FROM tasks WHERE id=".$id);
	header('location: index.php');
}

?>
		</div>
        
<!--To Do list Starts here-->
		
        <div class="heading">
		<h2 style="font-style: 'Hervetica';">ToDo List</h2>
	</div>
	
    <table>
	<thead>
		<tr>
			<th>N</th>
			<th>Tasks</th>
			<th style="width: 60px;">Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		// select all tasks if page is visited or refreshed
		$tasks = mysqli_query($db, "SELECT * FROM tasks");

		 while ($row = mysqli_fetch_array($tasks)) { ?>
			<tr>
				<td> <?php echo $row['ID']; ?> </td>
				<td class="task"> <?php echo $row['task']; ?> </td>
				<td class="delete"> 
					<a href="index.php?del_task=<?php echo $row['ID'] ?>">x</a> 
				</td>
			</tr>
		<?php } ?>	
	</tbody>
</table>
        
	</body>
</html>