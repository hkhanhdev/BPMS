<?php include_once('topbar_sidebar.php');
include_once ('database.php'); ?>

<div id="content">
    <h1 style="font-weight: bold">Add product</h1>
    <table class="table table-hover">
        <tr>
            <th scope="col">Product name</th>
            <th scope="col">Price</th>
            <th scope="col">Category</th>
            <th scope="col">Image</th>
            <th scope="col">Color</th>
            <th scope="col">Title</th>
            <th scope="col">Material</th>
            <th scope="col">Weight</th>
        </tr>
        <tr>
            <td><input type="text" placeholder="Prd name" id="prd_name"></td>
            <td><input type="text" placeholder="$ Prd price" id="prd_price"></td>
            <td><input type="text" placeholder="Category" id="prd_cate"></td>
            <td><input type="text" placeholder="Image file name" id="image_file_name"></td>
            <td><input type="text" placeholder="Color" id="color"></td>
            <td><input type="text" placeholder="Title" id="title"></td>
            <td><input type="text" placeholder="Material" id="material"></td>
            <td><input type="text" placeholder="Weight" id="weight"></td>
        </tr>
        <tr>
            <th scope="col">Size</th>
        </tr>
        <tr>
            <td><input type="text" placeholder="Size" id="size"></td>
            <td><button type="button" class="btn btn-outline-primary" onclick="add_prd()">Add</button></td>
        </tr>

    </table>
    <br>
    <h1 style="font-weight: bold">All products</h1>
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Product name</th>
            <th scope="col">Price</th>
            <th scope="col">Category</th>
            <th scope="col">Image</th>
            <th scope="col">Status</th>
        </tr>
        </thead>
        <tbody>
<?php
$sql = "select p.prd_id,p.prd_name,p.prd_price,p.prd_image,c.cate_id,c.cate_name,p.is_displayed from tbl_product p left join tbl_category c on p.cate_id = c.cate_id;" ;
$result = mysqli_query($conn,$sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
        <tr>
            <th scope="row" id="prd_id"><?php echo $row['prd_id']?></th>
            <td><?php echo $row['prd_name']?></td>
            <td>$<?php echo $row['prd_price']?></td>
            <td><?php echo $row['cate_name']?></td>
            <td><?php echo $row['prd_image']?></td>
<?php if ($row['is_displayed'] == 1) {?>
            <td class="table-success">Displayed</td>
<?php }else{?>
            <td class="table-danger">Hidden</td>
<?php }?>
            <td><button type="button" class="btn btn-success" onclick="update_prd(<?php echo $row['prd_id']?>,1)">Display</button></td>
            <td><button type="button" class="btn btn-danger" onclick="update_prd(<?php echo $row['prd_id']?>,0)">Hide</button></td>
        </tr>
<?php
    }
} else {
    // Handle case when no rows are retrieved
    echo "<tr><td colspan='6'>No products found.</td></tr>";
}
?>

        </tbody>
    </table>
</div>

<?php
if (isset($_POST['prd_name']) && isset($_POST['prd_price']) && isset($_POST['prd_cate']) && isset($_POST['image'])) {
    $prd_name = $_POST['prd_name'];
    $prd_price = $_POST['prd_price'];
    $prd_cate = $_POST['prd_cate'];
    $img_file = $_POST['image'];
    $color = $_POST['color'];
    $title = $_POST['title'];
    $material = $_POST['material'];
    $weight = $_POST['weight'];
    $size = $_POST['size'];

    $check_if_cate_exist = "SELECT * FROM tbl_category WHERE cate_name = '$prd_cate'";
    $if_cate_exist = mysqli_query($conn, $check_if_cate_exist);
    // Check if cate already existed then just add new product, if not add new cate then add new product
    if ($if_cate_exist->num_rows == 0) {
        $insert_new_cate = "INSERT INTO tbl_category(cate_name) VALUES ('$prd_cate')";
        $inserted_new_cate = mysqli_query($conn, $insert_new_cate);
        $cate_now_exist = mysqli_query($conn, $check_if_cate_exist);
        $cate_row = $cate_now_exist ->fetch_assoc();

        $insert_prd = "INSERT INTO tbl_product(prd_name, prd_price, prd_image, cate_id,prd_color,prd_title,prd_material,prd_weight,prd_size) 
           VALUES ('$prd_name', '$prd_price','$img_file',{$cate_row['cate_id']},'$color','$title','$material','$weight','$size')";

        $inserted_prd = mysqli_query($conn, $insert_prd);
    }else {
        $cate_row = $if_cate_exist->fetch_assoc();

        $insert_prd = "INSERT INTO tbl_product(prd_name, prd_price, prd_image, cate_id,prd_color,prd_title,prd_material,prd_weight,prd_size) 
           VALUES ('$prd_name', '$prd_price','$img_file',{$cate_row['cate_id']},'$color','$title','$material','$weight','$size')";

        $inserted_prd = mysqli_query($conn, $insert_prd);
    }
}
?>
<script>
    function add_prd() {
        var prd_name = document.getElementById('prd_name').value;
        var prd_price = document.getElementById('prd_price').value;
        var prd_cate = document.getElementById('prd_cate').value;
        var image_file_name = document.getElementById('image_file_name').value;
        var color = document.getElementById('color').value;
        var title = document.getElementById('title').value;
        var material = document.getElementById('material').value;
        var weight = document.getElementById('weight').value;
        var size = document.getElementById('size').value;
        // Make AJAX request to the PHP file
        var xhr = new XMLHttpRequest();
        xhr.open('POST', window.location.href, true);
        // Create a new FormData object and append the value to it
        var formData = new FormData();
        formData.append('prd_name', prd_name);
        formData.append('prd_price',prd_price);
        formData.append('prd_cate',prd_cate);
        formData.append('image',image_file_name);
        formData.append('color', color);
        formData.append('title',title);
        formData.append('material',material);
        formData.append('weight',weight);
        formData.append('size',size);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                alert("Adding product to database ...")
                window.location.href = "product_manage.php";
            }
        };
        xhr.send(formData);
    }

    function update_prd(prd_id,status) {
        // Make AJAX request to the PHP file
        var xhr = new XMLHttpRequest();
        xhr.open('POST', window.location.href, true);
        // Create a new FormData object and append the value to it
        var formData = new FormData();
        formData.append('prd_id', prd_id);
        formData.append('status', status);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                alert("Updating ...")
                window.location.href = "product_manage.php";
            }
        };
        xhr.send(formData);
    }
</script>
<?php
if (isset($_POST['prd_id']) && isset($_POST["status"])) {
$prd_id = $_POST['prd_id'];
$update_prd = "update tbl_product set is_displayed = ".$_POST['status']." WHERE prd_id = ".$prd_id;
$updated_prd = mysqli_query($conn, $update_prd);
}
?>

<?php
$conn->close();
include_once ('footer.php')
?>
