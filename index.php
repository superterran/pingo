<?php if(isset($_GET['status'])) { echo (bool) file_get_contents('http://google.com'); die(); } ?>
<?php if(isset($_GET['ip'])) { echo (string) trim(file_get_contents('http://phihag.de/ip/')); die(); } ?>
<!doctype>
<html>
    <head>
        <title>Pingo</title>
        <script src="http://ajax.googleapis.com/ajax/libs/prototype/1.7.1.0/prototype.js"></script>

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
            }

        </style>
    </head>
    <body bgcolor="yellow">

        <div id="content"></div>

        <script type="text/javascript">


            var pingo = Class.create({

                trans: {
                    '1.2.3.4':'Comcast Cable'
                },

                initialize: function() {

                    this.testLoop();
                    this.getIp()

                    new PeriodicalExecuter(function(pe) {this.testLoop()}.bind(this), 3);
                },

                getIp: function() {
                    new Ajax.Request('http://<?php echo $_SERVER['SERVER_NAME'] ?>?ip', {

                        onSuccess: function(response) {

                            $('content').innerHTML = '';
                            if(response.responseText.trim() in this.trans) {
                                $('content').innerHTML += '<h1>'+this.trans[response.responseText]+'</h1>';
                            }
                                $('content').innerHTML += '<h2>'+response.responseText+'</h2>';



                        }.bind(this)
                    })
                },

                testLoop: function(url) {
                    new Ajax.Request('http://<?php echo $_SERVER['SERVER_NAME'] ?>?status=' + url, {
                        onSuccess: function(response) {

                            if(response.responseText == '1') {

                                document.bgColor = 'green'
                                this.favIcon();
                            } else {
                                document.bgColor = 'red'
                                $('content').innerHTML = '<h1>Disconnected</h1>';
                            }
                        }.bind(this),
                        onFailure: function(response) {
                            document.bgColor = 'red'
                            $('content').innerHTML = '<h1>Broken</h1>';
                        }
                    });

                    if($('content').innerHTML == '<h1>Disconnected</h1>') this.getIp();
                    if($('content').innerHTML == '') this.getIp();
                    this.favIcon();
                },

                favIcon: function() {

                    $$('link').each(function(e){ e.remove() })
                    var link = document.createElement('link');
                    link.type = 'image/x-icon';
                    link.rel = 'shortcut icon';
                    link.href = './'+document.bgColor+'.ico';
                    document.getElementsByTagName('head')[0].appendChild(link);

                }

            });

            var app = new pingo();

        </script>

    </body>
</html>