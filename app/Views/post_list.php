<?php $this->extend("layout") ?>

<?php $this->section("title") ?>
    <title>Post Management</title>
<?php $this->endSection() ?>

<?php $this->section("content") ?>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="<?= base_url("css/post_list.css") ?>">

    <div id="post_list">
        <table id="post_table">
            <col width="8%">
            <col width="8%">
            <col width="32%">
            <col width="32%">
            <col width="20%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User_ID</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div id="paging-container">
            <button id="first-btn" class='btn btn-paging-ctrl'>first</button>
            <button id="prev-btn" class='btn btn-paging-ctrl'>prev</button>
            <div id="page-btn-container"></div>
            <button id="next-btn" class='btn btn-paging-ctrl'>next</button>
            <button id="last-btn" class='btn btn-paging-ctrl'>last</button>
        </div>
    </div>

    <div id="view_modal">
        <h2 id="post_id" style="text-align: center"></h2>
        <div class="post-container">
            <div class="title-box">
                <div id="title"></div>
                <div id="author"></div>
            </div>
            <div class="body-box">
                <p id="body"></p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 30px"><button class='btn btn-primary' onclick="close_modal()">Close</button></div>
    </div>

    <script>
        var postData;   // store all posts
        var totalPages;   // store total number of post pages
        var currentPage = 1;
        const totalPagingBtn = 9;   // number of paging btns below the table

        function updateTable(data){
            console.log(data.length);
            var table = document.getElementsByTagName("tbody")[0];

            table.innerHTML = "";   // clear the table

            for(let i = 0; i < data.length; i++){
                var row = document.createElement("tr");

                row.innerHTML += "<td>" + data[i].id + "</td>";
                row.innerHTML += "<td>" + data[i].user_id + "</td>";
                row.innerHTML += "<td class='overflow-cell'>" + data[i].title + "</td>";
                row.innerHTML += "<td class='overflow-cell'>" + data[i].body + "</td>";

                buttonCell = document.createElement("td");
                buttonDiv = document.createElement("div");
                buttonDiv.className = "btn-cell";
                buttonDiv.innerHTML += "<button class='btn btn-primary' onclick=view_post(" + data[i].id + ")>" + "View" + "</button>";
                buttonDiv.innerHTML += "<button class='btn btn-warning'>" + "Update" + "</button>";
                buttonDiv.innerHTML += "<button class='btn btn-danger'>" + "Delete" + "</button>";
                buttonCell.appendChild(buttonDiv);

                row.appendChild(buttonCell);

                table.appendChild(row);
            }
        }

        function view_post(post_id){
            $("#post_list").css("opacity", 0.1);   // fade the table
            $(".btn-cell button").css("cursor", "default");   // remove hand cursor on button
            $(".btn-cell button").prop("disabled", true);   // disable the buttons function
            
            $("#view_modal").css("display", "block");   // show post modal

            for(let i = 0; i < postData.length; i++){
                if(postData[i].id == post_id){
                    $("#post_id").html("Post " + post_id);
                    $("#title").html(postData[i].title);
                    $("#author").html("Author: " + postData[i].user_id);
                    $("#body").html(postData[i].body);
                    break;
                }
            }
        }

        function close_modal(){
            $("#view_modal").css("display", "none");   // hide post modal
            
            $("#post_list").css("opacity", 1);   // set the table opacity higher
            $(".btn-cell button").css("cursor", "hand");   // set hand cursor on button
            $(".btn-cell button").prop("disabled", false);   // enable the buttons function
        }

        function UI_setPagingButton(){
            if(currentPage == 1){
                $("#first-btn").css("visibility", "hidden");
                $("#prev-btn").css("visibility", "hidden");
            }else{
                $("#first-btn").css("visibility", "visible");
                $("#prev-btn").css("visibility", "visible");
            }

            if(currentPage == totalPages){
                $("#last-btn").css("visibility", "hidden");
                $("#next-btn").css("visibility", "hidden");
            }else{
                $("#last-btn").css("visibility", "visible");
                $("#next-btn").css("visibility", "visible");
            }

            $("#page-btn-container").html("");   // clear all btns

            let counter = 1;

            var firstBtn = 1;   // the first button in paging btns
            
            if(currentPage > totalPages - Math.floor(totalPagingBtn / 2)){
                firstBtn = totalPages - totalPagingBtn + 1;
            }else if(currentPage > 5){
                firstBtn = currentPage - Math.floor(totalPagingBtn / 2);
            }

            while(counter <= totalPagingBtn){
                var page_btn = "<button class='btn btn-paging' onclick='AJAX_get_posts(" + firstBtn + ")'>" + firstBtn + "</button>";

                if(firstBtn == currentPage){
                    page_btn = "<button class='btn btn-paging btn-currentpage' onclick='AJAX_get_posts(" + firstBtn + ")'>" + firstBtn + "</button>"
                }

                $("#page-btn-container").append(page_btn);

                firstBtn++;
                counter++;
            }
        }

        function AJAX_get_posts(pageNumber){
            currentPage = pageNumber;

            // AJAX call to get posts
            var xhr = $.ajax({
                url: "https://gorest.co.in/public/v2/posts?page=" + pageNumber,
                method: "GET",
                success: function(data, status){
                    totalPages = xhr.getResponseHeader("X-Pagination-Pages");   // get total pages of posts
                    console.log(totalPages);
                    postData = data;
                    updateTable(data);   // create a post table
                    UI_setPagingButton();
                },
                error: function(data){
                    console.log("Something error");
                }
            });
        }

        window.onload = function(){
            AJAX_get_posts(1);

            $("#first-btn").click(function(){
                currentPage = 1;
                AJAX_get_posts(currentPage);
            })

            $("#prev-btn").click(function(){
                currentPage -= 1;
                AJAX_get_posts(currentPage);
            })
            
            $("#last-btn").click(function(){
                currentPage = totalPages;
                AJAX_get_posts(currentPage);
            })

            $("#next-btn").click(function(){
                currentPage += 1;
                AJAX_get_posts(currentPage);
            })
        }
    </script>
<?php $this->endSection() ?>