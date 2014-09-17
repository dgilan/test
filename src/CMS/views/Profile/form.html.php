<?php if (!isset($errors)) {
    $errors = array();
} ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">My Profile</h3>
    </div>
    <div class="panel-body">

        <form class="form-horizontal" role="form" method="post" action="/profile">
            <?php foreach ($errors as $error) { ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <strong>Error!</strong> <?php echo $error ?>
                </div>
            <?php } ?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Email</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="email" placeholder="Email" value="<?php echo $user->email ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Name</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo $user->name ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-success pull-right">OK</button>
        </form>
    </div>
</div>