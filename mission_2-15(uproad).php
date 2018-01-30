<?php

	$dsn='データベース名';
	$user='ユーザー名';
	$password='パスワード';
	$pdo=new PDO($dsn,$user,$password);

	$sql = 'SELECT * FROM mission';
	$results = $pdo -> query($sql);

	$name=$_POST['name'];
	$comment=$_POST['comment'];
	$pass=$_POST['pass'];
	$time=date("m月d日 H時i分s秒");
	
	if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['pass'])){
		
		foreach ($results as $row){
			$id=$row[0];
		}
		$id=$id+1;

		$sql=$pdo->prepare("INSERT INTO mission (id,name,comment,time,pass) VALUES(:id,:name,:comment,:time,:pass)");
		$sql -> bindValue(':id', $id, PDO::PARAM_INT);
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':time', $time, PDO::PARAM_STR);
  		$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

		$sql -> execute();

	}

	if(isset($_POST['trash'])){
		$password=$_POST['password'];
		$trash=$_POST['trash'];
		foreach($results as $row){
			if($trash==$row[0]){
				$oripass=$row[4];
			}
		}
		
		if($password==$oripass && !$trash==0){
			echo $trash."番を削除しました。";

			$nm="削除されました。"; $kome=""; $pw=""; $dt="";
			$sql="update mission set name='$nm',comment='$kome',time='$dt',pass='$pw' where id='$trash'";
			$result=$pdo->query($sql);
		}
		
		elseif(!$trash==0){
			echo "パスワードが違います。";
		}
	}

	if(isset($_POST['editmode'])){
		$password=$_POST['password'];
		$edit=$_POST['edit'];
		foreach($results as $row){
			if($edit==$row[0]){
				$oripass=$row[4];
			}
		}
		if(!($password==$oripass) && !$edit==0){
			echo "パスワードが違います。";
		}
	}

	if(!empty($_POST['newname']) && !empty($_POST['newcomment'])){
		$editnumber=$_POST['editnumber'];
		$newname=$_POST['newname'];
		$newcomment=$_POST['newcomment'];
		$newtime=$time."に編集";

		$sql="update mission set name='$newname',comment='$newcomment',time='$newtime' where id='$editnumber'";
		$result=$pdo->query($sql);

		echo "編集しました";
	}

?>

<html>
	<head>
		<meta charset"UFT-8">
		<title>掲示板(仮)</title>
	</head>

	<body>
		<h1>プログラミング練習報告ブログ</h1>

<?php
	if(!(isset($_POST['editmode'])) || !($password==$oripass) || $edit==0){
		$sql = 'SELECT * FROM mission';
		$results = $pdo -> query($sql);

		foreach($results as $row){
			for($i=0;$i<4;$i++){
				echo " $row[$i]";
			}
			echo "<hr>";
		}
?>
	<style>
		table {
			border-collapse: collapse;
		}
		td {
			border: solid 1px;
			padding: 0.5em;
		}
	</style>
	<table>
	<tr>
	<td>
		<h1>入力フォーム</h1>
		<form action="mission_2-15.php"method="post">
			名前<br>
			<input type="text"name="name"><br>
			コメント<br>
			<textarea cols="20"rows="3"name="comment"></textarea><br>
			パスワード<br>
			<input type="password"name="pass">
			<input type="submit"value="送信">
		</form>
	</td>
	<td>
		<h1>削除フォーム</h1>
		<form action="mission_2-15.php"method="post">
			削除番号<br>
			<select name="trash">
			<option value="0"selected>-</option>
			<?php
				$sql = 'SELECT * FROM mission';
				$results = $pdo -> query($sql);

				foreach($results as $row){
					if(!($row[3]=="")){
						echo'<option value="',$row[0],'">',$row[0],'</option>';
					}
				}
			?>
			</select><br>
			パスワード<br>
			<input type="password"name="password">
			<input type="submit"value="削除する">
		</form><br><br>
	</td>
	<td>
		<h1>編集フォーム</h1>
		<form action="mission_2-15.php"method="post">
			編集番号<br>
			<select name="edit">
			<option value="0"selected>-</option>
			<?php
				$sql = 'SELECT * FROM mission';
				$results = $pdo -> query($sql);

				foreach($results as $row){
					if(!($row[3]=="")){
						echo'<option value="',$row[0],'">',$row[0],'</option>';
					}
				}
			?>
			</select><br>
			パスワード<br>
			<input type="password"name="password">
			<input type="hidden"name="editmode">
			<input type="submit"value="編集する">
		</form><br><br>
	</td>
	</tr>
	</table>

<?php
	}
		else{
			$password=$_POST['password'];
			$edit=$_POST['edit'];
			foreach($results as $row){
				if($edit==$row[0]){
					$oripass=$row[4];
				}
			}
		
			if($password==$oripass && !$edit==0){
				echo $_POST['edit']."番の編集";
				
				$sql = 'SELECT * FROM mission';
				$results = $pdo -> query($sql);

				foreach($results as $row){
					if($row[0]==$edit){
						$oriname=$row[1];
						$oricomment=$row[2];
					}
				}

			echo '<form action="mission_2-15.php"method="post">';
			echo '	<h2>名前</h2>';
//テキストボックスに前の文字データを表示する。
			echo '	<input type="text"name="newname"value="',$oriname,'"><br> ';
			echo '	<h2>コメント</h2>';
			echo '	<textarea cols="20" rows="3"name="newcomment">',$oricomment,'</textarea>';
			echo '  <input type ="hidden"name="editnumber"value="',$edit,'">';
			echo '	<input type="submit"value="送信">';
			echo '</form>';
			}
		}
			
?>
	</body>
</html>