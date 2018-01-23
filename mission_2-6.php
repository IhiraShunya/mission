<?php
	$name=$_POST['name'];
	$comment=$_POST['comment'];
	$pass=$_POST['pass'];
	$time=date("m月d日 H時i分s秒");
	
	$filename='kadai2-2.txt';
	
	if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['pass'])){
		$fp=fopen($filename,'a');
		$content=file_get_contents('kadai2-2.txt');
		$contents=explode("<>",$content);
		$number=(count($contents)-1)/5+1;
		fwrite($fp,"$number<>$name<>$comment<>$time<>$pass<>\n");
		fclose($fp);
	}

	if(isset($_POST['trash'])){
		$password=$_POST['password'];
		$content=file_get_contents('kadai2-2.txt');
		$contents=explode("<>",$content);
		$trash=$_POST['trash'];
		foreach($contents as $key => $value){
			if(floor($key/5)+1==$trash && $key%5==4){
				$oripass=$value;
			}
		}
		
		if($password==$oripass && !$trash==0){
			echo $trash."番を削除しました。";

			$filename='kadai2-2.txt';
			$fp=fopen($filename,'w');
			foreach($contents as $key => $value){

				if(!($key==count($contents)-1)){
					if(!(floor($key/5)+1==$trash)){
						fwrite($fp,"$value<>");
					}

					elseif($key%5==0 || $key%5==4){
						fwrite($fp,"$value<>");
					}

					elseif($key%5==1){
						fwrite($fp,"削除されました。<>");
					}

					else{
						fwrite($fp,"<>");
					}
				}
			}
			fclose($fp);
		}
		
		elseif(!$trash==0){
			echo "パスワードが違います。";
		}
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
	
	$content=file_get_contents('kadai2-2.txt');
	$contents=explode("<>",$content);
	
	foreach($contents as $key => $value){
	
		if($key%5==0){
			echo $value;
		}

		if($key%5==1){
			echo " $value";
		}

		if($key%5==2){
			echo "        $value";
		}
		

		if($key%5==3){
			echo "        $value"."<hr>";
		}
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
		<form action="mission_2-6.php"method="post">
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
		<form action="mission_2-6.php"method="post">
			削除番号<br>
			<select name="trash">
			<option value="0"selected>-</option>
			<?php
				$content=file_get_contents('kadai2-2.txt');
				$contents=explode("<>",$content);
				$number=(count($contents)-1)/5;
				for($i=1;$i<=$number;$i++){
					foreach($contents as $key => $value){
						if(floor($key/5)+1==$i && $key%5==3){
							$date=$value;
						}
					}
		
					if(!($date=="")){
						echo'<option value="',$i,'">',$i,'</option>';
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
		<form action="mission_2-1.php"method="post">
			編集番号<br>
			<select name="edit">
			<option value="0"selected>-</option>
			<?php
				$content=file_get_contents('kadai2-2.txt');
				$contents=explode("<>",$content);
				$number=(count($contents)-1)/5;
				for($i=1;$i<=$number;$i++){
					foreach($contents as $key => $value){
						if(floor($key/5)+1==$i && $key%5==3){
							$date=$value;
						}
					}
		
					if(!($date=="")){
						echo'<option value="',$i,'">',$i,'</option>';
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
	</body>
</html>

