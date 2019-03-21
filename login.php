<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <style>
                    body{
        padding-top:4.2rem;
		padding-bottom:4.2rem;
		background:rgba(0, 0, 0, 0.76);
        }
        a{
         text-decoration:none !important;
         }
         h1,h2,h3{
         font-family: 'Kaushan Script', cursive;
         }
          .myform{
		position: relative;
		display: -ms-flexbox;
		display: flex;
		padding: 1rem;
		-ms-flex-direction: column;
		flex-direction: column;
		width: 100%;
		pointer-events: auto;
		background-color: #fff;
		background-clip: padding-box;
		border: 1px solid rgba(0,0,0,.2);
		border-radius: 1.1rem;
		outline: 0;
		max-width: 500px;
		 }
         .tx-tfm{
         text-transform:uppercase;
         }
         .mybtn{
         border-radius:50px;
         }
        
         .login-or {
         position: relative;
         color: #aaa;
         margin-top: 10px;
         margin-bottom: 10px;
         padding-top: 10px;
         padding-bottom: 10px;
         }
         .span-or {
         display: block;
         position: absolute;
         left: 50%;
         top: -2px;
         margin-left: -25px;
         background-color: #fff;
         width: 50px;
         text-align: center;
         }
         .hr-or {
         height: 1px;
         margin-top: 0px !important;
         margin-bottom: 0px !important;
         }
         .google {
         color:#666;
         width:100%;
         height:40px;
         text-align:center;
         outline:none;
         border: 1px solid lightgrey;
         }
          form .error {
         color: #ff0000;
         }
         #second{display:none;}
        </style>
    </head>
    <body>
        <div class="container">

            <div class="first">
                <div class="myform form ">
                        <div class="logo mb-3">
                            <div class="col-md-12 text-center">
                                <h1>Login</h1>
                            </div>
                        </div>
                        <?php
                        if (!isset($_GET['code'])):
                            $authorizationUrl = $twitch->getAuthorizationUrl($state);
                        ?>
                        <div class="col-md-12 mb-3">
                            <p class="text-center">
                                <a href="<?php echo $authorizationUrl;?>" class="google btn mybtn"><i class="fa fa-google-plus">
                                </i> Use your Twitch account
                                </a>
                            </p>
                        </div>
                        <?php
                        else:
                            try{
                                $twitch->getState();
                                header("Location: /streamer.php", 301);exit;
                            }catch(\App\Twitch\Exceptions\TwitchOauthStateException $e){
                                header("HTTP/1.1 400 Bad Request");
                                exit($e->getMessage());
                            }
                            
                        ?>
                        <?php endif;?>
                            
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>