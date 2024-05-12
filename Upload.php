<?php 
    $con = mysqli_connect("localhost","root","","test1");
    if($con==false){
        die("connection Error".mysql_connect_error());
    }
    else{
        echo "it's sucessful";
    }
    if(isset($_POST['submit'])){

        $username=$_POST['username'];
        $name=$_POST['name'];
        $originalFilename=$_FILES['image']['name'];
        $uniqueFilename = md5(uniqid(rand(), true)) . '_' . $originalFilename;
        $tempname=$_FILES['image']['tmp_name'];
        $folder = 'photos/'.$uniqueFilename;
        $uniquename = 'photos/'.$uniqueFilename;
        $query=mysqli_query($con,"insert into registration (name,username,image_path) values ('$name','$username',' $uniquename ')");
        if(move_uploaded_file($tempname,$folder)){
            echo "<h2>file uploaded successfully </h2>";

        }
        else {
            echo "<h2>not uploaded </h2>";
        }
        
}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h2>User Registration</h2>
    
    
    <form  method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="image">Upload Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>
        <input type="submit"  name="submit" value="submit">
        
    </form>
    
    <div>
        <?php 
            $res = mysqli_query($con, "SELECT * FROM registration" );
            while($row= mysqli_fetch_assoc($res)){
        ?>
        <img src="<?php echo $row['image_path'] ?>" />
        <?php } ?>
    </div>    
</body>
</html>
