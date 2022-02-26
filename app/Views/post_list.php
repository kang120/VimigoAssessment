<?php $this->extend("layout") ?>

<?php $this->section("title") ?>
    <title>Post Management</title>
<?php $this->endSection() ?>

<?php $this->section("content") ?>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="<?= base_url("css/post_list.css") ?>">

    <div>
        <table id="post_table">
            <col width="10%">
            <col width="10%">
            <col width="30%">
            <col width="30%">
            <col width="20%">
            <tr>
                <th>ID</th>
                <th>User_ID</th>
                <th>Title</th>
                <th>Body</th>
                <th>Action</th>
            </tr>
        </table>
    </div>

    <script>
        function createTable(data){
            console.log(data.length);
            var table = document.getElementById("post_table");

            for(let i = 0; i < data.length; i++){
                var row = document.createElement("tr");

                row.innerHTML += "<td>" + data[i].id + "</td>";
                row.innerHTML += "<td style='width:200px'>" + data[i].user_id + "</td>";
                row.innerHTML += "<td class='overflow-cell'>" + data[i].title + "</td>";
                row.innerHTML += "<td class='overflow-cell'>" + data[i].body + "</td>";

                buttonCell = document.createElement("td");
                buttonDiv = document.createElement("div");
                buttonDiv.className = "btn-cell";
                buttonDiv.innerHTML += "<button class='btn btn-primary'>" + "View" + "</button>";
                buttonDiv.innerHTML += "<button class='btn btn-warning'>" + "Update" + "</button>";
                buttonDiv.innerHTML += "<button class='btn btn-danger'>" + "Delete" + "</button>";
                buttonCell.appendChild(buttonDiv);

                row.appendChild(buttonCell);

                table.appendChild(row);
            }
        }  

        var posts = $.ajax({
            url: "https://gorest.co.in/public/v2/posts",
            method: "GET",
            success: function(data){
                createTable(data);
            },
            error: function(data){
                console.log("Something error");
            }
        })
    </script>
<?php $this->endSection() ?>