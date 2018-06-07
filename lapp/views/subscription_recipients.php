
<section class="content">
    <h1 class="page-title">
        <small>Subscription and Revenue Report</small>
    </h1>
    <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light portlet-fit bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-settings font-red"></i>
                                            <span class="caption-subject font-red sbold uppercase">Recipients</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-toolbar">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="btn-group">
                                                        <button id="sample_editable_1_new" class="btn green"> Add New
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="btn-group pull-right">
                                                      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                            <thead>
                                                <tr>
                                                    <th> ID </th>
                                                    <th> Email </th>
                                                    <th> Receive Subscription Report </th>
                                                    <th> Receive Revenue Report </th>
                                                    <th> Active </th>
                                                    <th>Created On</th>
                                                    <th> Edit </th>
                                                    <th> Delete </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($subscription_recipients as $item) { ?>
                                                <tr>
                                                    <td><?=$item->id?></td>
                                                    <td><?=$item->email?></td>
                                                    <td><?=$item->receive_subscription?></td>
                                                    <td><?=$item->receive_revenue?> </td>
                                                    <td class="center"> <?=$item->is_active?> </td>
                                                    <td><?=$item->created_at?></td>
                                                    <td>
                                                        <a class="edit" href="javascript:;"> Edit </a>
                                                    </td>
                                                    <td>
                                                        <a class="delete" href="javascript:;"> Delete </a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                        </div>
</section>