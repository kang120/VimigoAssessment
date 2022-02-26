<html>
    <head>
        <?php $this->renderSection("title") ?>
        <style>
            *{
                margin: 0;
                padding: 0;
            }

            header{
                background: #0e1966;
                color: white;
                text-align: center;
                font-size: 3em;
                padding: 15px 0;
            }

            main{
                padding-top: 50px;
                padding-left: 15%;
                padding-right: 15%;
            }
        </style>
    </head>

    <body>
        <header>
            Post Management
        </header>

        <main>
            <?php $this->renderSection("content") ?>
        </main>
    </body>
</html>