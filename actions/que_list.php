<?php 
ob_start();
session_start(); 
 include '../classes/autoload.php' ;

 // session redirection
if (!isset($_SESSION['adm'])) {
    header("Location: ../home.php");
    exit;
}

$que = new Question();

	if (isset($_GET['delId'])) {
		$id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['delId']);
    	$delQue = $que->QueDelete($id);
		echo "<script>
alert('Question ID ".$id." deleted!');
window.location.href='que_list.php';
</script>";
		
    }
 ?>
<div class="grid_10">
    <div class="box round first grid">
	<?php
	if (isset($delQue) && !is_array($delQue)) {
            echo $delQue;
        }
		?>   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <?php include_once '../components/boot.php';?>
	<link rel="stylesheet" href="../components/style.css">                   
    <title>Question List - Paint Of Heart</title>
</head>
<body>
	<h2>Question List</h2>
		<a href="../admin.php" class='btn btn-primary my-3 dash-btn fw-bold'>Dashboard</a>
		<a href="../home.php" class='btn btn-primary my-3 home-btn fw-bold'>Home</a>
        <div class="block"> 
			<table class="table" id="example">
				<thead>
					<tr>
						<th scope="col">Num/ID</th>
						<th scope="col">ProdID</th>
						<th scope="col">Product</th>
						<th scope="col">Category</th>
						<th scope="col">User</th>
						<th scope="col">Date</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
			<tbody>
		<?php 
			$question = $que->AllQuestionAll();
			if (is_array($question)) { // if any question
				$i=0;
				$canaddans=0;
				$canedans=0;
				foreach ($question as $question) {
					$i++;
					if ($question['answer']==='') { // if it is not yet answered
						$answer = '(Not answered yet)';
						$canaddans=1;
				$canedans=0;
					} else { // if it is  answered
						$answer = $question['answer'];
						$canaddans=0;
				$canedans=1;
					}
		?>
				<tr class="odd gradeX">
					<td><?php echo $i.'/'.$question['queid']; ?></td>
					<td><?php echo $question['fk_item_id'] ;?></td>
					<td><?php echo $question['name'] ;?></td>
					<td><?php echo $question['catName'] ;?></td>
					<td><?php echo $question['email'] ;?></td>
					<td><?php echo $question['date'] ;?></td>
					<td>
					<div class="btn-group">
						<a onclick="return confirm('Are you sure to delete this question? (<?php echo 'QueID '.$question['queid'].', ProdID '.$question['fk_item_id'].', by '.$question['email'] ?>)?')" class= "btn btn-danger" href="?delId=<?php echo $question['queid'] ?>" title='Delete'><i class='material-icons'>&#xE872;</i></a>&nbsp;
						<a class= "btn btn-primary" href="../question.php?id=<?php echo $question['fk_item_id']."#".$question['queid'] ?>" title='View'><i class='material-icons'>&#xE417;</i></a>
				</div>
				</td>
					</tr>
					<tr><td colspan="7" id='<?php echo $question['queid']; ?>'>Q: <?php echo $question['question'] ;?></td></tr>
					<tr><td colspan="7">A: <?php echo $answer ;?><br>
					<form id='form-ans'>  <!-- Answer form  -->
					<textarea class='form-control my-3' rows='2' name='answer' id='AnsBtn-<?php echo $question['queid']; ?>' placeholder='Your Answer' /></textarea>
            <p class='text-danger' id='AnsBtn-<?php echo $question['queid']; ?>'></p>
			<input type="hidden" name="queid" id='AnsBtn-<?php echo $question['queid']; ?>' value="<?php echo $question['queid'] ?>" />
			<?php 
			if ($canaddans==1) { // add answer
				$ansclass = 'success';
				$anstitle = 'Add';
			} else if  ($canedans==1) { // edit answer
				$ansclass = 'warning';
				$anstitle = 'Edit';
			}
			echo "<button id='AnsBtn-".$question['queid']."' class='btn btn-".$ansclass." mb-3' type='submit'  value='".$anstitle." Answer'>".$anstitle." Answer</button>";
			 ?>	
			 </form><!-- End of Answer form  -->
		</td></tr>

		<?php } } else  { ?>
			<tr class="odd gradeX">
			<td colspan="8" class="text-center">No data available</td>
			</tr>
			<?php }
		?>	

			</tbody>
		</table>

       </div>
    </div>
</div>
<?php include '../components/footer.php';?>
<script>
        var error = '';

		// apply eventlistener to all buttons
		var button = document.querySelectorAll('button[id^=AnsBtn]');
		for (var i = 0; i < button.length; i++) {
button[i].addEventListener("click", insertAns);

           // FUNCTION: add/edit answer
   		function insertAns(e) {
            e.preventDefault(); //this prevents the page from refreshing after submitting
			var a=event.target.id; // collect all elements related to the clicked button id
	 answerx = document.querySelector("textarea[id^="+a+"]");
	 queidx = document.querySelector("input[id^="+a+"]");
	 anserrx = document.querySelector("p[id^="+a+"]");
	 
			let answer=answerx.value; //saving answer value
			console.log(answer)
			let queid=queidx.value; //saving queid value
			console.log(queid)
            anserrx.innerHTML =  '';
            
            if (answer == "") { // answer validation
                error = "Answer must be filled";
                anserrx.innerHTML = error;
                return false;
              }

            let params=`answer=${answer}&queid=${queid}`; //creating the parameters for the POST method
            console.log(params)
            
            let request=new XMLHttpRequest (); //creating new request
            request.open("POST", "que_edit.php" ,true); //connecting to the php file of process
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); //setting header for POST method
            request.onload=function(){
                if(this.status== 200){
                    location.reload();
            }
            }
            request.send(params); //send parameters to be further processed by php
        }
 }

		</script>
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>