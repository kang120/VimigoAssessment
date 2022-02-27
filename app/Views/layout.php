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