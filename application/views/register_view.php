<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <title>Websockets Chat - Register</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Portfolio project showing skills with Node.js, PHP, Javascript, jQuery, AJAX and API's">
        <meta name="author" content="Patrick Burns">
        <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/css/bootstrap-responsive.min.css" rel="stylesheet">
        <script type="text/javascript">
            if (window.location.hash && window.location.hash == '#_=_') {
                if (window.history && history.pushState) {
                    window.history.pushState("", document.title, window.location.pathname);
                } else {
                    // Prevent scrolling by storing the page's current scroll offset
                    var scroll = {
                        top: document.body.scrollTop,
                        left: document.body.scrollLeft
                    };
                    window.location.hash = '';
                    // Restore the scroll offset, should be flicker free
                    document.body.scrollTop = scroll.top;
                    document.body.scrollLeft = scroll.left;
                }
            }
        </script>
    </head>
    <body>    
        <nav class="navbar navbar-default" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Websockets Chat</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="#">Source</a></li>
                    <li><a href="http://www.linkedin.com/in/burnsforce">Contact</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Projects <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="http://simpleurl.co">URL Shortener</a></li>
                            <li><a href="http://burnsforcedevelopment.com/linkbaitgenerator/">Link Bait Generator</a></li>
                            <li><a href="http://chat.burnsforcedevelopment.com/">Web Sockets Chat</a></li>
                            <li><a href="http://burnsforcedevelopment.com/citysay/">Kansas City Says</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                        if ($this->session->userdata('logged_in') != 1){
                            printf('<li><a href="/register/" data-toggle="modal">Register</a></li>');
                            printf('<li><a href="/login/" data-toggle="modal">Login</a></li>');
                        } else{
                            $avatarLink = '<img src="http://graph.facebook.com/' . $this->session->userdata('username') . '/picture" class="header-avatar" />';
                            printf('<li class="dropdown">');
                            printf('<a href="#" class="dropdown-toggle" data-toggle="dropdown">');
                            printf('<span>%s %s</span>', $avatarLink, $this->session->userdata('username'));
                            printf('</a>');
                            printf('<ul class="dropdown-menu">');
                            printf('<li><a href="/user"><i class="icon-user"></i> Profile</a></li>');
                            printf('<li class="divider"></li>');
                            printf('<li><a href="/logout"><i class="icon-off"></i> Logout</a></li>');
                            printf('</ul>');
                            printf('</li>');
                        }
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
        <div class="container">
            <div class="row">
                <form class="form-signin mg-btm col-md-6 col-md-offset-3">
                    <h3 class="heading-desc">
                        Register an account with Websockets Chat</h3>
                    <div class="social-box">
                        <div class="row mg-btm">
                            <div class="col-md-12">
                                <a href="/facebook" class="btn btn-block btn-facebook">
                                    <i class="fa fa-facebook"></i> Register with Facebook
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                        </div>
                    </div>
                    <div class="main">	
                        <div id="registerMessageContainer" class="col-md-12" >
                        </div>
                        <div id="registerUsernameInputGroup" class="form-group">
                            <label class="control-label" for="username">Username</label>
                            <input type="text" class="form-control" placeholder="Username" name="username" id="username" autofocus>
                        </div>
                        <div id="registerEmailInputGroup" class="form-group">
                            <label class="control-label" for="email">Email Address</label>
                            <input type="text" class="form-control" placeholder="Email" name="email" id="email">
                        </div>
                        <div id="registerPasswordInputGroup" class="form-group">
                            <label class="control-label" for="password">Password</label>
                            <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                        </div>
                        <span class="clearfix"></span>	
                    </div>
                    <div class="login-footer">
                        <div class="row">
                            <div class="col-xs-6 col-md-6">
                                <div class="left-section">
                                    <a href="/passwordreset">Forgot your password?</a>
                                    <a href="/login">Login Here</a>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-6 pull-right">
                                <button type="submit" id="registerButton" class="btn btn-large btn-danger pull-right">Register</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </body>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="/assets/js/plugins/bootstrap.min.js"></script>
    <script src="/assets/js/custom/validate.js"></script>
    <script src="/assets/js/custom/register.js"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-47186422-1', 'burnsforcedevelopment.com');
        ga('send', 'pageview');

    </script>
</html>