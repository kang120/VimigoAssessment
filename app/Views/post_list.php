<?php $this->extend("layout") ?>

<?php $this->section("title") ?>
    <title>Post Management</title>
<?php $this->endSection() ?>

<?php $this->section("content") ?>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="<?= base_url("css/post_list.css") ?>">

    <div id="post-list">
        <div class="search-box">
            <div style="font-size: 1.2em">Search by
            <select id="filter_type" style="height: 30px;" oninput="search_filter()">
                <option value="id">POST ID</option>
                <option value="user_id">USER ID</option>
                <option value="title">TITLE</option>
                <option value="body">BODY</option>
            </select>
            <input id="searchbar" type="text" oninput="search_filter()"></div>
            <button class="btn btn-success" style="margin-left: auto; margin-right: 30px;" onclick="open_createModal()">New Post</button>
        </div>
        <table id="post-table" style="margin-top: 20px">
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
            <button id="first-btn" class='btn btn-success'>first</button>
            <button id="prev-btn" class='btn btn-success'>prev</button>
            <div id="page-btn-container"></div>
            <button id="next-btn" class='btn btn-success'>next</button>
            <button id="last-btn" class='btn btn-success'>last</button>
        </div>
    </div>

    <div id="create-modal">
        <h2 style="text-align: center">New Post</h2>
        <div style="margin-top: 15px; padding: 0 15px;">
            <div style="font-size: 1.3em; display: flex; align-items:center;"><b>Author: </b><input id="create-author" type="number"></div>
            <div style="font-size: 1.5em; margin-top: 15px;"><b>Title:</b></div>
            <textarea id="create-title" class="title-textarea"></textarea>
            <div style="margin-top: 30px; font-size: 1.5em;"><b>Body:</b></div>
            <textarea id="create-body" class="body-textarea"></textarea>
        </div>
        <div style="text-align: center; margin-top: 30px">
            <button class='btn btn-primary' onclick="open_createSubmitModal()">Create</button>
            <button class='btn btn-secondary' style="margin-left: 25px;" onclick="close_createModal()">Cancel</button>
        </div>
    </div>

    <div id="create-submit-modal">
        <div style="font-size: 1.3em">Are you sure to create the post?</div>
        <div style="margin-top: 20px">
            <button class='btn btn-warning' onclick="submit_create()">Confirm</button>
            <button class='btn btn-secondary' style="margin-left: 25px;" onclick="close_createSubmitModal()">Cancel</button>
        </div>
    </div>

    <div id="create-result-modal" class="result-modal">
        <div style="font-size: 1.2em;"><b></b></div>
        <div><button style="margin-top: 20px;" class='btn btn-primary' onclick="close_all_modal()">Close</button></div>
    </div>

    <div id="view-modal">
        <h2 id="view-post-id" style="text-align: center"></h2>
        <div class="post-container">
            <div class="view-title-box">
                <div id="view-title"></div>
                <div id="view-author"></div>
            </div>
            <div class="view-body-box">
                <p id="view-body"></p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 30px"><button class='btn btn-primary' onclick="close_viewModal()">Close</button></div>
    </div>

    <div id="update-modal">
        <h2 id="update-post-id" style="text-align: center"></h2>
        <div id="update-author" style="text-align: center; font-size: 15px; margin-top: 5px;"></div>
        <div style="margin-top: 15px; padding: 0 15px;">
            <div style="font-size: 1.5em;"><b>Title:</b></div>
            <textarea id="update-title" class="title-textarea"></textarea>
            <div style="margin-top: 30px; font-size: 1.5em;"><b>Body:</b></div>
            <textarea id="update-body" class="body-textarea"></textarea>
        </div>
        <div style="text-align: center; margin-top: 30px">
            <button class='btn btn-primary' onclick="open_updateSubmitModal()">Update</button>
            <button class='btn btn-secondary' style="margin-left: 25px;" onclick="close_updateModal()">Cancel</button>
        </div>
    </div>

    <div id="update-submit-modal">
        <div style="font-size: 1.3em">Are you sure to update the post?</div>
        <div style="margin-top: 20px">
            <button class='btn btn-warning' onclick="submit_update()">Confirm</button>
            <button class='btn btn-secondary' style="margin-left: 25px;" onclick="close_updateSubmitModal()">Cancel</button>
        </div>
    </div>

    <div id="update-result-modal" class="result-modal">
        <div style="font-size: 1.2em;"><b></b></div>
        <div><button style="margin-top: 20px;" class='btn btn-primary' onclick="close_all_modal()">Close</button></div>
    </div>

    <div id="delete-modal">
        <div style="font-size: 1.3em">Are you sure to delete the post?</div>
        <div style="margin-top: 20px">
            <button class='btn btn-warning' onclick="submit_delete()">Confirm</button>
            <button class='btn btn-secondary' style="margin-left: 25px;" onclick="close_deleteModal()">Cancel</button>
        </div>
    </div>
    
    <div id="delete-result-modal" class="result-modal">
        <div style="font-size: 1.2em;"><b></b></div>
        <div><button style="margin-top: 20px;" class='btn btn-primary' onclick="close_all_modal()">Close</button></div>
    </div>

    <script>
        var postData;   // store all posts
        var totalPages;   // store total number of post pages
        var currentPage = 1;
        const totalPagingBtn = 9;   // number of paging btns below the table

        var deletePostSelected = -1;

        function updateTable(data){
            console.log("Post per page: ", data.length);
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
                buttonDiv.innerHTML += "<button class='btn btn-warning' onclick=update_post(" + data[i].id + ")>" + "Update" + "</button>";
                buttonDiv.innerHTML += "<button class='btn btn-danger' onclick=delete_post(" + data[i].id + ")>" + "Delete" + "</button>";
                buttonCell.appendChild(buttonDiv);

                row.appendChild(buttonCell);

                table.appendChild(row);
            }
        }

        function search_filter(){
            AJAX_get_posts(1);
        }

        function view_post(post_id){
            open_viewModal();

            for(let i = 0; i < postData.length; i++){
                if(postData[i].id == post_id){
                    $("#view-post-id").html("Post " + post_id);
                    $("#view-title").html(postData[i].title);
                    $("#view-author").html("Author: " + postData[i].user_id);
                    $("#view-body").html(postData[i].body);
                    break;
                }
            }
        }

        function update_post(post_id){
            open_updateModal();

            for(let i = 0; i < postData.length; i++){
                if(postData[i].id == post_id){
                    $("#update-post-id").html("Post " + post_id);
                    $("#update-title").val(postData[i].title);
                    $("#update-author").html("Author: " + postData[i].user_id);
                    $("#update-body").val(postData[i].body);
                    break;
                }
            }
        }

        function delete_post(post_id){
            deletePostSelected = post_id;

            open_deleteModal();
        }

        function open_createModal(){
            $("#post-list").css("opacity", 0.1);   // fade the table
            $(".btn-cell button").css("cursor", "default");   // remove hand cursor on button
            $(".btn-cell button").prop("disabled", true);   // disable the buttons function
            
            $("#create-modal").css("display", "block");   // show post modal
        }

        function close_createModal(){
            $("#create-modal").css("display", "none");   // hide create post modal
            
            $("#post-list").css("opacity", 1);   // set the table opacity higher
            $(".btn-cell button").css("cursor", "hand");   // set hand cursor on button
            $(".btn-cell button").prop("disabled", false);   // enable the buttons function
        }

        function open_createSubmitModal(){
            $("#create-modal").css("opacity", 0.8);   // fade the update modal
            $("#create-modal button").css("cursor", "default");   // remove hand cursor on button
            $("#create-modal button").prop("disabled", true);   // disable the buttons function
            
            $("#create-submit-modal").css("display", "flex");
        }

        function close_createSubmitModal(){
            $("#create-submit-modal").css("display", "none");   // hide post modal
            
            $("#create-modal").css("opacity", 1);   // set the update modal opacity higher
            $("#create-modal button").css("cursor", "hand");   // set hand cursor on button
            $("#create-modal button").prop("disabled", false);   // enable the buttons function
        }

        function open_createResultModal(result, message){
            close_createSubmitModal();
            
            $("#create-result-modal").css("display", "block");


            if(result == "success"){
                $("#create-result-modal b").css("color", "green");
            }else{
                $("#create-result-modal b").css("color", "red");
            }

            $("#create-result-modal b").html(message);
        }

        function close_createResultModal(){
            $("#create-submit-modal").css("opacity", 1);   // set the update confirm modal opacity higher
            $("#create-submit button").css("cursor", "hand");   // set hand cursor on button
            $("#create-submit button").prop("disabled", false);   // enable the buttons function
            
            $("#create-result-modal").css("display", "none");
        }

        function open_viewModal(){
            $("#post-list").css("opacity", 0.1);   // fade the table
            $(".btn-cell button").css("cursor", "default");   // remove hand cursor on button
            $(".btn-cell button").prop("disabled", true);   // disable the buttons function
            
            $("#view-modal").css("display", "block");   // show post modal
        }

        function close_viewModal(){
            $("#view-modal").css("display", "none");   // hide post modal
            
            $("#post-list").css("opacity", 1);   // set the table opacity higher
            $(".btn-cell button").css("cursor", "hand");   // set hand cursor on button
            $(".btn-cell button").prop("disabled", false);   // enable the buttons function
        }

        function open_updateModal(){
            $("#post-list").css("opacity", 0.1);   // fade the table
            $(".btn-cell button").css("cursor", "default");   // remove hand cursor on button
            $(".btn-cell button").prop("disabled", true);   // disable the buttons function
            
            $("#update-modal").css("display", "block");   // show update post modal
        }

        function close_updateModal(){
            $("#update-modal").css("display", "none");   // hide post modal
            
            $("#post-list").css("opacity", 1);   // set the table opacity higher
            $(".btn-cell button").css("cursor", "hand");   // set hand cursor on button
            $(".btn-cell button").prop("disabled", false);   // enable the buttons function
        }

        function open_updateSubmitModal(){
            $("#update-modal").css("opacity", 0.8);   // fade the update modal
            $("#update-modal button").css("cursor", "default");   // remove hand cursor on button
            $("#update-modal button").prop("disabled", true);   // disable the buttons function
            
            $("#update-submit-modal").css("display", "flex");
        }

        function close_updateSubmitModal(){
            $("#update-submit-modal").css("display", "none");   // hide post modal
            
            $("#update-modal").css("opacity", 1);   // set the update modal opacity higher
            $("#update-modal button").css("cursor", "hand");   // set hand cursor on button
            $("#update-modal button").prop("disabled", false);   // enable the buttons function
        }

        function open_updateResultModal(result, message){
            close_updateSubmitModal();
            
            $("#update-result-modal").css("display", "block");


            if(result == "success"){
                $("#update-result-modal b").css("color", "green");
            }else{
                $("#update-result-modal b").css("color", "red");
            }

            $("#update-result-modal b").html(message);
        }

        function close_updateResultModal(){
            $("#update-submit-modal").css("opacity", 1);   // set the update confirm modal opacity higher
            $("#update-submit button").css("cursor", "hand");   // set hand cursor on button
            $("#update-submit button").prop("disabled", false);   // enable the buttons function
            
            $("#update-result-modal").css("display", "none");
        }

        function open_deleteModal(){
            $("#post-list").css("opacity", 0.1);   // fade the table
            $(".btn-cell button").css("cursor", "default");   // remove hand cursor on button
            $(".btn-cell button").prop("disabled", true);   // disable the buttons function
            
            $("#delete-modal").css("display", "block");   // show update post modal
        }

        function close_deleteModal(){
            $("#post-list").css("opacity", 1);   // set the delete modal opacity higher
            $("#post-list button").css("cursor", "hand");   // set hand cursor on button
            $("#post-list button").prop("disabled", false);   // enable the buttons function

            $("#delete-modal").css("display", "none");   // hide delete modal
        }

        function open_deleteResultModal(result){
            close_deleteModal();
            
            $("#delete-result-modal").css("display", "block");

            if(result == "success"){
                $("#delete-result-modal b").css("color", "green");
                $("#delete-result-modal b").html("Delete Successfully");
            }else{
                $("#delete-result-modal b").css("color", "red");
                $("#delete-result-modal b").html("Delete Failed. Try Again Later");
            }
        }

        function close_deleteResultModal(){
            $("#delete-modal").css("opacity", 1);   // set the delete modal opacity higher
            $("#delete-modal button").css("cursor", "hand");   // set hand cursor on button
            $("#delete-modal button").prop("disabled", false);   // enable the buttons function
            
            $("#delete-result-modal").css("display", "none");
        }

        function close_all_modal(){
            close_createResultModal();
            close_createSubmitModal();
            close_createModal();

            close_updateResultModal();
            close_updateSubmitModal();
            close_updateModal();

            close_deleteResultModal();
            close_deleteModal();
            
            AJAX_get_posts(currentPage);   // refresh table
        }

        function submit_create(){
            var post_author = $("#create-author").val();
            var post_title = $("#create-title").val();
            var post_body = $("#create-body").val();
            
            var data = {
                "user_id": post_author,
                "title": post_title,
                "body": post_body
            };

            AJAX_create_posts(data);
        }

        function submit_update(){
            var post_id = $("#update-post-id").html().split(" ")[1];
            var post_author = $("#update-author").html().split(" ")[1];
            var post_title = $("#update-title").val();
            var post_body = $("#update-body").val();
            
            var data = {
                "id": post_id,
                "user_id": post_author,
                "title": post_title,
                "body": post_body
            };

            AJAX_update_posts(data);
        }

        function submit_delete(){
            AJAX_delete_posts(deletePostSelected);
        }

        function UI_setPagingButton(){
            if(currentPage == 1){
                $("#first-btn").css("visibility", "hidden");
                $("#prev-btn").css("visibility", "hidden");
            }else{
                $("#first-btn").css("visibility", "visible");
                $("#prev-btn").css("visibility", "visible");
            }

            if(currentPage == totalPages || totalPages == 0){
                $("#last-btn").css("visibility", "hidden");
                $("#next-btn").css("visibility", "hidden");
            }else{
                $("#last-btn").css("visibility", "visible");
                $("#next-btn").css("visibility", "visible");
            }

            $("#page-btn-container").html("");   // clear all btns

            if(totalPages == 0){
                return;
            }

            let counter = 1;

            var firstBtn = 1;   // the first button in paging btns
            
            if(currentPage >= 10 && currentPage > totalPages - Math.floor(totalPagingBtn / 2)){
                firstBtn = totalPages - totalPagingBtn + 1;
            }else if(currentPage > 5){
                firstBtn = currentPage - Math.floor(totalPagingBtn / 2);
            }

            while(counter <= totalPagingBtn && firstBtn <= totalPages){
                var page_btn = "<button class='btn btn-paging' onclick='AJAX_get_posts(" + firstBtn + ")'>" + firstBtn + "</button>";

                if(firstBtn == currentPage){
                    page_btn = "<button class='btn btn-paging btn-currentpage' onclick='AJAX_get_posts(" + firstBtn + ")'>" + firstBtn + "</button>"
                }

                $("#page-btn-container").append(page_btn);

                firstBtn++;
                counter++;
            }
        }

        function AJAX_get_posts(pageNumber, query){
            currentPage = pageNumber;

            var filter_type = $("#filter_type").val();
            var search_filter = $("#searchbar").val();

            var query = filter_type + "=" + search_filter;

            // AJAX call to get posts
            var xhr = $.ajax({
                url: "https://gorest.co.in/public/v2/posts?page=" + pageNumber + "&" + query,
                method: "GET",
                headers: {
                    "Authorization": "Bearer b16cef22dd918befa7ed4cad3bb4b161a66a0d49bb582845a8cd7a398b0e8a70"
                },
                success: function(data, status){
                    totalPages = xhr.getResponseHeader("X-Pagination-Pages");   // get total pages of posts
                    console.log("Total pages: ", totalPages);
                    postData = data;
                    updateTable(data);   // create a post table
                    UI_setPagingButton();
                },
                error: function(data){
                    console.log("Something error");
                }
            });
        }

        function AJAX_create_posts(data){

            console.log(data.user_id);
            // AJAX call to update post
            var xml = $.ajax({
                url: "https://gorest.co.in/public/v2/posts",
                method: "POST",
                dataType: 'json',
                data: data,
                headers: {
                    "Authorization": "Bearer b16cef22dd918befa7ed4cad3bb4b161a66a0d49bb582845a8cd7a398b0e8a70"
                },
                success: function(data, status){
                    open_createResultModal("success", "Upload Post Successfully");
                },
                error: function(data){
                    var body = xml.responseJSON;
                    var message = "";

                    if(body.length > 0){
                        message += body[0].field + " ";
                        message += body[0].message;
                    }else{
                        message += body.message;
                    }

                    open_createResultModal("error", message);
                }
            });
        }

        function AJAX_update_posts(data){
            // AJAX call to update post
            var xml = $.ajax({
                url: "https://gorest.co.in/public/v2/posts/" + data.id,
                method: "PATCH",
                dataType: 'json',
                data: data,
                headers: {
                    "Authorization": "Bearer b16cef22dd918befa7ed4cad3bb4b161a66a0d49bb582845a8cd7a398b0e8a70"
                },
                success: function(data, status){
                    open_updateResultModal("success", "Update Successfully");
                },
                error: function(data){
                    var body = xml.responseJSON;
                    var message = "";

                    if(body.length > 0){
                        message += body[0].field + " ";
                        message += body[0].message;
                    }else{
                        message += body.message;
                    }

                    open_updateResultModal("error", message);
                }
            });
        }

        function AJAX_delete_posts(post_id){
            // AJAX call to delete post
            $.ajax({
                url: "https://gorest.co.in/public/v2/posts/" + post_id,
                method: "DELETE",
                headers: {
                    "Authorization": "Bearer b16cef22dd918befa7ed4cad3bb4b161a66a0d49bb582845a8cd7a398b0e8a70"
                },
                success: function(data, status){
                    open_deleteResultModal("success");
                },
                error: function(data){
                    open_deleteResultModal("fail");
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