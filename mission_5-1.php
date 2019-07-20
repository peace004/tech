<html>
<mata http-equiv="content-type" charset="utf-8">

<?php
	$newname = "";
    $newcome = "";
    $edit_num = "";
    $passwordf = "pa";
	
	//DBログイン
	$dsn = 'データベース名';
	$user = 'ユーザ名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	//テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT"
	.");";
	$stmt = $pdo->query($sql);
	
	if(!empty($_POST['namef']) && !empty($_POST['comentf']) && !empty($_POST['passwordf'])){
		$pass = $_POST['passwordf'];
		
		if($pass == $passwordf){
			$namef = $_POST['namef'];
       	 	$commentf = $_POST['comentf'];
       	 	$timef = date("Y/m/d H:i:s");
        	echo "入力を受け付けました"."<br>";
        	
        	if(!empty($_POST['edit_num'])){
        		$edit = $_POST['edit_num'];
        		
        		$id = $edit; //変更する投稿番号
				$name = $namef;
				$comment = $commentf; //変更したい名前、変更したいコメントは自分で決めること
				$sql = 'update tbtest set name=:name,comment=:comment where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':name', $name, PDO::PARAM_STR);
				$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				
        	}else{
        		$sql = $pdo -> prepare("INSERT INTO tbtest (name, comment) VALUES (:name, :comment)");
				$sql -> bindParam(':name', $name, PDO::PARAM_STR);
				$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$name = $namef;
				$comment = $commentf; //好きな名前、好きな言葉は自分で決めること
				$sql -> execute();
        	}
        }
       }
       
       
       
        //削除フォーム機能
    if(!empty($_POST['num']) && !empty($_POST['password2'])){
    	$pass = $_POST['password2'];
    	if($pass == $passwordf){
        	$del_num = $_POST['num'];
        	
        	$id = $del_num;
			$sql = 'delete from tbtest where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
 
    	}else{
    		echo "パスワードが違います。"."<br>";
    	}
    }
             
    //編集番号指定と表示　実際の編集は上
    if(!empty($_POST['new']) && ($_POST['password3'] == $passwordf)){
    	$f = 0;
    	$new = $_POST['new'];
    	$edit_num = $new;
    	
    	$sql = 'SELECT * FROM tbtest';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		
    	$newname = $results[$edit_num-1]['name'];
    	$newcome = $results[$edit_num-1]['comment'];

    	
    }else if(!empty($_POST['password3']) && ($_POST['password3'] !== $passwordf)){
    	echo "パスワードが違います。"."<br>";
    }else{
    }

	
?>


<form action="mission_5-1.php" method="post">
<input type="text"  value="<?=$newname ?>" name="namef" placeholder="名前"><br>
<input type="text"  value="<?=$newcome ?>" name="comentf" placeholder="コメント"><br>
<input type="text"  value="" name="passwordf" placeholder="パスワード">
<input type="hidden"  value="<?=$edit_num ?>" name="edit_num">
<input type="submit" value="送信">
</form>

<form action="mission_5-1.php" method="post">
<input type="text" name="num" placeholder="削除対象番号"><br>
<input type="text"  value="" name="password2" placeholder="パスワード">
<input type="submit" value="削除">
</form>

<form action="mission_5-1.php" method="post">
<input type="text" name="new" placeholder="編集対象番号"><br>
<input type="text"  value="" name="password3" placeholder="パスワード">
<input type="submit" value="編集">
</form>


<?php
	//表示部分
	$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';
	echo "<hr>";
	}
?>

</html>