<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <title>Websockets Chat - Profile</title>
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
                    if($this->session->userdata('logged_in') != 1){
                        printf('<li><a href="/register/" data-toggle="modal">Register</a></li>');
                        printf('<li><a href="/login/" data-toggle="modal">Login</a></li>');
                    }
                    else{
                        //$avatarLink = '<img src="http://graph.facebook.com/' . $this->session->userdata('username') . '/picture" class="header-avatar" />';
                        $avatarLink = '<img src="' . $this->session->userdata('avatar') . '" id="header-avatar" class="header-avatar" />';
                        printf('<li class="dropdown">');
                        printf('<a href="#" class="dropdown-toggle" data-toggle="dropdown">');
                        printf('<span>%s <span id="nav-username">%s<span></span>', $avatarLink, $this->session->userdata('username'));
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
                <div class="form-signin mg-btm col-md-6 col-md-offset-3">
                    <ul class="nav nav-tabs faq-cat-tabs">
                        <li class="active"><a href="#userDetails" data-toggle="tab" id="userDetailsTab">User Details</a></li>
                        <li><a href="#userAvatar" data-toggle="tab">Avatar</a></li>
                        <li><a href="#userPassword" data-toggle="tab" id="userPasswordTab">Reset Password</a></li>
                        <li><a href="#userDelete" data-toggle="tab">Delete Account</a></li>
                    </ul>
                    <div class="main tab-content">
                        <div class="tab-pane in fade active" id="userDetails">
                            <div id="userMessageContainer" class="col-md-12" ><div class="alert alert-info">Update your account details</div></div>
                            <div id="updateUsernameInputGroup" class="form-group">
                                <label class="control-label" for="username">Username</label>
                                <input type="text" class="form-control" placeholder="Username" name="username" id="username" data-toggle="tooltip" title="Username" value="<?php echo $this->session->userdata('username'); ?>">
                            </div>
                            <div id="updateEmailInputGroup" class="form-group">
                                <label class="control-label" for="email">Email Address</label>
                                <input type="text" class="form-control" placeholder="Email" name="email" id="email" value="<?php echo $this->session->userdata('email_address'); ?>">
                            </div>
                            <div id="updateFirstNameInputGroup" class="form-group">
                                <label class="control-label" for="firstname">First Name</label>
                                <input type="text" class="form-control" placeholder="first name" name="firstname" id="firstname" value="<?php echo $this->session->userdata('first_name'); ?>">
                            </div>
                            <div id="updateLastNameInputGroup" class="form-group">
                                <label class="control-label" for="lastname">Last Name</label>
                                <input type="text" class="form-control" placeholder="last name" name="lastname" id="lastname" value="<?php echo $this->session->userdata('last_name'); ?>">
                            </div>
                            <span class="clearfix"></span>
                            <div class="pull-right">
                                <button class="btn btn-large btn-danger has-spinner" id="updateUserButton"><span class="spinner"><i class="fa fa-refresh fa-spin"></i></span>Update Details</button>
                            </div>	
                        </div>
                        <div class="tab-pane in fade" id="userAvatar">	
                            <div id="avatarMessageContainer" class="col-md-12" ><div class="alert alert-info">Change your avatar image</div></div>
                                <div id="uploadAvatarControlContainer" class="col-md-7">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                          <?php echo '<img id="avatarPreview" src="' . $this->session->userdata('avatar') . '" />'; ?>
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                        <div>
                                          <form enctype="multipart/form-data" id="avatarForm">
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new">Select New Image</span>
                                                  <input type="file" name="selectAvatarButton" id="selectAvatarButton" />
                                            </span>
                                          </form>
                                        </div>
                                    </div>

                                    <button class="btn btn-large btn-danger has-spinner" id="uploadNewAvatarButton"><span class="spinner"><i class="fa fa-refresh fa-spin"></i></span>Upload</button>
                                </div>
                            <div id="socialAvatarControlContainer" class="col-md-5">
                                <a id="useFaceebokAvatarButton" class="btn btn-block btn-facebook">
                                    <i class="fa fa-facebook"></i> Use Facebook Avatar
                                </a>
                            </div>
                        </div>
                        <div class="tab-pane in fade" id="userPassword">
                            <div id="passwordMessageContainer" class="col-md-12" >
                                <div class="alert alert-info">Reset your password</div>
                                
                            </div>
                            <div id="resetPasswordInputGroup1" class="form-group">
                                <label class="control-label" for="password1">Reset Password</label>
                                <input type="password" class="form-control"  name="password1" id="password1" autofocus>
                            </div>
                            <div id="resetPasswordInputGroup2" class="form-group">
                                <label class="control-label" for="password2">Confirm Password</label>
                                <input type="password" class="form-control" name="password2" id="password2" >
                            </div>
                            <span class="clearfix"></span>
                            <div class="pull-right">
                                <button class="btn btn-large btn-danger has-spinner" id="resetPasswordButton"><span class="spinner"><i class="fa fa-refresh fa-spin"></i></span>Reset Password</button>
                            </div>
                        </div>
                        <div class="tab-pane in fade" id="userDelete">
                                   <button class="btn btn-block btn-danger has-spinner" id="deleteAccount"><span class="spinner"><i class="fa fa-refresh fa-spin"></i></span>Delete Account</button>
                        </div>
                       
                        <span class="clearfix"></span>	
                    </div>
                    
                </div>
            </div>
        </div>
    </body>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="/assets/js/plugins/bootstrap.min.js"></script>
    <script src="/assets/js/custom/validate.js"></script>
    <script src="/assets/js/custom/profile.js"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-47186422-1', 'burnsforcedevelopment.com');
        ga('send', 'pageview');

    </script>
</html>