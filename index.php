<?php 
include_once 'header.php';
?>
    
    <?php if (isset($_SESSION['u_email'])) { 
        include_once 'userpage.php';
    } 
    elseif (isset($_SESSION['e_uname'])) {
        if ($_SESSION['employee_type'] == "manager") {
            include_once 'managerpage.php';
        }
        elseif ($_SESSION['employee_type'] == "dianomeas") {
            include_once 'deliverypage.php';
        }
    }  
    else { ?>
        <div class="container-fluid  " id="cardResponsive">
            <div class="card text-white bg-dark " style="max-width: 26rem">
                <div class="card-body">
                    <img class="card-img-fluid" src="img/logo.png">
                    <div class="card-body">
                        <h1 class="card-title">Καλώς Ορίσατε !</h1>
                        <br>
                        <button type="button" class="btn btn-light btn-lg" data-toggle="modal" data-target="#loginModal">Παραγγείλετε Τώρα</button>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="loginModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content text-white bg-dark">
                    <div class="modal-body">
                        <ul class="nav nav-tabs " id="myTab" role="tablist">
                            <li class="nav-item" id="tab1">
                                <a class="nav-link active show" id="login-tab" data-toggle="tab" href="#loginForm" role="tab" aria-selected="true">Σύνδεση</a>
                            </li>
                            <li class="nav-item" id="tab2">
                                <a class="nav-link" id="register-tab" data-toggle="tab" href="#registerForm" role="tab" aria-selected="false">Εγγραφή</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="loginForm" role="tabpanel">
                            <form class="form" action="includes\login.php" method="POST" name="login_form">
                                <div class="form-group">
                                    <label for="exampleDropdownFormEmail2">Email Χρήστη</label>
                                    <input class="form-control" id="inlineFormInputGroup" placeholder="Το email σας" required="" type="email" name="uemail">
                                </div>
                                <div class="form-group">
                                    <label for="inlineFormInputGroup">Κωδικός Πρόσβασης</label>
                                    <input class="form-control" id="inlineFormInputGroup" placeholder="Ο κωδικός πρόσβασης σας" required="" type="password" name="pwd">
                                </div>
                                <div class="block-buttons">
                                <div class="btn-group">
                                <button type="submit" class="btn btn-light" name="submit">Σύνδεση</button>
                                <button type="button" class="btn btn-danger " data-dismiss="modal">Κλείσιμο
                                    </button>
                                </div>
                                </div>
                            </form>
                            </div>
                            <!---->
                            <div class="tab-pane fade" id="registerForm" role="tabpanel" >
                            <form class="form" action="includes\signup.php" method="POST" name="register_form">
                                <div class="form-group">
                                    <label for="exampleDropdownFormEmail2">Email Χρήστη</label>
                                    <input class="form-control" id="inlineFormInputGroup" placeholder="Το email σας" required="" type="email" name="email">
                                </div>
                                
                                <div class="form-group">
                                    <label for="inlineFormInputGroup">Επιθυμητός Κωδικός Πρόσβασης</label>
                                    <input class="form-control" id="inlineFormInputGroup" placeholder="Ο κωδικός πρόσβασης σας" required="" type="password" name="password">
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleDropdownFormEmail2">Τηλέφωνο Χρήστη</label>
                                    <input class="form-control" id="inlineFormInputGroup" placeholder="Το τηλεφωνο σας" required="" type="text" name="phone">
                                </div>
                                <hr class="light">
                                <div class="block-buttons">
                                <div class="btn-group">
                                <button type="submit" class="btn btn-light" name="submit">Εγγραφή</button>
                                <button type="button" class="btn btn-danger " data-dismiss="modal">Κλείσιμο
                                    </button>
                                </div>
                                </div>
                            </form>
                            </div>
                            <!---->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    
    <?php 
    include_once 'footer.php';
    ?>
