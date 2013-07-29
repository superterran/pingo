<?php if(isset($_GET['status'])) { echo (bool) file_get_contents('http://google.com'); die(); } ?>
<?php if(isset($_GET['ip'])) { echo (string) trim(file_get_contents('http://phihag.de/ip/')); die(); } ?>
<?php if(!isset($_COOKIE['repeat'])) setcookie('repeat', '20', time()+60*60*24*30); // set repeat cookie ?>
<?php if(isset($_GET['repeat'])) {  $_COOKIE['repeat'] = $_POST['repeat']; } ?>
<!doctype>
<html>
    <head>
        <title>Pingo</title>
        <script src="./prototype.js"></script>
        <style type="text/css">
            h1, h2 {

                font-family: Helvetica;
                color: #fff;
                margin: 0px; padding: 0px;
            }

            h1 { font-size: 72px; }
            h2 { font-size: 54px;}

            #content {

                position: absolute;
                top: 40%;
                left: 0px;
                right: 0px;
                text-align: center;

            }

            body {

                text-align: center;
                vertical-align: middle;
                font-family: helvetica;
            }

            #controls {

                position: absolute;
                right: 0px;
                color: white;
                text-align: right;
                padding-right: 20px;
                padding-top: 5px;
            }

            #controls input[type="text"] {

                width: 50px;

            }

            #controls label {
                float: left;
                font-weight: bold;
                width: 80px;
                text-align: left;
                display: block;
                padding-top: 3px;

            }

            #options {
                display: block;

            }

            #controls a {

                color: white;
                text-decoration: none;
                font-weight: bold;
            }

        </style>
    </head>
    <body bgcolor="yellow">

        <div id="controls">
            <a href="#" onclick="$('options').toggle()">Options</a>
            <form action = "?repeat" method="post" id="options" style="display: none">
            <label>Repeat: </label> <input type="text" name = "repeat" value="<?php echo $_COOKIE['repeat'] ?>"><input type="submit" value = "update">
            </form>
        </div>
        <div id="content"></div>

        <script type="text/javascript">


            var pingo = Class.create({

                repeat: <?php echo (int) $_COOKIE['repeat'] ?>,

                trans: <?php if(is_file('remoteips.json')) echo file_get_contents('remoteips.json'); else echo '{ }'; ?>,

                ip: '',

                state: 0,

                initialize: function() {

                    this.testLoop();
                    this.getIp()

                    new PeriodicalExecuter(function(pe) {this.testLoop()}.bind(this), this.repeat);
                },

                getIp: function() {

              //      $('content').innerHTML = '<h1>Obtaining Info</h1>';

                    new Ajax.Request('http://<?php echo $_SERVER['SERVER_NAME'] ?>?ip', {

                        onSuccess: function(response) {
                            $('content').innerHTML = '';
                            this.ip = response.responseText.trim();
                            if(response.responseText.trim() in this.trans) {
                                $('content').innerHTML += '<h1>'+this.trans[response.responseText]+'</h1>';
                            }
                                $('content').innerHTML += '<h2>'+response.responseText+'</h2>';



                        }.bind(this)
                    })
                },

                testLoop: function(url) {

                    var prevstate = this.state;

                    new Ajax.Request('http://<?php echo $_SERVER['SERVER_NAME'] ?>?status=' + url, {
                        onSuccess: function(response) {

                            if(response.responseText == '1') {
                                this.state = 1;
                                document.bgColor = 'green';
                                if(prevstate == 0) $('content').innerHTML = '<h1>Obtaining Info</h1>';
                                    this.favIcon();
                            } else {
                                this.ip = '';
                                document.bgColor = 'red';
                                this.state = 0;
                                $('content').innerHTML = '<h1>Disconnected</h1>';
                            }

                            this.favIcon();

                        }.bind(this),
                        onFailure: function(response) {
                            document.bgColor = 'red';
                            this.ip = '';
                            this.state = 0;
                            $('content').innerHTML = '<h1>Broken</h1>';
                            this.favIcon();
                        }
                    });

                    if((this.state == 1) && (this.ip == '')) {
                        this.getIp();
                    }

                },

                favIcon: function() {

                    $$('link').each(function(e){ e.remove() })
                    var link = document.createElement('link');
                    link.type = 'image/x-icon';
                    link.rel = 'shortcut icon';
                    link.href = './icos/'+document.bgColor+'.ico';
                    document.getElementsByTagName('head')[0].appendChild(link);

                }

            });

            var app = new pingo();

        </script>

    </body>
</html>