<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Testing services</title>
        <link rel="stylesheet" type="text/css" href="css/testService.css" media="screen" />
        <link href="css/dark-hive/jquery-ui-1.10.2.custom.min.css" rel="stylesheet"/>
        <script src="js/jquery-1.9.1.min.js"></script>
        <script src="js/jquery-ui-1.10.2.custom.min.js"></script> 
        <script src="js/testService.js"></script>
        <link rel="stylesheet" type="text/css" href="css/flexlayout.css"></link>
    </head>

    <body>

        <div class="pageContent">
            <div id="main">
                <div class="container">
                    <h1>Test Services Sandbox</h1>
                </div>

                <div class="container" id="mainContainer">
                    <div id="sandbox">
                        <p>
                            <input id="service" value="" placeholder="Service name..."/>
                            <button class="button" id="test">Test</button>
                            <button class="button" id="help">Help</button>
                            <p>
                                <button class="button" id="add">Add parameter</button>
                            </p>
                        </p>
                        <div id="params"></div>    
                        <div id="result">
                            <p align="center" ><img src="images/sandbox.gif"/></p>                            
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </body>
</html>
