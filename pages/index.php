
<?php
include "database.php";
include "database2.php";

//problems and escalated picks
$problemsquery = $sbt->prepare ("SELECT COUNT(item) as xcount FROM iciqty01 WHERE (qbin = 'NOTFOUND' OR qbin = 'CONDITION') AND qonhand > 0" );
$problemsquery->execute ();
$problemsquery->setFetchMode ( PDO::FETCH_ASSOC );
$problems = $problemsquery->fetch ();
$probcount = $problems['xcount'];

if ($probcount > 0) {
    $probstatus = "red";
} else {
    $probstatus = "danger";
}

$soquery = $pickingtable->prepare ("SELECT count(item) as so FROM rfpicks WHERE reason = 'Sales Order' AND active = .t.");
$soquery->execute ();
$soquery->setFetchMode ( PDO::FETCH_ASSOC );
$xsopicks = $soquery->fetch ();
$sopicks = $xsopicks['so'];

$wkquery = $pickingtable->prepare ("SELECT count(item) as wk FROM rfpicks WHERE reason = 'Sales Order' and substr(bin,4,2)='BK' AND active = .t.");
$wkquery->execute ();
$wkquery->setFetchMode ( PDO::FETCH_ASSOC );
$wkpicks = $wkquery->fetch ();
$walkingpicks = $wkpicks['wk'];

if ($walkingpicks > 0) {
    $wkstatus = "green";
} else {
    $wkstatus = "success";
}

$rpquery = $pickingtable->prepare ("SELECT count(item) as rep FROM rfpicks WHERE reason = 'Replenishment' AND active = .t.");
$rpquery->execute ();
$rpquery->setFetchMode ( PDO::FETCH_ASSOC );
$rppicks = $rpquery->fetch ();
$reppicks = $rppicks['rep'];

if ($reppicks > 0) {
    $repstatus = "yellow";
} else {
    $repstatus = "info";
}

$oldquery = $pickingtable->prepare ("SELECT count(item) as old FROM rfpicks WHERE active = .t. and date < date()-2");
$oldquery->execute ();
$oldquery->setFetchMode ( PDO::FETCH_ASSOC );
$oldpick = $oldquery->fetch ();
$oldpicks = $oldpick['old'];

if ($oldpicks > 0) {
    $oldstatus = "red";
    } else {
    $oldstatus = "danger";
}

$orquery = $sbt->prepare ("SELECT count(item) as orphans FROM orphanpicks");
$orquery->execute ();
$orquery->setFetchMode ( PDO::FETCH_ASSOC );
$orpick = $orquery->fetch ();
$orpicks = $orpick['orphans'];

if ($orpicks > 0) {
    $orstatus = "yellow";
} else {
    $orstatus = "info";
}

$sorquery = $pickingtable->prepare ("SELECT count(item) as retpick FROM rfpicks where active = .t. and reason = 'Sales Order' and bin = 'RT'");
$sorquery->execute ();
$sorquery->setFetchMode ( PDO::FETCH_ASSOC );
$sorpick = $sorquery->fetch ();
$sorpicks = $sorpick['retpick'];

if ($sorpicks > 0) {
    $sorstatus = "green";
} else {
    $sorstatus = "success";
}

$ovquery = $sbt->prepare ("SELECT COUNT(a.item) as ov FROM allretail a LEFT JOIN iciloc01 b ON a.item = b.item AND b.loctid = 'NEWK' WHERE a.qty > b.maxlevel AND b.maxlevel > 0");
$ovquery->execute ();
$ovquery->setFetchMode ( PDO::FETCH_ASSOC );
$ovpicks = $ovquery->fetch ();
$overretail = $ovpicks['ov'];
print_r($overretail);


if ($overretail > 0) {
    $ovstatus = "yellow";
} else {
    $ovstatus = "info";
}


$picksquery = $pickingtable->prepare ("SELECT * from rfpicks where active = .t. order by pickorder");
$picksquery->execute ();
$picksquery->setFetchMode ( PDO::FETCH_ASSOC );

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Picks Dashboard</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Cosmo Warehouse Admin v1.0</a>
            </div>
            <!-- /.navbar-header -->

            <!-- /.navbar-top-links -->
            <div class="navbar-default sidebar" role="navigation">
                <p></p>
                <img src="logo.jpg" height="125"/>
                <p></p>
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i>Refresh</a>
                        </li>


                     </ul>
                </div>
                <!-- /.sidebar-collapse -->

                <p></p>
                            <!-- <div class="col-sm-1 col-md-1"> -->
                <div class="panel panel-<?php print_r($orstatus)?>">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-2">
                                <i class="fa fa-child fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php print_r($orpicks)?></div>
                                <div>Orphaned Picks!</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
                <p></p>
                <!-- <div class="col-sm-1 col-md-1"> -->
                <div class="panel panel-<?php print_r($ovstatus) ?>">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-2">
                                <i class="fa fa-stack-overflow fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php print_r($overretail)?></div>
                                <div>Retail Overstock Items!</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
                <p></p>
                <!-- <div class="col-sm-1 col-md-1"> -->
                <div class="panel panel-<?php print_r($oldstatus)?>">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-2">
                                <i class="fa fa-warning fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php print_r($oldpicks)?></div>
                                <div>More than 48hrs!</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>

                <p></p>
                <!-- <div class="col-sm-1 col-md-1"> -->
                <div class="panel panel-<?php print_r($probstatus)?>">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-2">
                                <i class="fa fa-support fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php print_r($probcount)?></div>
                                <div>Escalated Picks!</div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>

                </div>


    </div>


           <!-- </div> -->
            <!-- /.navbar-static-side -->



        </nav>




        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Picking Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php print_r($sopicks)?></div>
                                    <div>Sales Order Picks!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-<?php print_r($wkstatus)?>">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-road fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php print_r($walkingpicks)?></div>
                                    <div>Walking SO Picks!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-<?php print_r($sorstatus)?>">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-send fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php print_r($sorpicks)?></div>
                                    <div>SO Picks in Retail!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-<?php print_r($repstatus)?>">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-recycle fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php print_r($reppicks)?></div>
                                    <div>Replenishment Picks!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Picking Table Line Items
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>Bin</th>
                                        <th>Pickorder</th>
                                        <th>Reason</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php while ($r = $picksquery->fetch() ): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($r['item']);?> </td>
                                            <td><?php echo htmlspecialchars($r['descrip']);?> </td>
                                            <td><?php echo htmlspecialchars($r['qty']);?> </td>
                                            <td><?php echo htmlspecialchars($r['bin']);?> </td>
                                            <td><?php echo htmlspecialchars($r['pickorder']);?> </td>
                                            <td><?php echo htmlspecialchars($r['reason']);?> </td>
                                            <td><?php echo htmlspecialchars($r['date']);?> </td>

                                        </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });
    </script>

</body>

</html>
